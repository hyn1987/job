<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('files', function(Blueprint $table)
    {
      /**
       * This table keeps the information of files uploaded.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // File id
      $table->integer('user_id')->unsigned()->index(); // User id
      $table->string('name', 128); // File name
      $table->tinyInteger('type')->unsigned()->default(0); // File type: 0 - Unknown, 1 - Avatar, ...
      $table->string('ext', 5); // File extension
      $table->bigInteger('size'); // File size in byte
      $table->string('path', 255); // File path
      $table->timestamp('uploaded_at'); // Time uploaded
      $table->boolean('is_approved')->default(1); // Status: 0 - Not approved, 1 - Approved

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
    Schema::dropIfExists('files');
  }
}