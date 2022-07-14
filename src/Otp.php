<?php

namespace Abdukhaligov\LaravelOTP\Models;

use Abdukhaligov\LaravelOTP\OtpFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * @property string $id
 * @property string $token
 * @property string $clean_token Token without hashing (used only in factory)
 * @see OtpFactory::newToken()
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 */
class Otp extends Model
{
  use HasFactory;

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
    'identifier', 'token', 'valid_until'
  ];

  protected $casts = [
    'valid' => 'boolean'
  ];

  protected static function newFactory(): OtpFactory
  {
    return OtpFactory::new();
  }

  protected static function boot()
  {
    parent::boot();
    static::creating(function ($otp) {
      $otp->token = Hash::make($otp->token);
      unset($otp->clean_token);
    });
  }
}
