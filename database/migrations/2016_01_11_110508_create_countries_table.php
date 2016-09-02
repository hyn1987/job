<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('countries', function(Blueprint $table)
    {
      // DB Engine
      $table->engine = 'InnoDB';

      /* Should be following ISO 3166-1 and 3166-2 */
      $table->increments('id'); // Auto Increment Country Id
      $table->string('charcode', 2)->unique(); // Country 2 characters code, from ISO 3166-1
      $table->string('charcode3', 3); // 3 characters code
      $table->string('numcode', 3); // 3 number code
      $table->string('name', 64)->unique(); // Country name in English
      $table->string('country_code', 10); // Country Code (Phone Prefix)

      // Soft Delete
      $table->softDeletes();

      // Covering Index
      $table->index(array('charcode', 'name'));
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('countries');
  }
}