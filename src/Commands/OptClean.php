<?php


namespace Abdukhaligov\LaravelOTP\Commands;

use Abdukhaligov\LaravelOTP\Models\Otp;
use Illuminate\Console\Command;

class OptClean extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'otp:clean';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Clean Otp database, remove all old list that is expired or used.';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    try {
      $list = Otp::query()->where('valid', false)->orWhere('valid_until', '<', now())->count();
      $this->info("Found {$list} expired otps.");

      Otp::where('valid', false)->delete();
      $this->info("expired tokens deleted");
    } catch (\Exception $e) {
      $this->error("Error:: {$e->getMessage()}");
    }

    return 0;
  }
}
