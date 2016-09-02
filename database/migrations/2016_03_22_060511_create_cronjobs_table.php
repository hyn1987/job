<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronjobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cronjobs', function(Blueprint $table)
		{
			
			/**
       * 이 표는 cronjob들을 보관한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('type')->unsigned()->index(); // 0: Notification Cronjob Task,
      $table->text('meta'); // json data for cronjob
      $table->integer('max_runtime')->unsigned()->index(); //max running seconds for cronjob.
      $table->integer('status')->unsigned()->index(); //0: ready, 1: pause, 2: process, 3: complete
      $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cronjobs');
	}

}
