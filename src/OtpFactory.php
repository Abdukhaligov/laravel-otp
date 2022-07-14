<?php

namespace Abdukhaligov\LaravelOTP;

use Abdukhaligov\LaravelOTP\Models\Otp;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Otp>
 */
class OtpFactory extends Factory
{
  protected $model = Otp::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      //
    ];
  }


  public function newToken($digits): self
  {
    return $this->state(function () use ($digits) {
      return [
        'clean_token' => $this->faker->regexify('[A-Z0-9]{' . $digits . '}')
      ];
    });
  }
}
