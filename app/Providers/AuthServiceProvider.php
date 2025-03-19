<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        Gate::define('manage_quote',function(User $user,Quote $quote){
            return $user->id == $quote->user_id || $user->role === 'admin';
        });
    }
}
