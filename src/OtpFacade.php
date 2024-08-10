<?php

namespace Abdukhaligov\LaravelOTP;

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
   * @param bool $onlyDigits
   * @return string
   */
  private static function generateRandomString(int $length, bool $onlyDigits): string
  {
    $pattern = $onlyDigits ? '0123456789' : 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    return substr(str_shuffle(str_repeat($pattern, $length)), 0, $length);
  }

  /**
   * @param string $identifier
   * @return Otp|null
   */
  private static function validOtp(string $identifier): ?Otp
  {
    return Otp::where('identifier', $identifier)
      ->where('valid', true)
      ->where('attempts', '>', 0)
      ->where('valid_until', '>', now())
      ->first();
  }

  private static function checkOtp(Otp $otp, string $token): bool
  {
    return $otp->valid && Hash::check($token, $otp->token);
  }

  /**
   * Generate and save a new OTP code for the given identifier and return it.
   *
   * @param string $identifier The identity that will be tied to the OTP.
   * @param int $digits The amount of digits to be generated.
   * @param int $validity The validity period of the OTP in minutes.
   * @param int $attempts The attempts the OTP
   * @param bool $onlyDigits Generate OTP only digits
   * @return string
   */
  public static function generate(string $identifier, int $digits = 6, int $validity = 10, int $attempts = 3, bool $onlyDigits = false): string
  {
    Otp::where('identifier', $identifier)->where('valid', true)->delete();

    $cleanToken = self::generateRandomString($digits, $onlyDigits);

    Otp::create([
      'identifier' => $identifier,
      'valid_until' => now()->addMinutes($validity),
      'attempts' => $attempts,
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
    if ($otp = self::validOtp($identifier)) {
      $otp->attempts = $otp->attempts - 1;
      $otp->save();
    } else {
      return false;
    }

    if (!self::checkOtp($otp, $token)) {
      return false;
    } else {
      $otp->valid = false;
      $otp->save();

      return true;
    }
  }

  /**
   * Need to make otp submitted, via link or something else
   *
   * @param string $identifier
   * @param string $token
   * @return bool
   */
  public static function submit(string $identifier, string $token): bool
  {
    if (!$otp = self::validOtp($identifier)) {
      return false;
    }

    if (!self::checkOtp($otp, $token)) {
      return false;
    } else {
      $otp->submitted = true;
      $otp->save();

      return true;
    }
  }

  public static function checkSubmitted(string $identifier): bool
  {
    if (($otp = self::validOtp($identifier)) && $otp->submitted) {
      $otp->valid = false;
      $otp->save();

      return true;
    } else {
      return false;
    }
  }
}
