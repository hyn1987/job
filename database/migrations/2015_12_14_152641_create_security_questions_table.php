<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecurityQuestionsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('security_questions', function(Blueprint $table)
    {
      $table->engine = 'InnoDB';

      $table->increments('id'); // Security question id
      $table->string('question', 256); // Security question
      $table->integer('category_id')->unsigned()->nullable(); // Category id
      $table->boolean('is_active')->default(1); // State: 0 - inactive, 1 - active

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
    Schema::dropIfExists('security_questions');
  }
}