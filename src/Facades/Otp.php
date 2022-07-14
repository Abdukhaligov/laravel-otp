<?php

namespace Abdukhaligov\LaravelOTP\Facades;

use Abdukhaligov\LaravelOTP\Models\Otp as Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;

class Otp extends Facade
{
  /**
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'Otp';
  }

  /**
   * @param string $identifier
   * @param int $digits
   * @param int $validity
   * @return mixed
   */
  public static function generate(string $identifier, int $digits = 6, int $validity = 10)
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
}
