<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TempSeeder extends Seeder {

  protected function runHourlyLogs()
  {
   $ss = [];

    $HMs = ['00:02', '00:10', '00:50', '01:00', '01:10', '02:50', '03:00', '03:10', '03:25', '03:30', '03:40', '03:50', '13:30', '14:10', '14:20'];

    $comments = collect([
      'Building Admin Dashboard',
      'Building Admin Dashboard',
      'Building Admin Dashboard',
      'Building Admin Dashboard',
      'Building Admin Dashboard',
      'Building Admin Dashboard',
      'Hiring Manager',
      'Job Search page',
      'HTML v5',
      'HTML v5',
      'HTML v5'
    ]);

    $activeWindows = collect([
      'Windows Explorer',
      'Edit Plus 3',
      'Sublime Text 3',
      'Total Command',
      'Command Line',
      'Fox it Reader',
      'HTML viewer',
      'QQ Player',
      'Any Video Converter',
      'Adobe Photoshop CS6',
      'XAMPP Control Panel',
      'Google Chrome',
      'Mozilla Firefox'
    ]);

    //$date = date("Y-m-d");
    $date = "2016-03-20";
    $totalHm = count($HMs);

    foreach($HMs as $ix => $hm) {
      $sec = sprintf("%02d", rand(0, 59));

      $acts = [];
      list($h, $m) = explode(":", $hm, 2);

      $startMin = rand(0, 30);
      $endMin = $startMin + rand(5, 19);
      for($minute = $startMin; $minute <= $endMin; $minute++) {
        $key = sprintf("%02d:%02d", $h, $minute);
        $acts[$key] = [
          'k' => rand(0, 199),
          'm' => rand(0, 99),
        ];
      }

      $is_overlimit = ($ix > $totalHm - 4) ? 1 : 0;

      $item = [
        'contract_id' => 1,
        'comment' => $comments->random(),
        'activity' =>  json_encode($acts),
        'score' => rand(3, 10),
        'active_window' => $activeWindows->random(),
        'taken_at' => "$date $hm:$sec",
        'is_manual' => 0,
        'is_overlimit' => $is_overlimit
      ];

      $ss[] = $item;
    }

    $ss_extra = [
      [
        'contract_id' => 1,
        'comment' => 'Building Admin Dashboard',
        'activity' => '',
        'score' => 0,
        'active_window' => '',
        'taken_at' => "$date 14:30:00",
        'is_manual' => 1,
        'is_overlimit' => 0
      ],

      [
        'contract_id' => 1,
        'comment' => 'Building Admin Dashboard',
        'activity' => '',
        'score' => 0,
        'active_window' => '',
        'taken_at' => "$date 14:40:00",
        'is_manual' => 1,
        'is_overlimit' => 0
      ],

      [
        'contract_id' => 1,
        'comment' => 'Building Admin Dashboard',
        'activity' => '',
        'score' => 0,
        'active_window' => '',
        'taken_at' => "$date 14:40:00",
        'is_manual' => 1,
        'is_overlimit' => 0
      ]
    ];

    $ss = array_merge($ss, $ss_extra);

    DB::table('hourly_logs')->insert($ss);
  }


  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $this->runHourlyLogs();
  }
}