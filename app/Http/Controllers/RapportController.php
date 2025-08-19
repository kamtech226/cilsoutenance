<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Models\MeetingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Enums\StatutRapport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class RapportController extends Controller
{

use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

  public function index(Request $r, MeetingSession $session)
{
    $u = $r->user();

    $q = Rapport::where('session_id', $session->id)->latest();

    if ($u->hasRole('CE')) {
        // Le CE ne voit que ses propres rapports (y compris ses brouillons)
        $q->where('redige_par', $u->id);
    } elseif ($u->hasAnyRole(['SG','Presidente','Présidente'])) {
        // Le SG/Présidente voit uniquement ceux à traiter / déjà traités
        $q->whereIn('statut', [
            StatutRapport::Soumis,
            StatutRapport::Valide,
            StatutRapport::Publie,
            // (optionnel) StatutRapport::Rejete
        ]);
    } else {
        abort(403);
    }

    if ($r->filled('statut')) {
        $q->where('statut', $r->statut);
    }

    return $q->get();
}

    public function store(Request $request, MeetingSession $session)
    {
        // Autorisation simple: CE
        if (!$request->user()->hasRole('CE')) {
            abort(403);
        }

        $data = $request->validate([
            'titre'   => ['required','string','max:255'],
            'contenu' => ['nullable','string'],
            'pdf'     => ['nullable','file','mimes:pdf','max:15360'],
        ]);

        $fichier = $request->hasFile('pdf')
            ? $request->file('pdf')->store('rapports', 'public')
            : null;

        $rapport = Rapport::create([
            'session_id'  => $session->id,
            'titre'       => $data['titre'],
            'contenu'     => $data['contenu'] ?? null,
            'fichier_url' => $fichier,
            'redige_par'  => $request->user()->id,
            'statut'      => StatutRapport::Brouillon,
        ]);

        return response()->json([
            'id'         => $rapport->id,
            'titre'      => $rapport->titre,
            'contenu'    => $rapport->contenu,
            'fichierUrl' => $rapport->fichier_url ? Storage::disk('public')->url($rapport->fichier_url) : null,
            'statut'     => $rapport->statut,
            'created_at' => $rapport->created_at,
        ], 201);
    }
public function show(Rapport $rapport) {
    $this->authorize('view', $rapport);
    return $rapport;
}

    // 5.3 Soumettre (CE) – seulement rédacteur & statut Brouillon
    public function submit(Rapport $rapport, Request $request)
    {
        if (!$request->user()->hasRole('CE')) abort(403);
        if ($rapport->redige_par !== $request->user()->id) {
            abort(403, 'Seul le rédacteur peut soumettre ce rapport.');
        }
        if ($rapport->statut !== StatutRapport::Brouillon) {
            abort(422, 'Seul un brouillon peut être soumis.');
        }

        $rapport->update([
            'statut'     => StatutRapport::Soumis,
            'date_depot' => now(),
        ]);

        return $rapport->fresh();
    }

    // 5.4 Valider (SG/Présidente) – seulement si Soumis
    public function validateRapport(Rapport $rapport, Request $request)
    {
        if (!$request->user()->hasAnyRole(['SG','Presidente'])) abort(403);
        if ($rapport->statut !== StatutRapport::Soumis) {
            abort(422, 'Seul un rapport soumis peut être validé.');
        }

        $rapport->update([
            'statut'     => StatutRapport::Valide,
            'valide_par' => $request->user()->id,
        ]);

        return $rapport->fresh();
    }

    // 5.5 Publier (SG/Présidente) – seulement si Valide
    public function publish(Rapport $rapport, Request $request)
    {
        if (!$request->user()->hasAnyRole(['SG','Presidente'])) abort(403);
        if ($rapport->statut !== StatutRapport::Valide) {
            abort(422, 'Seul un rapport validé peut être publié.');
        }

        $rapport->update(['statut' => StatutRapport::Publie]);

        return $rapport->fresh();
    }
}
