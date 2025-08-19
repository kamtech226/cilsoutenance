<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MeetingSession extends Model
{
    use HasFactory, HasUuids;

    // IMPORTANT : pointer la table METIER, pas la table Laravel "sessions"
    protected $table = 'training_sessions';

    protected $fillable = [
        'code','theme','date_session','statut','lieu','cree_par',
    ];

    protected $casts = [
        'date_session' => 'date',
         'statut'       => \App\Enums\StatutSession::class, // ou: 'statut' => \App\Enums\StatutSession::class,
    ];

    public function ordreDuJour()
    {
        return $this->hasOne(\App\Models\OrdreDuJour::class, 'session_id');
    }

    public function rapports()
    {
        return $this->hasMany(\App\Models\Rapport::class, 'session_id');
    }

    public function decisions()
    {
        return $this->hasMany(Decision::class, 'session_id');
    }
}
