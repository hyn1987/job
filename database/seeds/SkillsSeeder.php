<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SkillsSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $skills = array(
      array(
        'id' => 1,
        'name' => 'php',
        'desc' => 'PHP is a general-purpose server-side scripting language originally designed for web development to produce dynamic web pages.',
      ),
      array(
        'id' => 2,
        'name' => 'css3',
        'desc' => 'Cascading Style Sheets (CSS) is a style sheet language used to describe the presentation (that is, the look and formatting) of a document written in a markup language.',
      ),
      array(
        'id' => 3,
        'name' => 'mysql',
        'desc' => 'MySQL is a relational database management system (RDBMS) that runs as a server providing multi-user access to a number of databases.',
      ),
      array(
        'id' => 4,
        'name' => 'magento',
        'desc' => 'Magento is one of ecommerce solutions.',
      ),
      array(
        'id' => 5,
        'name' => 'drupal',
        'desc' => 'Drupal is the best cms platform, recommended!',
      ),
      array(
        'id' => 6,
        'name' => 'cocos2d',
        'desc' => 'What is cocos2d? I dont know and love!',
      ),
      array(
        'id' => 7,
        'name' => 'ruby',
        'desc' => 'Wow~ Ruby, is my love!',
      ),
      array(
        'id' => 8,
        'name' => 'laravel',
        'desc' => 'Laravel is the best one!',
      ),
    );

    DB::table('skills')->insert($skills);
  }
}