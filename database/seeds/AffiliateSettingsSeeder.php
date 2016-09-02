<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AffiliateSettingsSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Add Notifications for Constant.
    $affiliate = [
      [
        'id' => 1,
        'percent' => 0,
        'duration' => 0,
      ],
    ];
    DB::table('affiliate_settings')->insert($affiliate);
  }
}