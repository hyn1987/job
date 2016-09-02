<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TimezonesSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('timezones')->insert([
      [
        'id'            =>  1,
        'name'          =>  'Etc/UTC',
        'label'         =>  'UTC (Coordinated Universal Time)',
        'gmt_offset'    =>  0.00,
      ],
      [
        'id'            =>  2,
        'name'          =>  'Europe/London',
        'label'         =>  'UTC (Coordinated Universal Time) Dublin, Edinburgh, London',
        'gmt_offset'    =>  0.00,
      ],
      [
        'id'            =>  3,
        'name'          =>  'Africa/Casablanca',
        'label'         =>  'UTC (no DST) Tangiers, Casablanca',
        'gmt_offset'    =>  0.00,
      ],
      [
        'id'            =>  4,
        'name'          =>  'Europe/Lisbon',
        'label'         =>  'UTC +00:00 Lisbon',
        'gmt_offset'    =>  0.00,
      ],
      [
        'id'            =>  5,
        'name'          =>  'Africa/Algiers',
        'label'         =>  'UTC +01:00 Algeria',
        'gmt_offset'    =>  1.00,
      ],
      [
        'id'            =>  6,
        'name'          =>  'Europe/Berlin',
        'label'         =>  'UTC +01:00 Berlin, Stockholm, Rome, Bern, Brussels',
        'gmt_offset'    =>  1.00,
      ],
      [
        'id'            =>  7,
        'name'          =>  'Europe/Paris',
        'label'         =>  'UTC +01:00 Paris, Madrid',
        'gmt_offset'    =>  1.00,
      ],
      [
        'id'            =>  8,
        'name'          =>  'Europe/Prague',
        'label'         =>  'UTC +01:00 Prague, Warsaw',
        'gmt_offset'    =>  1.00,
      ],
      [
        'id'            =>  9,
        'name'          =>  'Europe/Athens',
        'label'         =>  'UTC +02:00 Athens, Helsinki, Istanbul',
        'gmt_offset'    =>  2.00,
      ],
      [
        'id'            =>  10,
        'name'          =>  'Africa/Cairo',
        'label'         =>  'UTC +02:00 Cairo',
        'gmt_offset'    =>  2.00,
      ],
      [
        'id'            =>  11,
        'name'          =>  'EET',
        'label'         =>  'UTC +02:00 Eastern Europe',
        'gmt_offset'    =>  2.00,
      ],
      [
        'id'            =>  12,
        'name'          =>  'Africa/Harare',
        'label'         =>  'UTC +02:00 Harare, Pretoria',
        'gmt_offset'    =>  2.00,
      ],
      [
        'id'            =>  13,
        'name'          =>  'Asia/Jerusalem',
        'label'         =>  'UTC +02:00 Israel',
        'gmt_offset'    =>  2.00,
      ],
      [
        'id'            =>  14,
        'name'          =>  'Asia/Baghdad',
        'label'         =>  'UTC +03:00 Baghdad, Kuwait, Nairobi, Riyadh',
        'gmt_offset'    =>  3.00,
      ],
      [
        'id'            =>  15,
        'name'          =>  'Europe/Minsk',
        'label'         =>  'UTC +03:00 Minsk',
        'gmt_offset'    =>  3.00,
      ],
      [
        'id'            =>  16,
        'name'          =>  'Asia/Tehran',
        'label'         =>  'UTC +03:30 Tehran',
        'gmt_offset'    =>  3.50,
      ],
      [
        'id'            =>  17,
        'name'          =>  'Asia/Tbilisi',
        'label'         =>  'UTC +04:00 Abu Dhabi, Muscat, Tbilisi, Kazan',
        'gmt_offset'    =>  4.00,
      ],
      [
        'id'            =>  18,
        'name'          =>  'Asia/Yerevan',
        'label'         =>  'UTC +04:00 Armenia',
        'gmt_offset'    =>  4.00,
      ],
      [
        'id'            =>  19,
        'name'          =>  'Europe/Moscow',
        'label'         =>  'UTC +04:00 Moscow, St. Petersburg, Volgograd',
        'gmt_offset'    =>  4.00,
      ],
      [
        'id'            =>  20,
        'name'          =>  'Asia/Kabul',
        'label'         =>  'UTC +04:30 Kabul',
        'gmt_offset'    =>  4.50,
      ],
      [
        'id'            =>  21,
        'name'          =>  'Asia/Karachi',
        'label'         =>  'UTC +05:00 Islamabad, Karachi',
        'gmt_offset'    =>  5.00,
      ],
      [
        'id'            =>  22,
        'name'          =>  'Asia/Tashkent',
        'label'         =>  'UTC +05:00 Tashkent',
        'gmt_offset'    =>  5.00,
      ],
      [
        'id'            =>  23,
        'name'          =>  'Asia/Calcutta',
        'label'         =>  'UTC +05:30 Mumbai, Kolkata, Chennai, New Delhi',
        'gmt_offset'    =>  5.50,
      ],
      [
        'id'            =>  24,
        'name'          =>  'Asia/Katmandu',
        'label'         =>  'UTC +05:45 Kathmandu, Nepal',
        'gmt_offset'    =>  5.75,
      ],
      [
        'id'            =>  25,
        'name'          =>  'Asia/Almaty',
        'label'         =>  'UTC +06:00 Almaty, Dhaka',
        'gmt_offset'    =>  6.00,
      ],
      [
        'id'            =>  26,
        'name'          =>  'Asia/Yekaterinburg',
        'label'         =>  'UTC +06:00 Sverdlovsk',
        'gmt_offset'    =>  6.00,
      ],
      [
        'id'            =>  27,
        'name'          =>  'Asia/Bangkok',
        'label'         =>  'UTC +07:00 Bangkok, Jakarta, Hanoi',
        'gmt_offset'    =>  7.00,
      ],
      [
        'id'            =>  28,
        'name'          =>  'Asia/Omsk',
        'label'         =>  'UTC +07:00 Omsk, Novosibirsk',
        'gmt_offset'    =>  7.00,
      ],
      [
        'id'            =>  29,
        'name'          =>  'Asia/Shanghai',
        'label'         =>  'UTC +08:00 Beijing, Chongqing, Urumqi',
        'gmt_offset'    =>  8.00,
      ],
      [
        'id'            =>  30,
        'name'          =>  'Australia/Perth',
        'label'         =>  'UTC +08:00 Hong Kong SAR, Perth, Singapore, Taipei',
        'gmt_offset'    =>  8.00,
      ],
      [
        'id'            =>  31,
        'name'          =>  'Asia/Krasnoyarsk',
        'label'         =>  'UTC +08:00 Krasnoyarsk',
        'gmt_offset'    =>  8.00,
      ],
      [
        'id'            =>  32,
        'name'          =>  'Asia/Pyongyang',
        'label'         =>  'UTC +08:30 Pyongyang',
        'gmt_offset'    =>  8.50,
      ],
      [
        'id'            =>  33,
        'name'          =>  'Asia/Irkutsk',
        'label'         =>  'UTC +09:00 Irkutsk (Lake Baikal)',
        'gmt_offset'    =>  9.00,
      ],
      [
        'id'            =>  34,
        'name'          =>  'Asia/Tokyo',
        'label'         =>  'UTC +09:00 Tokyo, Osaka, Sapporo, Seoul',
        'gmt_offset'    =>  9.00,
      ],
      [
        'id'            =>  35,
        'name'          =>  'Australia/Adelaide',
        'label'         =>  'UTC +09:30 Adelaide',
        'gmt_offset'    =>  9.50,
      ],
      [
        'id'            =>  36,
        'name'          =>  'Australia/Darwin',
        'label'         =>  'UTC +09:30 Darwin',
        'gmt_offset'    =>  9.50,
      ],
      [
        'id'            =>  37,
        'name'          =>  'Australia/Brisbane',
        'label'         =>  'UTC +10:00 Brisbane',
        'gmt_offset'    =>  10.00,
      ],
      [
        'id'            =>  38,
        'name'          =>  'Pacific/Guam',
        'label'         =>  'UTC +10:00 Guam, Port Moresby',
        'gmt_offset'    =>  10.00,
      ],
      [
        'id'            =>  39,
        'name'          =>  'Australia/Sydney',
        'label'         =>  'UTC +10:00 Sydney, Melbourne',
        'gmt_offset'    =>  10.00,
      ],
      [
        'id'            =>  40,
        'name'          =>  'Asia/Yakutsk',
        'label'         =>  'UTC +10:00 Yakutsk (Lena River)',
        'gmt_offset'    =>  10.00,
      ],
      [
        'id'            =>  41,
        'name'          =>  'Australia/Hobart',
        'label'         =>  'UTC +11:00 Hobart',
        'gmt_offset'    =>  11.00,
      ],
      [
        'id'            =>  42,
        'name'          =>  'Asia/Vladivostok',
        'label'         =>  'UTC +11:00 Vladivostok',
        'gmt_offset'    =>  11.00,
      ],
      [
        'id'            =>  43,
        'name'          =>  'Pacific/Kwajalein',
        'label'         =>  'UTC +12:00 Eniwetok, Kwajalein',
        'gmt_offset'    =>  12.00,
      ],
      [
        'id'            =>  44,
        'name'          =>  'Pacific/Fiji',
        'label'         =>  'UTC +12:00 Fiji Islands, Marshall Islands',
        'gmt_offset'    =>  12.00,
      ],
      [
        'id'            =>  45,
        'name'          =>  'Asia/Kamchatka',
        'label'         =>  'UTC +12:00 Kamchatka',
        'gmt_offset'    =>  12.00,
      ],
      [
        'id'            =>  46,
        'name'          =>  'Asia/Magadan',
        'label'         =>  'UTC +12:00 Magadan, Solomon Islands, New Caledonia',
        'gmt_offset'    =>  12.00,
      ],
      [
        'id'            =>  47,
        'name'          =>  'Pacific/Auckland',
        'label'         =>  'UTC +12:00 Wellington, Auckland',
        'gmt_offset'    =>  12.00,
      ],
      [
        'id'            =>  48,
        'name'          =>  'Pacific/Apia',
        'label'         =>  'UTC +13:00 Apia (Samoa)',
        'gmt_offset'    =>  13.00,
      ],
      [
        'id'            =>  49,
        'name'          =>  'Atlantic/Azores',
        'label'         =>  'UTC -01:00 Azores, Cape Verde Island',
        'gmt_offset'    =>  -1.00,
      ],
      [
        'id'            =>  50,
        'name'          =>  'Atlantic/South_Georgia',
        'label'         =>  'UTC -02:00 Mid-Atlantic',
        'gmt_offset'    =>  -2.00,
      ],
      [
        'id'            =>  51,
        'name'          =>  'America/Buenos_Aires',
        'label'         =>  'UTC -03:00 E Argentina (BA, DF, SC, TF)',
        'gmt_offset'    =>  -3.00,
      ],
      [
        'id'            =>  52,
        'name'          =>  'America/Fortaleza',
        'label'         =>  'UTC -03:00 NE Brazil (MA, PI, CE, RN, PB)',
        'gmt_offset'    =>  -3.00,
      ],
      [
        'id'            =>  53,
        'name'          =>  'America/Recife',
        'label'         =>  'UTC -03:00 Pernambuco',
        'gmt_offset'    =>  -3.00,
      ],
      [
        'id'            =>  54,
        'name'          =>  'America/Sao_Paulo',
        'label'         =>  'UTC -03:00 S & SE Brazil (GO, DF, MG, ES, RJ, SP, PR, SC, RS)',
        'gmt_offset'    =>  -3.00,
      ],
      [
        'id'            =>  55,
        'name'          =>  'America/St_Johns',
        'label'         =>  'UTC -03:30 Newfoundland',
        'gmt_offset'    =>  -3.50,
      ],
      [
        'id'            =>  56,
        'name'          =>  'America/Halifax',
        'label'         =>  'UTC -04:00 Atlantic Time (Canada)',
        'gmt_offset'    =>  -4.00,
      ],
      [
        'id'            =>  57,
        'name'          =>  'America/La_Paz',
        'label'         =>  'UTC -04:00 La Paz',
        'gmt_offset'    =>  -4.00,
      ],
      [
        'id'            =>  58,
        'name'          =>  'America/Caracas',
        'label'         =>  'UTC -04:30 Caracas',
        'gmt_offset'    =>  -4.50,
      ],
      [
        'id'            =>  59,
        'name'          =>  'America/Bogota',
        'label'         =>  'UTC -05:00 Bogota, Lima',
        'gmt_offset'    =>  -5.00,
      ],
      [
        'id'            =>  60,
        'name'          =>  'America/New_York',
        'label'         =>  'UTC -05:00 Eastern Time (US & Canada)',
        'gmt_offset'    =>  -5.00,
      ],
      [
        'id'            =>  61,
        'name'          =>  'America/Indiana/Indianapolis',
        'label'         =>  'UTC -05:00 Eastern Time - Indiana - most locations',
        'gmt_offset'    =>  -5.00,
      ],
      [
        'id'            =>  62,
        'name'          =>  'America/Chicago',
        'label'         =>  'UTC -06:00 Central Time (US & Canada)',
        'gmt_offset'    =>  -6.00,
      ],
      [
        'id'            =>  63,
        'name'          =>  'America/Indiana/Knox',
        'label'         =>  'UTC -06:00 Eastern Time - Indiana - Starke County',
        'gmt_offset'    =>  -6.00,
      ],
      [
        'id'            =>  64,
        'name'          =>  'America/Mexico_City',
        'label'         =>  'UTC -06:00 Mexico City, Tegucigalpa',
        'gmt_offset'    =>  -6.00,
      ],
      [
        'id'            =>  65,
        'name'          =>  'America/Managua',
        'label'         =>  'UTC -06:00 Nicaragua',
        'gmt_offset'    =>  -6.00,
      ],
      [
        'id'            =>  66,
        'name'          =>  'America/Regina',
        'label'         =>  'UTC -06:00 Saskatchewan',
        'gmt_offset'    =>  -6.00,
      ],
      [
        'id'            =>  67,
        'name'          =>  'America/Phoenix',
        'label'         =>  'UTC -07:00 Arizona',
        'gmt_offset'    =>  -7.00,
      ],
      [
        'id'            =>  68,
        'name'          =>  'America/Denver',
        'label'         =>  'UTC -07:00 Mountain Time (US & Canada)',
        'gmt_offset'    =>  -7.00,
      ],
      [
        'id'            =>  69,
        'name'          =>  'America/Los_Angeles',
        'label'         =>  'UTC -08:00 Pacific Time (US & Canada); Los Angeles',
        'gmt_offset'    =>  -8.00,
      ],
      [
        'id'            =>  70,
        'name'          =>  'America/Tijuana',
        'label'         =>  'UTC -08:00 Pacific Time (US & Canada); Tijuana',
        'gmt_offset'    =>  -8.00,
      ],
      [
        'id'            =>  71,
        'name'          =>  'America/Nome',
        'label'         =>  'UTC -09:00 Alaska',
        'gmt_offset'    =>  -9.00,
      ],
      [
        'id'            =>  72,
        'name'          =>  'Pacific/Honolulu',
        'label'         =>  'UTC -10:00 Hawaii',
        'gmt_offset'    =>  -10.00,
      ],
      [
        'id'            =>  73,
        'name'          =>  'Pacific/Midway',
        'label'         =>  'UTC -11:00 Midway Island, Samoa',
        'gmt_offset'    =>  -11.00,
      ],
    ]);
  }
}
