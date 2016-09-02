<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RolesSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('roles')->insert([
      'id' => 1,
      'slug' => 'user',
      'name' => 'User',
      'desc' => 'This is an account',
      'is_active' => 1,
    ]);

    DB::table('roles')->insert([
      'id' => 2,
      'slug' => 'user_sadmin',
      'name' => 'Super Administrator',
      'desc' => 'This is an account for super administrator',
      'parent_id' => 1,
      'is_active' => 1,
    ]);

    DB::table('roles')->insert([
      'id' => 3,
      'slug' => 'user_admin',
      'name' => 'Administrator',
      'desc' => 'This is an account for administrator',
      'parent_id' => 1,
      'is_active' => 1,
    ]);

    DB::table('roles')->insert([
      'id' => 4,
      'slug' => 'user_buyer',
      'name' => 'Buyer',
      'desc' => 'This is an account for buyer',
      'parent_id' => 1,
      'is_active' => 1,
    ]);

    DB::table('roles')->insert([
      'id' => 5,
      'slug' => 'user_freelancer',
      'name' => 'Freelancer',
      'desc' => 'This is an account for freelancer',
      'parent_id' => 1,
      'is_active' => 1,
    ]);
  }
}