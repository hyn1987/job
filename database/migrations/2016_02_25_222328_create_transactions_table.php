<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('transactions', function(Blueprint $table)
    {
      /**
       * 이 표에는 사용자의 트랜잭션상황을 기록한다.
       * users표와 hasMany관계에 있다.
       * type마당은 트랜잭션의 형태를 규정한다.
       * 0 - Fixed:  buyer: -, freelancer: +
       * 1 - Hourly: buyer: -, freelancer: +
       * 2 - Bonus:  buyer: -, freelancer: +
       * 3 - Charge: all: +
       * 4 - Withdrawal: all: -
       * 5 - Refund: buyer: +, freelancer: -
       *
       * status마당은 트랜잭션의 처리상황을 규정한다.
       * 0 - Pending
       * 1 - Available
       */
      // DB Engine
      $table->engine = 'InnoDB';

      $table->increments('id'); // Id
      $table->integer('user_id')->unsigned()->index(); // User id from users table
      $table->tinyInteger('type')->unsigned()->default(0); // Transaction type
      $table->decimal('amount', 20, 2)->unsigned()->default(0); // Amount in USD
      $table->tinyInteger('status')->default(0); // Transaction status
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
    Schema::drop('transactions');
  }

}
