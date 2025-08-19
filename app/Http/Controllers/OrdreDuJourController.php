<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingSession;
use App\Models\OrdreDuJour;

class OrdreDuJourController extends Controller
{
  public function show(MeetingSession $session) {
    return $session->ordreDuJour()->with('points')->first();
  }

  public function store(MeetingSession $session) { // Secrétaire
    // autorisation selon Policy...
    $odj = $session->ordreDuJour;
    if ($odj) return $odj;
    return $session->ordreDuJour()->create(['version'=>1]);
  }


  public function valider(\App\Models\OrdreDuJour $odj, Request $r) { // SG / Présidente
    // authorize('validate', $odj)
    $odj->update(['date_validation'=>now(), 'valide_par'=>$r->user()->id]);
    return $odj->fresh();
  }
}

