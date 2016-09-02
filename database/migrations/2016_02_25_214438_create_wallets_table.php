<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('wallets', function(Blueprint $table)
    {
      /**
       * 이 표에는 사용자의 자금상황(구좌)을 기록한다.
       * users표와 hasOne관계에 있으며 사용자의 창조와 함께 자료가 추가된다.
       * status마당은 구좌가 사용가능한가를 의미하며 0인 경우는 동결을 의미한다.
       * created_at와 updated_at마당을 사용하여 갱신상황을 추적한다.
       * deleted_at마당을 통해 유연제거기능을 사용한다.
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('user_id')->unsigned()->index(); // User id from users table
      $table->decimal('amount', 20, 2)->unsigned()->default(0); // Amount in USD
      $table->boolean('status')->default(1); // Status: 0 - Inactive, 1 - Active
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
    Schema::drop('wallets');
  }

}
