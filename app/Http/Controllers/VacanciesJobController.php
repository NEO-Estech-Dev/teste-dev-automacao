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

            return response()->json($vacancies, 200);
        }else{
            Cache::remember('vacancies', 60, function() {
                return Vacancy::where('active', Vacancy::ACTIVE)->paginate(20);
            });

            $vacancies = Cache::get('vacancies');

            return response()->json($vacancies, 200);
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
            'location_vacancy_job' => 'required|string|max:255',
            'salary_vacancy_job' => 'required|numeric',
            'company_name' => 'required|string|max:255',
            'type_vacancy_job' => 'required|integer|between:0,2',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        Vacancy::create([
            'title_vacancy_job' => $request->input('title_vacancy_job'),
            'location_vacancy_job' => $request->input('location_vacancy_job'),
            'salary_vacancy_job' => $request->input('salary_vacancy_job'),
            'company_name' => $request->input('company_name'),
            'type_vacancy_job' => $request->input('type_vacancy_job'),
        ]);

        return response()->json(['message' => 'Vacancy was created'], 201);
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
            return response()->json($vacancy, 200);
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

        if($vacancy) {
            /*if(LevelUserEnum::getLevel($vacancy->users[0]->level) != LevelUserEnum::LEVELS['RECRUITER']) {
                return response()->json(['message' => 'User not active this vacancy'], 403);
            }
            */

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
            'location_vacancy_job' => 'required|string|max:255',
            'salary_vacancy_job' => 'required|numeric',
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
            $vacancy->location_vacancy_job = $request->input('location_vacancy_job');
            $vacancy->salary_vacancy_job = $request->input('salary_vacancy_job');
            $vacancy->company_name = $request->input('company_name');
            $vacancy->type_vacancy_job = $request->input('type_vacancy_job');

            $vacancy->update();

            return response()->json($vacancy, 200);
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

        if($vacancy) {
            $vacancy->active = Vacancy::DESACTIVATE;
            $vacancy->update();

            return response()->json(['message' => 'Vacancy Deleted Successfully'], 200);
        }

        return response()->json(['message' => 'Vacancy not found'], 404);
    }
}
