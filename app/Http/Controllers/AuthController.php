<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function csrf() { return response()->noContent(); }

    public function login(Request $r){
        $r->validate(['email'=>'required|email','password'=>'required']);
        if (!auth('web')->attempt($r->only('email','password'), true)) {
            return response()->json(['message'=>'Identifiants invalides'], 422);
        }
        $r->session()->regenerate();

        // optionnel: renvoyer aussi l'utilisateur
        return response()->json(['message'=>'ok']);
    }

public function me(\Illuminate\Http\Request $r)
{
    $u = $r->user(); // via auth:sanctum
    if (!$u) return response()->json(['message' => 'Unauthenticated'], 401);

    $roles = method_exists($u,'getRoleNames') ? $u->getRoleNames()->values() : [];
    $perms = method_exists($u,'getAllPermissions') ? $u->getAllPermissions()->pluck('name')->values() : [];

    return response()->json([
        'user' => ['id'=>$u->id, 'name'=>$u->name, 'email'=>$u->email],
        'roles' => $roles,
        'permissions' => $perms,
    ]);
}


    public function logout(Request $r){
        auth()->guard('web')->logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return response()->json(['message'=>'bye']);
    }
}
