<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('otps', function (Blueprint $table) {
      $table->increments('id')->index();
      $table->string('identifier');
      $table->string('token');
      $table->tinyInteger('attempts')->default(0);
      $table->dateTime('valid_until');
      $table->boolean('valid')->default(true);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('otps');
  }
};
