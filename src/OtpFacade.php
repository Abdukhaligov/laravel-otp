<?php

namespace Abdukhaligov\LaravelOTP;

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
   * Generate a random string of given length.
   *
   * @param int $length
   * @return string
   */
  private static function generateRandomString(int $length): string
  {
    return substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', $length)), 0, $length);
  }

  /**
   * Generate and save a new OTP code for the given identifier and return it.
   *
   * @param string $identifier The identity that will be tied to the OTP.
   * @param int $digits The amount of digits to be generated.
   * @param int $validity The validity period of the OTP in minutes.
   * @return string
   */
  public static function generate(string $identifier, int $digits = 6, int $validity = 10): string
  {
    Otp::where('identifier', $identifier)->where('valid', true)->delete();

    $cleanToken = self::generateRandomString($digits);

    Otp::create([
      'identifier' => $identifier,
      'valid_until' => now()->addMinutes($validity),
      'token' => Hash::make($cleanToken),
      'valid' => true
    ]);

    return $cleanToken;
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
    $otp = Otp::where('identifier', $identifier)
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
