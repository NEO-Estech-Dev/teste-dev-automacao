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

            return response()->json($candidates, 200);
        }else{
            Cache::remember('candidates', 60, function() {
                return Candidate::where('active', Candidate::ACTIVE)->paginate(20);
            });

            $candidates = Cache::get('candidates');

            return response()->json($candidates, 200);
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
            'cpf' => 'required|string|max:15',
            'phone' => 'required|string|max:11|min:11',
            'linkedin' => 'required|string',
            'github' => 'required|string',
            'user_id' => 'required|integer|min:1|exists:users,id',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        Candidate::create([
            'cpf' => $request->input('cpf'),
            'phone' => $request->input('phone'),
            'linkedin' => $request->input('linkedin'),
            'github' => $request->input('github'),
            'user_id' => $request->input('user_id'),
        ]);

        return response()->json(['message' => 'Candidate was created'], 201);
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
            return response()->json($candidate, 200);
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
            'cpf' => 'required|string|max:15',
            'phone' => 'required|string|max:11|min:11',
            'linkedin' => 'required|string',
            'github' => 'required|string',
            'user_id' => 'required|integer|min:1|exists:users,id',
        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        $candidate = Candidate::find($id);

        if($candidate) {
            $candidate->cpf = $request->input('cpf');
            $candidate->phone = $request->input('phone');
            $candidate->linkedin = $request->input('linkedin');
            $candidate->github = $request->input('github');
            $candidate->user_id = $request->input('user_id');

            $candidate->update();

            return response()->json($candidate, 200);
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
