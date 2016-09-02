<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('affiliate_settings', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id'); // Id
			$table->float('percent')->unsigned()->default(0);
			$table->tinyInteger('duration')->unsigned()->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('affiliate_settings');
	}

}
