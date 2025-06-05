<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!User::where('email','anderson@hotmail.com')->first()){
            $adim = User::create([
               'name' => 'user teste',
               'email'=> 'anderson@hotmail.com',
               'password' => Hash::make('demo12345', ['rounds'=> 12]),
               'nivelUser' => 2, //adiministrador 
            ]);
        }if(!User::where('email','allyson.@hotmail.com')->first()){
            $adim = User::create([
               'name' => 'user teste3',
               'email'=> 'allyson.@hotmail.com',
               'password' => Hash::make('demo12345', ['rounds'=> 12]),
               'nivelUser' => 1, //adiministrador 
            ]);
        }if(!User::where('email','andre@hotmail.com')->first()){
            $adim = User::create([
               'name' => 'user teste2',
               'email'=> 'andre@hotmail.com',
               'password' => Hash::make('demo12345', ['rounds'=> 12]),
               'nivelUser' => 2, //adiministrador 
            ]);
        }
    }
}
