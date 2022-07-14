<?php

namespace Abdukhaligov\LaravelOTP;

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
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
  }
}
