<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectMessageUsersThreadsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('project_message_users_threads', function(Blueprint $table)
    {
      /**
       * 이 표는 사용자가 통보문을 삭제하거나 기타 다른 작업을 하였을때 리용된다.
       * status마당은 삭제 경우 1을 사용한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('user_id')->unsigned()->index(); // User id from users table
      $table->integer('thread_id')->unsigned()->index(); // Message thread id from project_message_threads table
      $table->tinyInteger('status')->unsigned()->default(1); // Status, 0 - None, 1 - Removed
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('project_message_users_threads');
  }

}
