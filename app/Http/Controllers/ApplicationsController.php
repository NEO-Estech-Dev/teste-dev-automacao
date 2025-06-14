<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ApplicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Cache::has('applications')) {
            $applications = Cache::get('applications');

            return response()->json($applications, 200);
        }else{
            Cache::remember('applications', 60, function() {
                return Application::paginate(20);
            });

            $applications = Cache::get('applications');

            return response()->json($applications, 200);
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
            'vacancy_id' => 'required|integer|exists:tbl_vacancies_job,id',
            'candidate_id' => 'required|integer|exists:tbl_candidates,id',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        Application::create([
            'vacancy_id' => $request->input('vacancy_id'),
            'candidate_id' => $request->input('candidate_id'),
        ]);

        return response()->json(['message' => 'Application was created'], 201);
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

        $application = Application::find($id);

        if($application) {
            return response()->json($application, 200);
        }

        return response()->json(['message' => 'Application not found'], 404);
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
            'vacancy_id' => 'required|integer|exists:tbl_vacancies_job,id',
            'candidate_id' => 'required|integer|exists:tbl_candidates,id',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        $application = Application::find($id);

        if($application) {
            $application->vacancy_id = $request->input('vacancy_id');
            $application->candidate_id = $request->input('candidate_id');

            $application->update();

            return response()->json($application, 200);
        }

        return response()->json(['message' => 'Application not found'], 404);
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

        $application = Application::find($id);

        if($application) {
            $application->delete();

            return response()->json(['message' => 'Application Deleted Successfully'], 200);
        }

        return response()->json(['message' => 'Application not found'], 404);
    }
}
