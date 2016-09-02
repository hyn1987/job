<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('roles', function(Blueprint $table)
    {
      $table->engine = 'InnoDB';

      $table->increments('id'); // Role id
      $table->string('name', 64); // Role name
      $table->string('slug', 64)->nullable(); // Role code
      $table->string('desc', 128)->nullable(); // Role description
      $table->integer('parent_id')->unsigned()->index()->nullable(); // Parent role id
      $table->boolean('is_active')->default(1); // Role status: 0 - inactive, 1 - active

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
    Schema::dropIfExists('roles');
  }
}