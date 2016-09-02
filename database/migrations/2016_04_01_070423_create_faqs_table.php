<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('faqs', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id'); // Id
			$table->string('title', 1024); // title
		    $table->text('content'); // Content
		    $table->integer('cat_id')->unsigned()->default(0);
		    $table->tinyInteger('type')->unsigned()->default(1); // type : 0 - Buyer, 1 - All, 2 - Freelancer
		    $table->tinyInteger('visible')->unsigned()->default(1); // Show : 0 - Hidden, 1 - Show
		    $table->integer('order')->unsigned()->default(0); // order
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('faqs');
	}

}
