<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdreDuJour;
use App\Http\Requests\StorePointRequest;
use App\Enums\StatutPoint;
use App\Models\PointODJ;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class PointController extends Controller
{
    use AuthorizesRequests;

 public function index(\App\Models\OrdreDuJour $odj){
    // Optionnel: contrôler l'accès à la liste
    $this->authorize('viewAny', [PointODJ::class, $odj]);

    return $odj->points()->latest()->get();
}

public function store(\App\Http\Requests\StorePointRequest $r, \App\Models\OrdreDuJour $odj)
{
    // Si tu veux forcer la policy en plus du FormRequest (propre) :
    $this->authorize('create', [\App\Models\PointODJ::class, $odj]);

    return $odj->points()->create([
        'titre'       => $r->titre,
        'description' => $r->description,
        'priorite'    => $r->priorite ?? 3,
        'statut'      => \App\Enums\StatutPoint::Propose,
        'propose_par' => $r->user()->id,
    ]);
}

 public function retenir(Request $r, PointODJ $point)
    {
        $this->authorize('retenir', $point);

        if (!in_array($point->statut, [StatutPoint::Propose, StatutPoint::Ajourne], true)) {
            abort(422, 'Transition invalide');
        }

        $point->update(['statut' => StatutPoint::Retenu]);

        return $point->fresh();
    }

    public function ajourner(Request $r, PointODJ $point)
    {
        $this->authorize('ajourner', $point);

        if ($point->statut !== StatutPoint::Propose) {
            abort(422, 'Transition invalide');
        }

        $point->update(['statut' => StatutPoint::Ajourne]);

        return $point->fresh();
    }

    public function rejeter(Request $r, PointODJ $point)
    {
        $this->authorize('rejeter', $point);

        if ($point->statut !== StatutPoint::Propose) {
            abort(422, 'Transition invalide');
        }

        $point->update(['statut' => StatutPoint::Rejete]);

        return $point->fresh();
    }

    public function marquerTraite(Request $r, PointODJ $point)
    {
        $this->authorize('marquerTraite', $point);

        if ($point->statut !== StatutPoint::Retenu) {
            abort(422, 'Transition invalide');
        }

        // Si tu as la relation decisions() sur PointODJ, garde ce check :
        if (method_exists($point, 'decisions') && !$point->decisions()->exists()) {
            abort(422, 'Au moins une décision est requise pour marquer "Traité".');
        }

        $point->update(['statut' => StatutPoint::Traite]);

        return $point->fresh();
    }

}
