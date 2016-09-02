<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_profiles', function(Blueprint $table)
    {
      /**
       * This table keeps the information for user profile.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // User profile id
      $table->integer('user_id')->unsigned()->index(); // User id from users table
      $table->string('title', 256)->nullable(); // User profile title
      $table->string('desc', 1028)->nullable(); // User profile desc
      $table->decimal('rate', 10, 2)->nullable(); // User work rate, which includes site fee
      $table->tinyInteger('en_level')->unsigned()->default(0); // User english level, 0 ~ 5
      $table->tinyInteger('share')->unsigned()->default(0); // Share profile, 0 - public, 1 - users only, 2 - private (only for buyer)

      $table->decimal('total_score', 3, 2)->nullable();

      /*
      alter table `www.wawjob.com`.`user_profiles` 
   add column `metered_at` timestamp NULL COMMENT 'Last timestamp when total minutes were metered.' after `total_mins`;
    ALTER TABLE `www.wawjob.com`.`user_profiles`     ADD COLUMN `total_score` DECIMAL(3,2) DEFAULT '0' NULL COMMENT 'Total score (not-available when NULL)' AFTER `total_mins`;
      */
      $table->timestamp('metered_at')->nullable();

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
    Schema::drop('user_profiles');
  }
}