<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimezonesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('timezones', function(Blueprint $table)
    {
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Auto Increment State Id
      $table->string('name', 30); // GMT offset Label
      $table->string('label', 80); // Timezone Label
      $table->float('gmt_offset'); // GMT offset in number

      // Soft Delete
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('timezones');
  }
}