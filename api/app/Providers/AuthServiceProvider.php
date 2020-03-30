<?php

namespace App\Providers;

use App\Models\OAuthClient;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Route;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Passport::routes();

        // replaced Passport:routes() with this method to have control over the middleware
        $this->mapOauthRoutes();

        Passport::useClientModel(OAuthClient::class);
    }

    protected function mapOauthRoutes()
    {
        Route::prefix('oauth')
            ->namespace('Laravel\Passport\Http\Controllers')
            ->group(base_path('routes/oauth.php'));
    }
}
