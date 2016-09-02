<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('projects', function(Blueprint $table)
    {
      /**
       * 이표는 프로젝트를 위한 기본 표이다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Project id
      $table->integer('category_id')->unsigned()->index(); // Project category id from categories table
      $table->integer('client_id')->unsigned()->index(); // Client id from users table
      $table->string('subject', 255); // Project subject
      $table->text('desc'); // Project description
      $table->tinyInteger('type')->unsigned()->default(0); // Project type, 0 - Fixed, 1 - Hourly
      $table->string('duration', 10); // Project duration for hourly project
      $table->string('workload', 10); // Project workload for hourly project
      $table->decimal('price', 20, 2); // Project max price or rate
      $table->boolean('req_cv')->default(1); // Flag if resume need or not
      $table->integer('contract_limit')->unsigned()->default(0); // Limitation to hire, 0 - No limit
      $table->boolean('is_public')->unsigned()->default(1); // Public flag: 0 - Private, 1 - Public
      $table->tinyInteger('status')->unsigned()->default(0); // Project Status, 0 - Open, 1 - Closed
      $table->string('reason', 256); // Project status reason, ie, when the project is closed, then leave msg here.
      $table->timestamps();

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
    Schema::drop('projects');
  }
}