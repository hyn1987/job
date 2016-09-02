<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersLanguagesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users_languages', function(Blueprint $table)
    {
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('user_id'); // User id from users table
      $table->integer('lang_id'); // Language id from languages table

      $table->unique(array('user_id', 'lang_id'));

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
    Schema::drop('users_languages');
  }

}
