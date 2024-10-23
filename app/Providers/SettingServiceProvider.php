<?php

namespace App\Providers;

use App\Models\Kitchen;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
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
        Kitchen::firstOr(function () {
            return Kitchen::create([
              'name' => 'Alkaram',
            ]);
          });
    }
}
