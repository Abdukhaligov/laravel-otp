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
    $this->app->bind('otp', OtpFacade::class);

    /*
     * Create aliases for the dependency.
     */
    $loader = \Illuminate\Foundation\AliasLoader::getInstance();
    $loader->alias('Otp', OtpFacade::class);
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    if ($this->shouldMigrate()) {
      $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    $this->commands([
      OptClean::class,
    ]);
  }

  /**
   * Determine if we should register the migrations.
   *
   * @return bool
   */
  protected function shouldMigrate(): bool
  {
    return Otp::$runsMigrations;
  }
}
