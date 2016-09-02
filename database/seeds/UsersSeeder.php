<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $now = date('Y-m-d H:i:s'); // Get the current time by Mysql format.

    // Add users.
    $users = [
      [
        'id' => 1,
        'username' => 'admin',
        'email' => 'admin@wawjob.com',
        'password' => Hash::make('admin'), // Note that should be updated later.
        'created_at' => $now,
        'updated_at' => $now,
        'status' => 1,
      ],

      // Please remove the below rows.
      [
        'id' => 2,
        'username' => 'jin',
        'email' => 'jin@wawjob.com',
        'password' => Hash::make('jin'),
        'created_at' => $now,
        'updated_at' => $now,
        'status' => 1,
      ],
      [
        'id' => 3,
        'username' => 'son',
        'email' => 'son@wawjob.com',
        'password' => Hash::make('son'),
        'created_at' => $now,
        'updated_at' => $now,
        'status' => 1,
      ],
      [
        'id' => 4,
        'username' => 'jo',
        'email' => 'jo@wawjob.com',
        'password' => Hash::make('jo'),
        'created_at' => $now,
        'updated_at' => $now,
        'status' => 1,
      ],
      [
        'id' => 5,
        'username' => 'so',
        'email' => 'so@wawjob.com',
        'password' => Hash::make('so'),
        'created_at' => $now,
        'updated_at' => $now,
        'status' => 1,
      ],
      [
        'id' => 6,
        'username' => 'pak',
        'email' => 'pak@wawjob.com',
        'password' => Hash::make('pak'),
        'created_at' => $now,
        'updated_at' => $now,
        'status' => 1,
      ],
      [
        'id' => 7,
        'username' => 'ri',
        'email' => 'ri@wawjob.com',
        'password' => Hash::make('ri'),
        'created_at' => $now,
        'updated_at' => $now,
        'status' => 1,
      ],
      [
        'id' => 8,
        'username' => 'paek',
        'email' => 'paek@wawjob.com',
        'password' => Hash::make('paek'),
        'created_at' => $now,
        'updated_at' => $now,
        'status' => 1,
      ],
    ];
    DB::table('users')->insert($users);

    // Add roles.
    $roles = [
      [
        'user_id' => 1,
        'role_id' => 2,
      ],
      // Please remove below rows.
      [
        'user_id' => 2,
        'role_id' => 5,
      ],
      [
        'user_id' => 3,
        'role_id' => 5,
      ],
      [
        'user_id' => 4,
        'role_id' => 5,
      ],
      [
        'user_id' => 5,
        'role_id' => 5,
      ],
      [
        'user_id' => 6,
        'role_id' => 4,
      ],
      [
        'user_id' => 7,
        'role_id' => 4,
      ],
      [
        'user_id' => 8,
        'role_id' => 4,
      ],
    ];

    DB::table('users_roles')->insert($roles);

    // Add contacts.
    $contacts = [
      [
        'user_id' => 1,
        'first_name' => 'Job',
        'last_name' => 'Waw',
      ],
      // Please remove below rows.
      [
        'user_id' => 2,
        'first_name' => 'KukSong',
        'last_name' => 'Kim',
      ],
      [
        'user_id' => 3,
        'first_name' => 'YongJin',
        'last_name' => 'Son',
      ],
      [
        'user_id' => 4,
        'first_name' => 'MyongSon',
        'last_name' => 'Jo',
      ],
      [
        'user_id' => 5,
        'first_name' => 'Kwang',
        'last_name' => 'So',
      ],
      [
        'user_id' => 6,
        'first_name' => 'MyongJin',
        'last_name' => 'Pak',
      ],
      [
        'user_id' => 7,
        'first_name' => 'CholMin',
        'last_name' => 'Ri',
      ],
      [
        'user_id' => 8,
        'first_name' => 'MyongChol',
        'last_name' => 'Paek',
      ],
    ];

    DB::table('user_contacts')->insert($contacts);

    // Add profiles.
    $profiles = [
      [
        'id' => 1,
        'user_id' => 1,
        'title' => 'Administrator',
        'desc' => 'I am an administrator on wawjob.',
      ],
      // Please remove below rows.
      [
        'id' => 2,
        'user_id' => 2,
        'title' => 'Senior Developer',
        'desc' => 'I am a senior developer.',
      ],
    ];

    DB::table('user_profiles')->insert($profiles);

    // Add educations.
    $educations = [
      [
        'id' => 1,
        'user_id' => 1,
        'school' => 'TsingHua University',
        'degree' => 'master',
        'major' => 'computer science',
        'minor' => 'web development',
        'desc' => 'Dont worries.',
        'from' => '2002/04/01',
        'to' => '2006/06/14',
        'is_verified' => 1,
        'is_active' => 1,
      ],
      [
        'id' => 2,
        'user_id' => 2,
        'school' => 'Beijing University',
        'degree' => 'master',
        'major' => 'computer science',
        'minor' => 'web development',
        'desc' => 'Dont worries.',
        'from' => '2002/04/01',
        'to' => '2006/06/14',
        'is_verified' => 1,
        'is_active' => 1,
      ],
    ];

    DB::table('user_educations')->insert($educations);

    // Add employments.
    $employments = [
      [
        'id' => 1,
        'user_id' => 1,
        'company' => 'Jinan Software Compnay',
        'position' => 'pm',
        'desc' => 'Dont worries.',
        'from' => '2002/04/01',
        'to' => '2006/06/14',
        'is_verified' => 1,
        'is_active' => 1,
      ],
      [
        'id' => 2,
        'user_id' => 2,
        'company' => 'Beijing Software Company',
        'position' => 'pm',
        'desc' => 'Dont worries.',
        'from' => '2002/04/01',
        'to' => '2006/06/14',
        'is_verified' => 1,
        'is_active' => 1,
      ],
    ];

    DB::table('user_employments')->insert($employments);

    // Add skills.
    $skills = [
      [
        'id' => 1,
        'user_id' => 1,
        'skill_id' => 1,
      ],
      [
        'id' => 2,
        'user_id' => 1,
        'skill_id' => 2,
      ],
      [
        'id' => 3,
        'user_id' => 2,
        'skill_id' => 1,
      ],
    ];

    DB::table('user_skills')->insert($skills);

    // Add languages.
    $languages = [
      [
        'id' => 1,
        'user_id' => 1,
        'lang_id' => 1,
      ],
      [
        'id' => 2,
        'user_id' => 2,
        'lang_id' => 1,
      ],
      [
        'id' => 3,
        'user_id' => 2,
        'lang_id' => 2,
      ],
    ];

    DB::table('users_languages')->insert($languages);
  }
}