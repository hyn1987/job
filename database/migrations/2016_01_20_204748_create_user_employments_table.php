<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEmploymentsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_employments', function(Blueprint $table)
    {
      /**
       * 이 표는 사용자들의 경력정보를 보관한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('user_id')->unsigned()->index(); // User id from users table
      $table->string('company', 512); // Company name
      $table->string('position', 128); // Position on the company
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
    Schema::drop('user_employments');
  }
}