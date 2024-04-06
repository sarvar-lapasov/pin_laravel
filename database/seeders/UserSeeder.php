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
        $admin = User::create([
            'name'=> 'Milena',
            'email' => 'molly@mail.ru',
             'password' => Hash::make('secret'),
         ]);
 
        $admin->roles()->attach(1);
        
        $user = User::create([
            'name'=> 'Billy',
            'email' => 'mike@mail.ru',
             'password' => Hash::make('12345678'),
         ]);
 
        $user->roles()->attach(2);
    }
}
