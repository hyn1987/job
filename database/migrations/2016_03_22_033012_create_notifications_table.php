<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('notifications', function(Blueprint $table)
    {
      /**
       * 이 표는 체계 통보문들을 보관한다.
       * is_const마당이 1이면 관리자가 삭제할수 없다.
       * content마당에는 여러개 localization 통보문들의 배렬을 json형식으로 변환하여 보관한다.
       * 실례로
       * {"en":"You have received an invitation to interview for the job \":job_title\""}
       * 이때 en마당은 필수항목이다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->string('slug', 128)->unique(); // Slug
      $table->text('content'); // Content
      $table->tinyInteger('is_const')->unsigned()->default(0); // The const flag: 0 - Not, 1 - Constant
      $table->tinyInteger('type')->unsigned()->default(0); // Type: 0 - Normal, 1 - System
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('notifications');
  }
}