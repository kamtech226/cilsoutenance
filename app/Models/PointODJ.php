<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Enums\StatutPoint;

class PointODJ extends Model {
  use HasUuids;

  
    protected $table = 'point_odjs';

  // Autoriser l’assignation de masse
    protected $fillable = ['titre','description','priorite','statut','propose_par'];

    protected $casts = [
        'priorite' => 'integer',
        'statut'   => StatutPoint::class, // si enum "backed" string
    ];

  public function odj(){ return $this->belongsTo(OrdreDuJour::class,'odj_id'); }
public function decisions()
{
    return $this->hasMany(\App\Models\Decision::class, 'point_id');
}

  public function pieces(){ return $this->hasMany(PieceJointe::class,'point_id'); }
  public function proposePar(){ return $this->belongsTo(User::class,'propose_par'); }
}
