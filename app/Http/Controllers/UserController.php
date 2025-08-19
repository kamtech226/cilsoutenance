<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ----- Admin (SG/Présidente/Admin) -----

    public function index(Request $r) {
        return User::query()
            ->when($r->filled('role'), fn($q)=>$q->role($r->role)) // si tu utilises le scope Spatie
            ->latest()->paginate(20);
    }

public function store(Request $r)
{
    $data = $r->validate([
        'name'      => ['required','string','max:255'],
        'email'     => ['required','email','max:255','unique:users,email'],
        'roles'     => ['sometimes','array'],
        'roles.*'   => ['string','exists:roles,name'],
        'password'  => ['nullable','string','min:8'],
    ]);

    $plain = $data['password'] ?? Str::password(12); // ou Str::random(12)

    $user = \App\Models\User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => Hash::make($plain),
    ]);

    if (!empty($data['roles'])) {
        $user->syncRoles($data['roles']);
    }

    return response()->json([
        'id'     => $user->id,
        'name'   => $user->name,
        'email'  => $user->email,
        'roles'  => $user->getRoleNames(),
        // En dev tu peux renvoyer le MDP généré. En prod -> envoi par mail/SMS.
        'temp_password' => app()->environment('local') ? $plain : null,
    ], 201);
}


public function update(Request $r, \App\Models\User $user)
{
    $data = $r->validate([
        'name'      => ['sometimes','string','max:255'],
        'email'     => ['sometimes','email','max:255','unique:users,email,'.$user->id],
        'roles'     => ['sometimes','array'],
        'roles.*'   => ['string','exists:roles,name'],
        'password'  => ['sometimes','string','min:8'],
    ]);

    // password présent -> on hash
    if (array_key_exists('password', $data)) {
        $user->password = Hash::make($data['password']);
        unset($data['password']);
    }

    // name/email
    $user->fill(array_intersect_key($data, array_flip(['name','email'])));
    $user->save();

    // roles
    if (array_key_exists('roles', $data)) {
        $user->syncRoles($data['roles']);
    }

    return $user->only('id','name','email');
}


    public function destroy(User $user) {
        $user->delete();
        return response()->noContent();
    }

    // ----- Mon profil (tout utilisateur connecté) -----

    public function updateMe(Request $r) {
        $data = $r->validate([
            'name'  => ['sometimes','string','max:255'],
            'email' => ['sometimes','email','max:255','unique:users,email,'.$r->user()->id],
        ]);
        $r->user()->update($data);
        return ['user'=>$r->user()->only('id','name','email')];
    }

    public function changePassword(Request $r) {
        $r->validate([
            'current_password' => ['required'],
            'password'         => ['required','min:8','confirmed'], // + champ password_confirmation
        ]);

        if (! Hash::check($r->current_password, $r->user()->password)) {
            return response()->json(['message'=>'Mot de passe actuel incorrect.'], 422);
        }

        $r->user()->forceFill(['password'=>Hash::make($r->password)])->save();
        $r->session()->regenerate(); // sécurité session

        return ['message'=>'Mot de passe mis à jour.'];
    }
}
