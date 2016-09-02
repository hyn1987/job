<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('languages', function(Blueprint $table)
    {
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->string('name', 64); // Language name
      $table->string('code', 2); // Language code
      $table->string('country', 2); // 2 char country code

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
    Schema::drop('languages');
  }
}