<?php

namespace App\Providers;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Passport::loadKeysFrom(storage_path('oauth'));
        Passport::personalAccessTokensExpireIn(CarbonInterval::minutes(30));
        Passport::refreshTokensExpireIn(CarbonInterval::days(30));
        Passport::enablePasswordGrant();
    }
}
