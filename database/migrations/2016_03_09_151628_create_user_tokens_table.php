<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTokensTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_tokens', function(Blueprint $table)
    {
      /**
       * 이 표는 사용자의 token정보를 보관한다.
       * JSON Web Token(JWT)를 사용한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('user_id')->unsigned()->index(); // User id from users table
      $table->string('api_v1_token', 128)->index()->default(''); // Tracker token for Api v1
      $table->string('reset_pwd_token', 128)->index()->default(''); // Reset password token
      $table->timestamp('reset_pwd_at'); // Reset password token

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
    Schema::drop('user_tokens');
  }

}
