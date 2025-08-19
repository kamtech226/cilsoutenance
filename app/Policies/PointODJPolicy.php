<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PointODJ;
use App\Models\OrdreDuJour;
use Illuminate\Auth\Access\HandlesAuthorization;

class PointODJPolicy
{
    use HandlesAuthorization;

    // Créer un point : CE/Directeur et ODJ non validé
    public function create(User $user, \App\Models\OrdreDuJour $odj): bool
    {
      //  if (!is_null($odj->date_validation)) return false;

        return $user->hasAnyRole(['CE', 'Directeur', 'DirecteurCE', 'Directeur/CE']);
    }

      public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['CE','Directeur','SG','Admin']);
    }

   // --- Actions SG/Présidente sur les points ---
    protected function isSG(User $user): bool
    {
        // adapte si ton rôle s'appelle "Presidente" sans accent
        return $user->hasRole('SG') || $user->hasRole('Présidente') || $user->hasRole('Presidente');
    }

    public function retenir(User $user, PointODJ $point): bool
    {
        return $this->isSG($user);
    }

    public function ajourner(User $user, PointODJ $point): bool
    {
        return $this->isSG($user);
    }

    public function rejeter(User $user, PointODJ $point): bool
    {
        return $this->isSG($user);
    }

    public function marquerTraite(User $user, PointODJ $point): bool
    {
        return $this->isSG($user);
    }

}
