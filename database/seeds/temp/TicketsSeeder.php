<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TicketsSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $now = date("Y-m-d H:i:s");

    // Add tickets.
    $tickets = [
      [
        'subject' => 'New functionalities comes on!',
        'content' => 'Hello everybody, our devs upgrade the system completely, please try our new system now!',
        'user_id' => 3,
        'type' => 0,
        'priority' => 0,
        'created_at' => $now,
        'updated_at' => $now,
      ],
      [
        'subject' => 'What happened?',
        'content' => 'My account has been suspended and not sure what happened.',
        'user_id' => 2,
        'type' => 4,
        'priority' => 0,
        'created_at' => $now,
        'updated_at' => $now,
      ],
    ];

    DB::table('tickets')->insert($tickets);
  }
}