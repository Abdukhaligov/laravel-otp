<?php

namespace Abdukhaligov\LaravelOTP;

use Abdukhaligov\LaravelOTP\Commands\OptClean;
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

    /*
     * Create aliases for the dependency.
     */
    $loader = \Illuminate\Foundation\AliasLoader::getInstance();
    $loader->alias('Otp', Otp::class);
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
