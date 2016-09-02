<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPortfoliosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_portfolios', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id'); // Id
			$table->integer('user_id')->unsigned()->index(); // User Id
			$table->integer('cat_id')->unsigned()->index(); // Category Id
			$table->string('title', 512); // title for portfolio
			$table->string('url', 512); // site url for portfolio
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_portfolios');
	}

}
