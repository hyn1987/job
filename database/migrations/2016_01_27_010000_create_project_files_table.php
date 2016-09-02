<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectFilesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('project_files', function (Blueprint $table) {
      /**
       * This table keeps the skill list for projects.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('project_id')->unsigned()->index(); // Project id from projects table
      $table->integer('file_id')->unsigned()->index(); // File id from files table
      $table->boolean('is_active')->default(0); // Active flag: 0 - inactive, 1 - active

      $table->index(array('project_id', 'file_id'));
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('project_files');
  }
}