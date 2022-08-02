<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Laravel\Auth\BindFailureListener;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {


        $this->registerPolicies();

        Fortify::authenticateUsing(function ($request) {
            $validated = Auth::validate([
                'mail' => $request->email,
                'password' => $request->password,
                'fallback' => [
                    'email' => $request->email,
                    'password' => $request->password,
                ],
            ]);

            return $validated ? Auth::getLastAttempted() : null;
        });

        //
    }
}
