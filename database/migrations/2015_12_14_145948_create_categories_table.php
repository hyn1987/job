<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('categories', function(Blueprint $table)
    {
      /**
       * 이 표는 캐테고리정보를 보관한다.
       * type마당은 캐터고리의 형태를 지정한다.
       * 0 - Project
       * 1 - QA
       * 2 - Maintanance
       *
       * 현단계에서 desc마당의 사용을 보류한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Category id
      $table->tinyInteger('type')->unsigned()->default(0); // Category type
      $table->string('name', 64); // Cateogry name
      $table->string('desc', 255)->nullable(); // Category description
      $table->integer('parent_id')->unsigned()->index()->nullable(); // Parent category id from id
      $table->smallInteger('order')->unsigned()->nullable(); // Cateogory order

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
    Schema::dropIfExists('categories');
  }
}