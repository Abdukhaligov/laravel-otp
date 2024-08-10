<?php

namespace Abdukhaligov\LaravelOTP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $token
 * @property string $attempts
 * @property string $clean_token Token without hashing (used only in factory)
 * @see OtpFactory::newToken()
 * @property bool $active
 * @property bool $valid
 * @property bool $submitted
 * @property \Illuminate\Support\Carbon|null $created_at'
 */
class Otp extends Model
{
  use HasFactory;

  /**
   * Indicates if migrations will be run.
   *
   * @var bool
   */
  public static bool $runsMigrations = true;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'otps';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'identifier', 'token', 'valid_until', 'attempts'
  ];

  protected $casts = [
    'valid' => 'boolean'
  ];

  /**
   * Configure to not register its migrations.
   *
   * @return static
   */
  public static function ignoreMigrations(): static
  {
    static::$runsMigrations = false;

    return new static;
  }
}
