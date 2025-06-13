<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Cache::has('users')) {
            $users = Cache::get('users');

            return $users;
        }else{
            Cache::remember('users', 60, function() {
                return User::where('active', User::ACTIVE)->paginate(20);
            });

            $users = Cache::get('users');

            return $users;
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
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'level' => 'required|integer|between:0,1',
            'password' => 'required|string|max:255'

        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }


        User::create([
            'fullname' => $request->input('fullname'),
            'email' => $request->input('email'),
            'level' => $request->input('level'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json(['message' => 'User was created'], 201);
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

        $user = User::find($id);

        if($user) {
            return $user;
        }

        return response()->json(['message' => 'User not found'], 404);
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

        $user = User::find($id);

        if($user) {
            $user->active = User::ACTIVE;
            $user->update();

            return response()->json(['message' => 'User was active'], 200);
        }

        return response()->json(['message' => 'User not found'], 404);
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
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'level' => 'required|integer|max:1',
            'password' => 'required|string|max:255',
            'id' => 'required|integer|min:1'

        ]);

        if($validator->fails()) {

            $errors = $validator->errors();

            return response()->json(['message' => $errors], 400);
        }

        $user = User::find($id);

        if($user) {
            $user->fullname = $request->input('fullname');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));

            $user->update();

            return $user;
        }

        return response()->json(['message' => 'User not found'], 404);
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

        $user = User::find($id);

        if($user) {
            $user->active = User::DESACTIVATE;
            $user->update();

            return response()->json(['message' => 'User Deleted Successfully'], 200);
        }

        return response()->json(['message' => 'User not found'], 404);
    }
}
