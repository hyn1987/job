<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('tickets', function(Blueprint $table) {
      /**
       * 이 표는 티케트 기본정보를 보관한다.
       * admin_id마당에는 티케트를 처리할 관리자가 할당된 경우에 값이 존재한다.
       * type마당은 티케트의 류형을 제시한다.
       * 0 - Notify
       * 1 - Normal
       * 2 - Dispute
       * 3 - Question
       * 4 - Suspension
       * 5 - Maintanance
       * 주의: type이 Notify인 경우 priority와 status마당은 무의미하다.
       * type이 Dispute인 경우 contract_id가 리용된다.
       * priority마당은 티케트의 중요도를 나타낸다.
       * 0 - Critical
       * 1 - High
       * 2 - Medium
       * 3 - Low
       * status마당은 티케트의 상태를 나타낸다.
       * 0 - Open
       * 1 - Assigned
       * 2 - Solved
       * 3 - Closed
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->string('subject', 255); // Subject
      $table->text('content')->nullable(); // Content
      $table->integer('user_id')->unsigned()->index(); // User id from users table
      $table->integer('admin_id')->unsigned()->index()->default(0); // Admin id from users table
      $table->integer('contract_id')->unsigned()->index()->default(0); // Contract id from users table
      $table->tinyInteger('type')->unsigned()->default(0); // Type
      $table->tinyInteger('priority')->unsigned()->default(0); // Priority
      $table->tinyInteger('status')->unsigned()->default(0); // Status
      $table->integer('attachment_id')->unsigned()->index()->default(0); // Attachment file id from uploads table
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
    Schema::dropIfExists('tickets');
  }
}