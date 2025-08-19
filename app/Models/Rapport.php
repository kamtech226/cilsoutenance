<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Enums\StatutRapport;

class Rapport extends Model
{
    use HasUuids;

    protected $fillable = [
        'session_id','titre','contenu','fichier_url','statut','redige_par','valide_par'
    ];

    protected $casts = [
        'date_depot' => 'datetime',
        'statut'     => StatutRapport::class,
    ];

    // 👉 Corrige le nom du modèle de session
    public function session()
    {
        return $this->belongsTo(MeetingSession::class, 'session_id');
    }

    public function redacteur()
    {
        return $this->belongsTo(User::class, 'redige_par');
    }

    public function valideur()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }
}
