<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
    $sg = User::create([
        'name'=>'SG Demo','email'=>'sg@cil.local','password'=>Hash::make('password'),
    ]); $sg->assignRole('SG');

    $pres = User::create([
        'name'=>'Présidente Demo','email'=>'pres@cil.local','password'=>Hash::make('password'),
    ]); $pres->assignRole('PRESIDENTE');

    $dir = User::create([
        'name'=>'Directeur Demo','email'=>'dir@cil.local','password'=>Hash::make('password'),
    ]); $dir->assignRole('DIRECTEUR');

    $ce = User::create([
        'name'=>'CE Demo','email'=>'ce@cil.local','password'=>Hash::make('password'),
    ]); $ce->assignRole('CE');

    $sec = User::create([
        'name'=>'Secrétaire Demo','email'=>'sec@cil.local','password'=>Hash::make('password'),
    ]); $sec->assignRole('SECRETAIRE');
}
}
