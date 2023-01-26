<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
            'name' => 'John',
            'email' => 'john@gmail.com',
            'password' => Hash::make('12345678'),
            ],
            [
            'name' => 'Doe',
            'email' => 'doe@gmail.com',
            'password' => Hash::make('12345678'),
            ]
        ];

        foreach($users as $user){
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password']
            ]);
        }
    }
}
