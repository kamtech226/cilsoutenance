<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MeetingSession;

class MeetingSessionPolicy
{
    /**
     * ⚠️ Pour une autorisation "sur la classe" (create),
     * la méthode NE PREND QU’UN SEUL paramètre: User $user.
     */
    public function create(User $user): bool
    {
        // Adapte si ton seeder a d’autres libellés; ici on autorise le SG
        return $user->hasAnyRole(['SG', 'Secrétaire Général', 'Secretaire General']);
    }

    public function view(User $user, MeetingSession $session): bool
    {
        // Lisser large pour l’instant
        return true;
    }

    public function planifier(User $user, MeetingSession $session): bool
    {
        return $user->hasAnyRole(['SG','Présidente','Presidente']);
    }

    public function demarrer(User $user, MeetingSession $session): bool
    {
        return $user->hasAnyRole(['SG','Présidente','Presidente']);
    }

    public function cloturer(User $user, MeetingSession $session): bool
    {
        return $user->hasAnyRole(['SG','Présidente','Presidente']);
    }
}
