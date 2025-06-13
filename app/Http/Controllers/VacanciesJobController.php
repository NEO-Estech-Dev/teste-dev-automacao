<?php

namespace App\Http\Controllers;

use App\Http\Enum\LevelUserEnum;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class VacanciesJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Cache::has('vacancies')) {
            $vacancies = Cache::get('vacancies');

            return $vacancies;
        }else{
            Cache::remember('vacancies', 60, function() {
                return Vacancy::where('active', Vacancy::ACTIVE)->paginate(20);
            });

            $vacancies = Cache::get('vacancies');

            return $vacancies;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_vacancy_job' => 'required|string|max:255',
            'description_vacancy_job' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'type_vacancy_job' => 'required|integer|between:0,2',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        Vacancy::create([
            'title_vacancy_job' => $request->input('title_vacancy_job'),
            'description_vacancy_job' => $request->input('description_vacancy_job'),
            'company_name' => $request->input('company_name'),
            'type_vacancy_job' => $request->input('type_vacancy_job'),
        ]);

        return response()->json(['message' => 'Vacancy was created'], 201);
    }

    public function subscribe(Request $request, $id) {
        $request->merge(['id' => $request->route('id')]);

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
            'title_vacancy_job' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        $user = User::find($id);

        if($user) {
            $vacancy = Vacancy::where('title_vacancy_job', $request->input('title_vacancy_job'))
            ->where('company_name', $request->input('company_name'))
            ->first();

            if(LevelUserEnum::getLevel($user->level) != LevelUserEnum::LEVELS['CANDIDATE']) {
                return response()->json(['message' => 'User not be a valid candidate to this vacancy'], 403);
            }

            if(!$vacancy){
                return response()->json(['message' => 'Vacancy not found'], 404);
            }

            $vacancy->candidate_id = $user->id;
            $vacancy->update();

            return response()->json(['message' => "Congratulations, you have applied for the position."], 200);
        }

        return response()->json(['message' => "User not found"], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $request->merge(['id' => $request->route('id')]);

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        $vacancy = Vacancy::find($id);

        if($vacancy) {
            return $vacancy;
        }

        return response()->json(['message' => 'Vacancy not found'], 404);
    }

    public function active(Request $request, $id)
    {
        $request->merge(['id' => $request->route('id')]);

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        $vacancy = Vacancy::find($id);

        if($vacancy && $vacancy->users[0]) {
            if(LevelUserEnum::getLevel($vacancy->users[0]->level) != LevelUserEnum::LEVELS['RECRUITER']) {
                return response()->json(['message' => 'User not active this vacancy'], 403);
            }

            $vacancy->active = Vacancy::ACTIVE;
            $vacancy->update();

            return response()->json(['message' => 'Vacancy was active'], 200);
        }

        return response()->json(['message' => 'Vacancy not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->merge(['id' => $request->route('id')]);

        $validator = Validator::make($request->all(), [
            'title_vacancy_job' => 'required|string|max:255',
            'description_vacancy_job' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'type_vacancy_job' => 'required|integer|max:1',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        $vacancy = Vacancy::find($id);

        if($vacancy) {
            $vacancy->title_vacancy_job = $request->input('title_vacancy_job');
            $vacancy->description_vacancy_job = $request->input('description_vacancy_job');
            $vacancy->company_name = $request->input('company_name');
            $vacancy->type_vacancy_job = $request->input('type_vacancy_job');

            $vacancy->update();

            return $vacancy;
        }

        return response()->json(['message' => 'Vacancy not found'], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->merge(['id' => $request->route('id')]);

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        $vacancy = Vacancy::find($id);

        if($vacancy && $vacancy->users[0]) {
            if(LevelUserEnum::getLevel($vacancy->users[0]->level) != LevelUserEnum::LEVELS['RECRUITER']) {
                return response()->json(['message' => 'User not freeze this vacancy'], 403);
            }

            $vacancy->active = Vacancy::DESACTIVATE;
            $vacancy->update();

            return response()->json(['message' => 'Vacancy Deleted Successfully'], 200);
        }

        return response()->json(['message' => 'Vacancy not found'], 404);
    }
}
