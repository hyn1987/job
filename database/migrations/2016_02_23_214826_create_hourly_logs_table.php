<?php
/* Mar 16, 2016 - paulz */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
// #sample_code_block#
$tmp = [
  "12:00" => ['k' => 999, 'm' => 999],
  "12:01" => ['k' => 999, 'm' => 999],
  "12:02" => ['k' => 999, 'm' => 999],
  "12:03" => ['k' => 999, 'm' => 999],
  "12:04" => ['k' => 999, 'm' => 999],
  "12:05" => ['k' => 999, 'm' => 999],
  "12:06" => ['k' => 999, 'm' => 999],
  "12:07" => ['k' => 999, 'm' => 999],
  "12:08" => ['k' => 999, 'm' => 999],
  "12:09" => ['k' => 999, 'm' => 999],
  "12:10" => ['k' => 999, 'm' => 999],
  "12:11" => ['k' => 999, 'm' => 999],
  "12:12" => ['k' => 999, 'm' => 999],
  "12:13" => ['k' => 999, 'm' => 999],
  "12:14" => ['k' => 999, 'm' => 999],
  "12:15" => ['k' => 999, 'm' => 999],
  "12:16" => ['k' => 999, 'm' => 999],
  "12:17" => ['k' => 999, 'm' => 999],
  "12:18" => ['k' => 999, 'm' => 999],
  "12:19" => ['k' => 999, 'm' => 999],
];

echo json_encode($tmp);
exit;
*/


class CreateHourlyLogsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('hourly_logs', function(Blueprint $table)
    {
      /**
       * Keeps the records of screenshots which are taken each 10 minutes
       * Each row of this table contains information of a screenshot
       *
       * To see the file path of screenshots, open documentation/Wawjob.xlsx
       */

      // DB Engine
      $table->engine = 'InnoDB';

      // Primary key
      $table->increments('id');

      // Reference field: `contracts`.id
      $table->integer('contract_id')->unsigned()->index();

      // Screenshot memo. e.g.: I worked for ...
      $table->string('comment', 4096)->nullable();

      // JSON data Activity array of items
      //      "HH:mm" => {"k": xxx, "m": xxx}
      //
      // to see example, run #sample_code_block# in the beginning of this file
      // When we record keyboard and mouse action for 19 minutes data, it comes to be 522 characters. So we reserve 600 bytes as length
      $table->string('activity', 600)->default('');

      // Activity score for the screenshot (0 ~ 10)
      $table->tinyInteger('score')->unsigned()->default(0);

      // Title of active window when the screenshot was taken of
      $table->string('active_window', 255);

      // Auto | Manual
      $table->tinyInteger('is_manual')->unsigned()->default(0);

      // Over weekly limit or not (Green: within limit, Brown: over limit)
      $table->tinyInteger('is_overlimit')->unsigned()->default(0);

      // Timestamp when the screenshot was taken
      $table->timestamp('taken_at')->default('0000-00-00 00:00:00');
      $table->timestamp('updated_at')->nullable();

      // Soft deletes
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
    Schema::drop('hourly_logs');
  }
}