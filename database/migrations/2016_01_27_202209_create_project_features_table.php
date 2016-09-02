<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectFeaturesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('project_features', function(Blueprint $table)
    {
      /**
       * This table keeps the rest information for projects, it will be used with projects table together.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('project_id')->unsigned()->index(); // Project id from projects table
      $table->tinyInteger('freelancer_type')->unsigned()->default(0); // Freelancer type, 0 - Any, 1 - Independent, 2 - Agency( not for now)
      $table->string('feedback_score')->nullable(); // Feedback score, 0 - Any
      $table->integer('hours_billed')->unsigned()->default(0); // Hours billed, 0 - Any
      $table->string('location', 256)->nullable(); // Location
      $table->tinyInteger('en_level')->unsigned()->default(0); // English level, range [0, 5]

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
    Schema::drop('project_features');
  }

}
