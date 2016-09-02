<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersRolesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users_roles', function(Blueprint $table)
    {
      $table->engine = 'InnoDB';

      $table->increments('id'); // User - Role pivot id
      $table->integer('user_id'); // User id
      $table->integer('role_id'); // Role id
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('users_roles');
  }
}