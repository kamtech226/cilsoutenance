<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Decision extends Model
{
    use HasFactory, HasUuids;

    // si ta table s'appelle bien "decisions", cette ligne est optionnelle
    protected $table = 'decisions';

    // 👉 autoriser les champs qu'on envoie depuis l'API
    protected $fillable = [
        'type',
        'contenu',
        'session_id',   // si ta table a cette colonne
        'point_id',     // sera rempli automatiquement si tu fais $point->decisions()->create()
        'cree_par',
    ];

    protected $casts = [
        'date_validation' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(MeetingSession::class, 'session_id');
    }

    public function point()
    {
        return $this->belongsTo(PointODJ::class, 'point_id');
    }
     public function auteur()  { return $this->belongsTo(User::class, 'cree_par'); }
}
