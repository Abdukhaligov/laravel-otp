<?php

namespace Abdukhaligov\LaravelOTP;

use Abdukhaligov\LaravelOTP\Commands\OptClean;
use Abdukhaligov\LaravelOTP\Facades\Otp;
use Illuminate\Support\ServiceProvider;

class OtpServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bind('otp', Otp::class);
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

    $this->commands([
      OptClean::class,
    ]);
  }
}
