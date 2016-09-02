<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractFeedbacksTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('contract_feedbacks', function(Blueprint $table)
    {
      /**
       * 이 표는 contract에 대한 feedback정보를 보관한다.
       * freelancer_score_detail과 buyer_score_detail은 Json형식 문자렬이다.
       * 실례로 {"skill":5,"communication":5,"relability":5}
       * 이 마당들은 현재 리용하지 않으며 앞으로 확장성을 고려하여 설계하였다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('contract_id')->unsigned()->index(); // Contract id from contracts table
      $table->float('buyer_score')->nullable(); // Buyer score, ie: 4.85
      $table->string('buyer_score_detail', 1024)->nullable(); // Buyer score detail
      $table->string('buyer_feedback', 1024)->nullable(); // Buyer feedback
      $table->boolean('is_buyer_feedback_public')->default(1); // Buyer feedback private flag: 0 - Private, 1 - Public
      $table->tinyInteger('buyer_feedback_status')->unsigned()->default(0); // Buyer feedback status: 0 - Waiting, 1 - Given, 2 - Editable
      $table->float('freelancer_score')->nullable(); // Buyer score, ie: 4.85
      $table->string('freelancer_score_detail', 1024)->nullable(); // Buyer score detail
      $table->string('freelancer_feedback', 1024)->nullable(); // Buyer feedback
      $table->boolean('is_freelancer_feedback_public')->default(1); // Buyer feedback private flag: 0 - Private, 1 - Public
      $table->tinyInteger('freelancer_feedback_status')->unsigned()->default(0); // Buyer feedback status: 0 - Waiting, 1 - Given, 2 - Editable

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
    Schema::drop('contract_feedbacks');
  }

}
