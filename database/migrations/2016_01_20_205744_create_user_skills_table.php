<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSkillsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_skills', function(Blueprint $table)
    {
      /**
       * This table keeps the skill items for users.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('user_id')->unsigned()->index(); // User id from users table
      $table->integer('skill_id')->unsigned()->index(); // Skill id from skills table
      $table->tinyInteger('priority')->unsigned()->default(0); // Skill order to show
      $table->tinyInteger('level')->unsigned()->default(0); // Skill level, range [0 ~ 10]
      $table->boolean('is_verified')->default(0); // Verification flag
      $table->boolean('is_active')->default(1); // Active flag

      $table->index(array('user_id', 'skill_id'));
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('user_skills');
  }
}