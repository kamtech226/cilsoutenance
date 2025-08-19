<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\OrdreDuJourController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\DecisionController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\PieceJointeController;

// ===== Bindings explicites (UUID) =====
use App\Models\MeetingSession;
use App\Models\OrdreDuJour;
use App\Models\PointODJ;
use App\Http\Controllers\UserController;



// patterns UUID (optionnel mais pratique)
Route::pattern('session', '[0-9a-fA-F-]+');
Route::pattern('odj', '[0-9a-fA-F-]+');
Route::pattern('point', '[0-9a-fA-F-]+');

// bindings par nom de paramètre
Route::model('session', MeetingSession::class);
Route::model('odj', OrdreDuJour::class);
Route::model('point', PointODJ::class);

// ===== Auth libre =====
Route::post('/login',  [AuthController::class, 'login']);

// ===== Routes protégées =====
Route::middleware('auth:sanctum')->group(function () {

     Route::patch('/me', [UserController::class,'updateMe']);                // changer nom/email
    Route::patch('/me/password', [UserController::class,'changePassword']); // changer son mot de passe

    // Administration des comptes (réservé SG/Présidente/Admin)
    Route::middleware('role:SG|Presidente|Admin')->group(function () {
        Route::get('/users', [UserController::class,'index']);
        Route::post('/users', [UserController::class,'store']);             // créer un compte
        Route::patch('/users/{user}', [UserController::class,'update']);    // modifier (rôles, email…)
        Route::delete('/users/{user}', [UserController::class,'destroy']);  // désactiver/supprimer
    });

    // Profil
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Sessions (MeetingSession) ---
    Route::get('/sessions', [SessionController::class,'index']);
    Route::post('/sessions', [SessionController::class,'store']);                           // SG
    Route::get('/sessions/{session}', [SessionController::class,'show']);                  // SG/CE
    Route::post('/sessions/{session}/planifier', [SessionController::class,'planifier']);  // SG
    Route::post('/sessions/{session}/demarrer',  [SessionController::class,'demarrer']);   // SG
    Route::post('/sessions/{session}/cloturer',  [SessionController::class,'cloturer']);   // SG

    // --- Ordre du jour ---
    Route::get('/sessions/{session}/odj',  [OrdreDuJourController::class,'show']);         // SG/CE
    Route::post('/sessions/{session}/odj', [OrdreDuJourController::class,'store']);        // SG
    Route::post('/odj/{odj}/valider',      [OrdreDuJourController::class,'valider']);      // SG/Président

    // --- Points ODJ ---
    Route::get('/odj/{odj}/points',   [PointController::class,'index']);                   // SG/CE
    Route::post('/odj/{odj}/points',  [PointController::class,'store']);                   // CE/Directeur
    Route::post('/points/{point}/retenir',  [PointController::class,'retenir']);           // SG/Président
    Route::post('/points/{point}/ajourner', [PointController::class,'ajourner']);          // SG/Président
    Route::post('/points/{point}/rejeter',  [PointController::class,'rejeter']);           // SG/Président
    Route::post('/points/{point}/traite',   [PointController::class,'marquerTraite']);     // SG/Président (si décisions)

    // --- Décisions ---
    Route::post('/points/{point}/decisions',    [DecisionController::class,'storeForPoint']);
    Route::post('/sessions/{session}/decisions',[DecisionController::class,'storeForSession']);

    // --- Rapports ---
    Route::get('/sessions/{session}/rapports', [RapportController::class,'index']);
    Route::post('/sessions/{session}/rapports',[RapportController::class,'store']);
    Route::post('/rapports/{rapport}/submit',  [RapportController::class,'submit']);
    Route::post('/rapports/{rapport}/validate',[RapportController::class,'validateRapport']);
    Route::post('/rapports/{rapport}/publish', [RapportController::class,'publish']);

    // --- Pièces jointes ---
    Route::post('/points/{point}/pieces', [PieceJointeController::class,'store']);
});