<?php
// app/Models/OrdreDuJour.php
namespace App\Models;

 use App\Models\MeetingSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrdreDuJour extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'ordre_du_jours';

    // Option A (recommandé) : lister les champs autorisés
    protected $fillable = [
        'session_id', 'version', 'cree_par',
        'valide_par', 'date_validation', 'commentaire',
    ];

    // Option B (rapide, moins strict)
    // protected $guarded = [];
public function points()
{
    // Doit matcher la colonne FK dans la table des points
    return $this->hasMany(PointODJ::class, 'odj_id');
}

    public function session()
    {
        return $this->belongsTo(MeetingSession::class, 'session_id');
    }
}
