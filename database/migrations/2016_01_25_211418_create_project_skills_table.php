<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectSkillsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('project_skills', function (Blueprint $table) {
      /**
       * 이 표는 과제와 관련되는 skill정보들을 보관한다.
       * projects표와 1:n관계에 있다.
       * order마당은 asc순위로 수값으로 입력한다.
       * level마당은 skill준위이며 0 ~ 10사이의 옹근수로 입력한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('project_id')->unsigned()->index(); // Project id from projects table
      $table->integer('skill_id')->unsigned()->index(); // Skill id from skills table
      $table->tinyInteger('order')->unsigned()->default(0); // Skill order to show
      $table->tinyInteger('level')->unsigned()->default(0); // Skill level, range [0 ~ 10]

      $table->index(array('project_id', 'skill_id'));
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('project_skills');
  }
}