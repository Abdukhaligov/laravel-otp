<?php

namespace Abdukhaligov\LaravelOTP;

use Abdukhaligov\LaravelOTP\Models\Otp as Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Hash;

class OtpFacade extends Facade
{
  /**
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'otp';
  }

  /**
   * Generate/save a new OTP code for the given identifier and return it.
   *
   * @param string $identifier The identity that will be tied to the OTP.
   * @param int $digits The amount of digits to be generated.
   * @param int $validity The validity period of the OTP in minutes.
   * @return string
   */
  public static function generate(string $identifier, int $digits = 6, int $validity = 10): string
  {
    Model::where('identifier', $identifier)->where('valid', true)->delete();

    /** @var Model|Builder $otp */
    $otp = Model::factory([
      'identifier' => $identifier,
      'valid_until' => Carbon::now()->addMinutes($validity),
      'valid' => true
    ])->newToken($digits)->make();

    $otp->create(array_merge(['token' => $otp->clean_token], $otp->attributesToArray()));

    return $otp->clean_token;
  }

  /**
   * Validate an OTP code for the given identifier.
   *
   * @param string $identifier The identity that will be tied to the OTP.
   * @param string $token The OTP code to be validated.
   * @return bool
   */
  public static function validate(string $identifier, string $token): bool
  {
    $otp = Model::where('identifier', $identifier)
      ->where('valid', true)
      ->where('valid_until', '>', now())
      ->first();

    if ($otp == null || !$otp->valid || !Hash::check($token, $otp->token)) {
      return false;
    }

    $otp->valid = false;
    $otp->save();

    return true;
  }
}
