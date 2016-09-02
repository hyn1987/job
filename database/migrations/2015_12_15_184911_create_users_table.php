<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function(Blueprint $table)
    {
      $table->engine = 'InnoDB';

      $table->increments('id'); // User id
      $table->string('username', 128)->unique(); // User name
      $table->string('email', 128)->unique(); // User email
      $table->string('password', 128); // Password, Hash bcrypt
      $table->integer('question_id')->unsigned()->nullable(); // Security question id from security_questions table
      $table->string('answer', 128)->nullable(); // Answer, Hash bcrypt
      $table->tinyInteger('status')->unsigned()->default(0); // User Status: 0 - Inactive, There are multiple states, such as 1 - Active, 2 - Block, 3 - Financial Suspend, 4 - Account Suspend, 9 - Account Closed.

      $table->string('locale', 8)->nullable(); // User locale

      $table->rememberToken();
      $table->timestamps(); // Add 'create_at' and 'updated_at' fields.

      // Soft Delete
      $table->softDeletes();

      // Covering Indexes
      $table->index(array('username', 'password'));
      $table->index(array('email', 'password'));
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('users');
  }
}