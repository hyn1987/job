<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ContractsSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Add Contracts.
    $contracts = [
      [
        'id' => 1,
        'title' => 'Wordpress Developer',
        'buyer_id' => 6,
        'contractor_id' => 5,
        'project_id' => 1,
        'application_id' => 1,
        'type' => true,
        'price' => 22.22,
        'limit' => 50,
        'is_allowed_manual_time' => true,
        'status' => 1,
        'created_at' => "2015-09-10 02:38:03",
        'started_at' => "2015-09-10 08:48:03",
      ],

      [
        'id' => 2,
        'title' => 'Wordpress Developer Required',
        'buyer_id' => 6,
        'contractor_id' => 3,
        'project_id' => 1,
        'application_id' => 2,
        'type' => true,
        'price' => 27.78,
        'limit' => 0,
        'is_allowed_manual_time' => true,
        'status' => 1,
        'created_at' => "2015-10-25 12:38:03",
        'started_at' => "2015-10-26 08:48:03",
      ],

      [
        'id' => 3,
        'title' => 'Another Wordpress Developer Required',
        'buyer_id' => 6,
        'contractor_id' => 2,
        'project_id' => 1,
        'application_id' => 2,
        'type' => false,
        'price' => 1200,
        'limit' => 0,
        'is_allowed_manual_time' => true,
        'status' => 1,
        'created_at' => "2015-10-29 11:38:09",
        'started_at' => "2015-10-30 08:46:03",
      ],

      [
        'id' => 4,
        'title' => 'Drupal Developer Required',
        'buyer_id' => 6,
        'contractor_id' => 2,
        'project_id' => 2,
        'application_id' => 2,
        'type' => false,
        'price' => 800,
        'limit' => 0,
        'is_allowed_manual_time' => true,
        'status' => 1,
        'created_at' => "2015-10-30 11:38:09",
        'started_at' => "2015-10-31 08:46:03",
      ],      

      [
        'id' => 5,
        'title' => 'Another Drupal Developer Required',
        'buyer_id' => 6,
        'contractor_id' => 3,
        'project_id' => 2,
        'application_id' => 2,
        'type' => true,
        'price' => 44.44,
        'limit' => 0,
        'is_allowed_manual_time' => true,
        'status' => 1,
        'created_at' => "2015-11-01 10:38:09",
        'started_at' => "2015-11-03 08:46:03",
      ],

      [
        'id' => 6,
        'title' => 'Magento Developer Required',
        'buyer_id' => 6,
        'contractor_id' => 3,
        'project_id' => 4,
        'application_id' => 2,
        'type' => true,
        'price' => 44.44,
        'limit' => 0,
        'is_allowed_manual_time' => true,
        'status' => 1,
        'created_at' => "2015-11-04 02:39:09",
        'started_at' => "2015-11-05 18:46:03",
      ],

      [
        'id' => 7,
        'title' => 'Magento Guru',
        'buyer_id' => 6,
        'contractor_id' => 5,
        'project_id' => 4,
        'application_id' => 2,
        'type' => true,
        'price' => 33.33,
        'limit' => 65,
        'is_allowed_manual_time' => false,
        'status' => 1,
        'created_at' => "2015-11-04 02:39:09",
        'started_at' => "2015-11-05 18:46:03",
      ],      
    ];

    DB::table('contracts')->insert($contracts);
  }
}