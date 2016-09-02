<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEducationsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_educations', function(Blueprint $table)
    {
      /**
       * 이 표는 사용자들의 학력정보를 보관한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('user_id')->unsigned()->index(); // User id from users table
      $table->string('school', 512); // School name
      $table->string('degree', 128); // Degree
      $table->string('major', 128); // Major
      $table->string('minor', 128); // Minor
      $table->string('desc', 1024); // Description
      $table->date('from'); // From
      $table->date('to'); // To
      $table->boolean('is_verified')->default(0); // Verification flag
      $table->boolean('is_active')->default(0); // Active flag: 0 - Inactive, 1 - Active

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
    Schema::drop('user_educations');
  }
}