<?php

namespace App\Http\Controllers;

use App\Enums\StatutSession;
use App\Http\Requests\StoreSessionRequest;
use App\Models\MeetingSession;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class SessionController extends Controller
{
    use AuthorizesRequests;
  public function index(Request $r) {
    $q = MeetingSession::query()->with('ordreDuJour');
    if ($r->filled('statut')) $q->where('statut', $r->statut);
    return $q->latest('date_session')->paginate(20);
  }

public function store(StoreSessionRequest $r) {
    $this->authorize('create', \App\Models\MeetingSession::class);

    $s = \App\Models\MeetingSession::create([
        'code'         => $r->code,                 // ou généré auto si tu veux
        'theme'        => $r->theme,
        'date_session' => $r->date_session,
        'statut'       => \App\Enums\StatutSession::Brouillon,
        'lieu'         => $r->lieu,
        'cree_par'     => $r->user()->id,
    ]);

    return response()->json(
        $s->refresh()->load(['ordreDuJour','rapports','decisions']),
        201
    );
}


  public function show(MeetingSession $session) {
    $this->authorize('view', $session);
    return $session->load(['ordreDuJour','rapports','decisions']);
  }

  public function planifier(MeetingSession $session) {
    $this->authorize('planifier', $session);
    if ($session->statut !== StatutSession::Brouillon) return abort(422,'Transition invalide');
    $session->update(['statut'=>StatutSession::Planifiee]);
    return $session;
  }

  public function demarrer(MeetingSession $session) {
    $this->authorize('demarrer', $session);
    if ($session->statut !== StatutSession::Planifiee) return abort(422,'Transition invalide');
    // Vérifier ODJ validé :
    if (!$session->ordreDuJour || !$session->ordreDuJour->date_validation) return abort(422, 'ODJ non validé');
    $session->update(['statut'=>StatutSession::EnCours]);
    return $session;
  }

  public function cloturer(MeetingSession $session) {
    $this->authorize('cloturer', $session);
    if ($session->statut !== StatutSession::EnCours) return abort(422,'Transition invalide');
    $session->update(['statut'=>StatutSession::Cloturee]);
    return $session;
  }
}

