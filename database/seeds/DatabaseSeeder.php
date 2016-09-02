<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();

    $seeders = [
      'categories',
      'roles',
      'skills',
      'countries',
      'timezones',
      'languages',
      'notifications',
      'users',
      'projects',
      'contracts',
      'tickets',
      'temp'
    ];

    foreach ($seeders as $seeder) {
      $this->call(ucfirst($seeder) . 'Seeder');
    }
  }
}