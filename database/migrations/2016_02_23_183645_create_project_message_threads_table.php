<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectMessageThreadsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('project_message_threads', function(Blueprint $table)
    {
      /**
       * 이 표는 기본 통보문 스레드들을 보관한다. 통보문 스레드들에는 여러개의 통보문(project_messages표)
       * 들이 보관된다. 현재 통보문스레드의 삭제를 위해 project_message_user_threads표를 리용한다.
       * 프로젝트와 관련되는 통보문들에 대해서는 project_id마당을 리용한다.
       * Contract와 관렬되는 통보문들에 대해서는 contract_id마당을 리용한다.
       * 해당 스레드에 통보문이 추가될때마다 updated_at마당을 갱신해주어야 한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Message thread Id
      $table->string('subject', 255); // Message thread subject
      $table->integer('sender_id')->unsigned()->index(); // Sender id from users table
      $table->integer('receiver_id')->unsigned()->index(); // Receiver id from users table
      $table->integer('application_id')->unsigned()->index()->nullable(); // Applicant id from project_applicants table
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
    Schema::drop('project_message_threads');
  }

}
