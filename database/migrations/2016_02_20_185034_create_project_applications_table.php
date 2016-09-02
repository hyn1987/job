<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectApplicationsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('project_applications', function(Blueprint $table)
    {
      /**
       * This table keeps the applicants for projects, will be used with projects table.
       * When the providers apply to the project posting, then we will use this table to create applications from providers.
       * Field 'provenance' indicates if the application made from invitation or not.
       * We will use the 'price' field with type of project(hourly and fixed), if hourly type, it will mean hourly rate.
       * Beside, we will ask cover letter and attachment.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('project_id')->unsigned()->index(); // Project id from projects table
      $table->integer('user_id')->unsigned()->index(); // User id from users table
      $table->tinyInteger('provenance')->unsigned()->default(0); // Provenance, 0 - Freelancer, 1 - Invitor
      $table->smallInteger('type')->unsigned()->default(0); // Project type, 0 - Hourly, 1 - Fixed
      $table->decimal('price', 20, 2); // Price or Rate(hourly) not including fee for site
      $table->string('cv', 4096)->nullable(); // Cover letter
      $table->integer('file_id')->unsigned()->nullable(); // Attachment id from files table
      $table->tinyInteger('status')->unsigned()->default(0); // Status, 0 - Normal, 1 - Invite, 2 - Active, 3 - Declined by you, 4 - Declined by client, 5 - Project Cancelled, 6 - Project Expired
      $table->string('reason', 4096); // Reason, extended status message
      $table->timestamps();

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
    Schema::drop('project_applications');
  }

}
