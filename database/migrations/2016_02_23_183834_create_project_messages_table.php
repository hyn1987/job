<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectMessagesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('project_messages', function(Blueprint $table)
    {
      /**
       * 이 표는 프로젝트의 통보문들을 보관한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Message Id
      $table->integer('thread_id')->unsigned()->index()->nullable(); // Message thread id from project_message_threads table
      $table->integer('sender_id')->unsigned()->index(); // Sender id from users table
      $table->string('message', 4096); // Message
      $table->integer('file_id')->unsigned()->nullable(); // Attachment id from files table
      $table->timestamp('created_at'); // Time to be created
      $table->timestamp('received_at')->nullable(); // Time to be received
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('project_messages');
  }

}
