<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAffiliatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_affiliates', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

		    $table->increments('id'); // Id
		    $table->integer('user_id')->unsigned(); // User id
		    $table->integer('affiliate_id')->unsigned(); // Affiliate User id
		    $table->tinyInteger('percent')->unsigned(); // percent for affiliate
		    $table->tinyInteger('duration')->unsigned(); // percent for affiliate
		    $table->timestamp('created_at'); // Time ticket has been created
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_affiliates');
	}

}
