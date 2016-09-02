<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserContactsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_contacts', function(Blueprint $table)
    {
      /**
       * 이 표는 사용자의 신상정보를 보관한다.
       * has_avatar마당은 사용자가 avatar를 가지고 있는가를 나타낸다.
       * avatar는 public/uploads/avatars폴더에 보관된다.
       * 파일형식은 1.png, 1_48.png, 1_128.png와 같다.
       * avatar파일들은 png 형식에 각종 크기로 보관한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // User contact id
      $table->integer('user_id')->unsigned()->index(); // User id from users table
      $table->string('first_name', 48); // User first name
      $table->string('last_name', 48); // User last name
      $table->boolean('gender')->default(0); // User gender: 0 - male, 1 - female
      $table->date('birthday')->nullable(); // User birthday
      $table->string('country_code', 2)->index()->nullable(); // User country from countries table
      $table->string('state', 48)->nullable(); // User state
      $table->string('city', 48)->nullable(); // User city
      $table->string('address', 128)->nullable(); // User address
      $table->string('address2', 128)->nullable(); // User another address
      $table->string('zipcode', 16)->nullable(); // User postal code
      $table->string('phone', 24)->nullable(); // User phone number
      $table->string('fax', 24)->nullable(); // User fax number
      $table->integer('timezone_id')->unsigned()->index()->nullable(); // User timezone id from timezones table
      $table->string('website', 128)->nullable(); // Website url
      $table->string('skype', 128)->nullable(); // Skype id
      $table->string('yahoo', 128)->nullable(); // Yahoo id
      $table->string('qq', 128)->nullable(); // QQ id

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
    Schema::dropIfExists('user_contacts');
  }
}