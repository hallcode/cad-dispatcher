<?php

namespace App\Providers;

use App\Incident;
use App\Policies\IncidentPolicy;
use App\Network;
use App\Policies\NetworkPolicy;
use App\Upload;
use App\Policies\UploadPolicy;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Incident::class => IncidentPolicy::class,
        Network::class => NetworkPolicy::class,
        Upload::class => UploadPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
    }
}
