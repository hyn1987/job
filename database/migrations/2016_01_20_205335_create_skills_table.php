<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('skills', function(Blueprint $table)
    {
      /**
       * 이표는 skill정보를 보관한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Skill id
      $table->string('name', 64); // Skill name
      $table->string('desc', 1024)->nullable(); // Skill description

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
    Schema::drop('skills');
  }

}
