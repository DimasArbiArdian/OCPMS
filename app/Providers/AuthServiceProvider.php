<?php

namespace App\Providers;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
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
        Gate::define('access-dashboard', fn (User $user): bool => in_array($user->role, ['admin', 'staff']));

        Gate::define('manage-candidates', fn (User $user): bool => in_array($user->role, ['admin', 'staff']));

        Gate::define('view-candidate', function (User $user, Candidate $candidate): bool {
            return in_array($user->role, ['admin', 'staff']) || $candidate->user_id === $user->id;
        });
    }
}
