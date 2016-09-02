<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('contracts', function(Blueprint $table)
    {
      /**
       * 이 표는 contract과 관련한 모든 정보를 보관한다.
       * 모든 contract들은 프로젝트로부터 시작되며 status마당을 리용하여 과제offer를 제공할수 있다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Contract Id
      $table->string('title', 255); // Contract title
      $table->integer('buyer_id')->unsigned()->index(); // Buyer id from users table
      $table->integer('contractor_id')->unsigned()->index(); // Contractor id from users table
      $table->integer('project_id')->unsigned()->index(); // Project id from projects table
      $table->integer('application_id')->unsigned()->index(); // Application id from project_applications table
      $table->tinyInteger('type')->default(0); // Hourly flag, 0 - Fixed, 1 - Hourly
      $table->decimal('price', 20, 2); // Price or Rate(hourly) not including fee for site
      $table->smallInteger('limit')->unsigned()->default(0); // Limitation, in hours, 0 - No limit
      $table->boolean('is_allowed_manual_time')->default(1); // Flag for adding manual time
      $table->smallInteger('status')->unsigned()->default(0); // Status, 0 - Offer, 1 - Started, 2 - Paused, 3 - Suspended, 4 - Finished(wait for feedback), 5 - Completed, 6 - Offer Declined
      $table->timestamp('created_at')->nullable();
      $table->timestamp('updated_at')->nullable();
      $table->timestamp('started_at')->nullable(); // Started at
      $table->timestamp('ended_at')->nullable(); // Ended at
      $table->boolean('closed_by')->default(0); // Closed by: 0 - Buyer, 1 - Freelancer
      $table->string('close_reason', 512)->nullable(); // Close reason

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
    Schema::drop('contracts');
  }

}
