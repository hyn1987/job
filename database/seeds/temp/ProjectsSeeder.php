<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ProjectsSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Add projects.
    $projects = [
      [
        'id' => 1,
        'category_id' => 3,
        'client_id' => 6,
        'subject' => 'Wordpress Developer',
        'desc' => "Hi everybody, we are looking for senior wordpress devs here.

All of the application code was created using Visual Studio .NET and the C#
programming language, while most of the database tasks were performed using the
DB2 Development Add-in to Visual Studio .NET.",
        'type' => 0,
        'duration' => 'MT6M',
        'workload' => 'FT',
        'price' => 22.22,
        'req_cv' => 0,
        'contract_limit' => 10,
        'is_public' => 1,
        'status' => 0,
        'created_at' => "2015-08-31 01:04:38",
        'updated_at' => "2015-08-31 11:04:40",
      ],
      [
        'id' => 2,
        'category_id' => 3,
        'client_id' => 6,
        'subject' => 'Drupal Developer',
        'desc' => 'Hi everybody, we are looking for senior drupal devs here.',
        'type' => 0,
        'duration' => 'LT1M',
        'workload' => 'PT',
        'price' => 33.33,
        'req_cv' => 1,
        'contract_limit' => 2,
        'is_public' => 1,
        'status' => 0,
        'created_at' => "2015-09-01 01:04:38",
        'updated_at' => "2015-09-01 01:04:40",
      ],
      [
        'id' => 3,
        'category_id' => 2,
        'client_id' => 7,
        'subject' => 'Senior Designer',
        'desc' => "Hi everybody, we are looking for senior designer.

Developers using the Microsoft .NET Framework can easily leverage their existing knowledge to access information stored in a DB2 database using the new DB2 Managed Provider. 

This tutorial shows how to create a stored procedure in DB2 and then incorporate it into a Web service built using Microsoft's Visual Studio .NET and C# (pronounced C Sharp). Finally, a simple ASP.NET application demonstrates how to access the web service to display the data over the Internet.",
        'type' => 0,
        'duration' => '',
        'workload' => '',
        'price' => 0,
        'req_cv' => 0,
        'contract_limit' => 1,
        'is_public' => 1,
        'status' => 0,
        'created_at' => "2015-09-02 01:04:38",
        'updated_at' => "2015-09-02 01:04:40",
      ],
      [
        'id' => 4,
        'category_id' => 4,
        'client_id' => 6,
        'subject' => 'Magento Developer',
        'desc' => "Hi everybody, we are looking for senior magento developer.

This tutorial demonstrates the techniques needed to build a Web service in C# that accesses an IBM DB2 Universal Database database using the DB2 managed data provider. The DB2 managed data provider offers capabilities similar to the SQL Server managed data provider as well as providing a high performance, secure way to access a DB2 database from any .NET programming language. 
The DB2 managed data provider was written in C# by the DB2 Development organization to combine the best features and function of DB2, while exploiting the database neutral facilities included in the .NET Framework.

This sample application in this tutorial involves the JustPC.com Music Company, which maintains a database with information about various CDs it has for sale.
Through a Web service it provides, users can search the database to retrieve various kinds of information, including a list of artists, a list of CDs recorded by a specific artist and the list of tracks on the CD.",
        'type' => 1,
        'duration' => '',
        'workload' => 'MT6M',
        'price' => 500,
        'req_cv' => 1,
        'contract_limit' => 1,
        'is_public' => 0,
        'status' => 0,
        'created_at' => "2015-10-01 09:04:38",
        'updated_at' => "2015-10-01 09:04:38",
      ],
      [
        'id' => 5,
        'category_id' => 5,
        'client_id' => 6,
        'subject' => 'Should I take this tutorial?',
        'desc' => "In order to follow along with this tutorial, you will need to create an empty database called MUSIC using the DB2 Control Center. Simply use the Create Database Wizard and specify MUSIC as the name of the database. 
No other information is required to create the database.
Note: While this tutorial was created and tested on a single Windows 2000 Server system, you may choose to run Visual Studio .NET on a Windows 2000 or XP Professional system and copy the appropriate files to the Windows Server system for execution",
        'type' => 1,
        'duration' => '3T6M',
        'workload' => '',
        'price' => 900,
        'req_cv' => 1,
        'contract_limit' => 1,
        'is_public' => 0,
        'status' => 0,
        'created_at' => "2015-10-02 09:26:38",
        'updated_at' => "2015-10-02 19:04:38",
      ],
      [
        'id' => 6,
        'category_id' => 3,
        'client_id' => 7,
        'subject' => 'Overview of the sample application',
        'desc' => "The JustPC.com Music Company maintains a database with information about
various CDs it has for sale. Through a Web service it provides, you can search the
database to retrieve various kinds of information, including a list of artists, a list of
CDs recorded by a specific artist, and the list of tracks on the CD.",
        'type' => 0,
        'duration' => '1T3M',
        'workload' => '',
        'price' => 0,
        'req_cv' => 1,
        'contract_limit' => 1,
        'is_public' => 0,
        'status' => 0,
        'created_at' => "2015-10-02 09:26:38",
        'updated_at' => "2015-10-02 19:04:38",
      ],
    ];
    DB::table('projects')->insert($projects);

    // Add project skills.
    $project_skills = [
      [
        'project_id' => 1, 'skill_id' => 1,
        'order' => 0, 'level' => 7,
      ],
      [
        'project_id' => 1, 'skill_id' => 3,
        'order' => 1, 'level' => 4,
      ],
      [
        'project_id' => 1, 'skill_id' => 6,
        'order' => 2, 'level' => 9,
      ],
      [
        'project_id' => 1, 'skill_id' => 7,
        'order' => 3, 'level' => 10,
      ],
      [
        'project_id' => 2, 'skill_id' => 2,
        'order' => 0, 'level' => 4,
      ],
      [
        'project_id' => 2, 'skill_id' => 4,
        'order' => 1, 'level' => 8,
      ],
      [
        'project_id' => 3, 'skill_id' => 1,
        'order' => 0, 'level' => 10,
      ],
      [
        'project_id' => 4, 'skill_id' => 7,
        'order' => 0, 'level' => 10,
      ],
      [
        'project_id' => 4, 'skill_id' => 3,
        'order' => 1, 'level' => 6,
      ],
      [
        'project_id' => 5, 'skill_id' => 5,
        'order' => 1, 'level' => 8,
      ],
      [
        'project_id' => 5, 'skill_id' => 3,
        'order' => 1, 'level' => 8,
      ],
      [
        'project_id' => 6, 'skill_id' => 1,
        'order' => 1, 'level' => 10,
      ],
      [
        'project_id' => 6, 'skill_id' => 2,
        'order' => 1, 'level' => 8,
      ],
      [
        'project_id' => 6, 'skill_id' => 8,
        'order' => 1, 'level' => 8,
      ],
    ];
    DB::table('project_skills')->insert($project_skills);
  }
}