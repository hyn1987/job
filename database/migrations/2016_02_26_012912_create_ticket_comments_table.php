<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketCommentsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('ticket_comments', function (Blueprint $table) {
      /**
       * 이 표는 티케트를 중심으로 오가는 통보문들을 보관한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('ticket_id')->unsigned()->index(); // Ticket id from tickets table
      $table->integer('commentor_id')->unsigned()->index(); // Commentor id from users table
      $table->text('comment'); // Comment
      $table->integer('attachment_id')->unsigned()->index()->default(0); // Attachment file id from uploads table
      $table->timestamp('created_at'); // Time ticket has been created
      $table->timestamp('updated_at'); // Time ticket has been updated

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
    Schema::dropIfExists('ticket_comments');
  }
}