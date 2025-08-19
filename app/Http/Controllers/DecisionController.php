<?php

namespace App\Http\Controllers;

use App\Models\MeetingSession;
use Illuminate\Http\Request;
use App\Models\PointODJ;
use App\Models\MeetingSessionSession;
use App\Http\Requests\StoreDecisionRequest;

class DecisionController extends Controller
{
 public function storeForPoint(StoreDecisionRequest $r, PointODJ $point)
    {
        // autorisation (à adapter à tes rôles/policies)
        if (! $r->user()->hasAnyRole(['SG','Présidente','Presidente'])) {
            abort(403, 'Unauthorized');
        }

        $decision = $point->decisions()->create([
            'type'       => $r->type,
            'contenu'    => $r->contenu,
            'session_id' => $point->session_id ?? null, // si ta table le demande
            'cree_par'   => $r->user()->id,
        ]);

        return response()->json($decision, 201);
    }
  public function storeForSession(MeetingSession $session, Request $r){
    $data = $r->validate(['type'=>'required','contenu'=>'required|string']);
    return $session->decisions()->create($data);
  }
}
