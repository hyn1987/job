<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAnalyticsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_analytics', function(Blueprint $table)
    {
      $table->engine = 'InnoDB';

      $table->increments('id'); // Analytic id
      $table->integer('user_id')->unsigned(); // User id from users table
      $table->string('login_ipv4', 16)->nullable(); // IP address, IPv4
      $table->timestamp('logged_at')->nullable(); // The time logged at
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('user_analytics');
  }
}