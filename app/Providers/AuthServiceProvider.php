<?php

namespace App\Providers;

use App\Models\Session;
use App\Policies\SessionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [

    \App\Models\MeetingSession::class => \App\Policies\MeetingSessionPolicy::class,
      \App\Models\PointODJ::class => \App\Policies\PointODJPolicy::class,
      \App\Models\Rapport::class => \App\Policies\RapportPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
