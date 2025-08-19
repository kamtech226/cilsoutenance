<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// GET "dummy" pour les redirections du middleware 'auth' (répond en JSON)
Route::get('/login', function () {
    return response()->json(['message' => 'Utilise POST /login pour te connecter.'], 405);
})->name('login'); // important : nom utilisé par le middleware 'auth'

// Connexion / Déconnexion (web, sessions)
Route::post('/login',  [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
