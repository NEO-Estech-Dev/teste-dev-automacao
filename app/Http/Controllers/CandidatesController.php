<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class CandidatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Cache::has('candidates')) {
            $candidates = Cache::get('candidates');

            return $candidates;
        }else{
            Cache::remember('candidates', 60, function() {
                return Candidate::where('active', Candidate::ACTIVE)->paginate(20);
            });

            $candidates = Cache::get('candidates');

            return $candidates;
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
            'main_language_programming' => 'required|string',
            'linkedin' => 'required|string',
            'description' => 'required|string',
            'github' => 'required|string',
            'phone' => 'required|string|max:11|min:11',
            'user_id' => 'required|integer|min:1|exists:users,id',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        Candidate::create([
            'main_language_programming' => $request->input('main_language_programming'),
            'linkedin' => $request->input('linkedin'),
            'github' => $request->input('github'),
            'description' => $request->input('description'),
            'phone' => $request->input('phone'),
            'next_phase' => $request->input('next_phase'),
            'user_id' => $request->input('user_id'),
        ]);

        return response()->json(['message' => 'Candidate was created'], 200);
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

        $candidate = Candidate::find($id);

        if($candidate) {
            return $candidate;
        }

        return response()->json(['message' => 'Candidate not found'], 404);
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

        $candidate = Candidate::find($id);

        if($candidate) {
            $candidate->active = Candidate::ACTIVE;
            $candidate->update();

            return response()->json(['message' => 'Candidate was active'], 200);
        }

        return response()->json(['message' => 'Candidate not found'], 404);
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
            'main_language_programming' => 'required|string|max:100',
            'linkedin' => 'required|string|max:255',
            'github' => 'required|string|max:255',
            'phone' => 'required|string|max:11|min:11',
            'next_phase' => 'required|integer|min:1',
            'user_id' => 'required|integer|min:1|exists:users,id',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        $candidate = Candidate::find($id);

        if($candidate) {
            $candidate->main_language_programming = $request->input('main_language_programming');
            $candidate->linkedin = $request->input('linkedin');
            $candidate->github = $request->input('github');
            $candidate->phone = $request->input('phone');
            $candidate->next_phase = $request->input('next_phase');
            $candidate->user_id = $request->input('user_id');

            $candidate->update();

            return $candidate;
        }

        return response()->json(['message' => 'Candidate not found'], 404);
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

        $candidate = Candidate::find($id);

        if($candidate) {
            $candidate->active = Candidate::DESACTIVATE;
            $candidate->update();

            return response()->json(['message' => 'Candidate Deleted Successfully'], 200);
        }

        return response()->json(['message' => 'Candidate not found'], 404);
    }
}
