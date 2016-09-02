<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHourlyLogMapsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hourly_log_maps', function(Blueprint $table)
		{
      // DB Engine
      $table->engine = 'InnoDB';

			$table->increments('id');

      // Reference field: `contracts`.id (unsigned)
      $table->integer('contract_id', false, true);

      // Date
      $table->date('date');

      // Total minutes of a day (unsigned)
      $table->smallInteger('mins', false, true);

      // Screenshot memo. e.g.: I worked for ...
      $table->text('act')->nullable();

			$table->timestamps();

			$table->index(array('contract_id', 'date'), 'c_date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hourly_log_maps');
	}

}
