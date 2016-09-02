<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserNotificationsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_notifications', function(Blueprint $table)
    {
      /**
       * 이 표는 할당된 통보문들을 보관한다.
       * 행수가 많아지는 조건에서 soft delete기능을 사용하지 않으며
       * 3달지난 통보문들을 자동적으로 삭제하는것으로 한다. (cronjob)
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('notification_id')->unsigned()->index(); // Notification id from notifications table
      $table->string('notification', 1024); // Notification
      $table->integer('sender_id')->unsigned()->index(); // Sender id from users table
      $table->integer('receiver_id')->unsigned()->index(); // Receiver id from users table
      $table->timestamp('notified_at')->nullable();
      $table->timestamp('read_at')->nullable();
      $table->timestamp('valid_date')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('user_notifications');
  }

}
