<?php use Wawjob\Timezone;

/* Mar 2, 2016 - paulz */
if ( !function_exists('ago') )
{
  function ago($datetime)
  {
    $diff = date_diff(date_create(), date_create($datetime));

    $format = '';
    if($diff->y !== 0) {
        $format = $diff->y." ".trans('common.unit_year').(($diff->y>1)? trans('common.unit_plural') : trans('common.unit_timespace'));
    } else if($diff->m !== 0) {
        $format = $diff->m." ".trans('common.unit_month').(($diff->m>1)? trans('common.unit_plural') : trans('common.unit_timespace'));
    } else if($diff->d !== 0) {
        $format .= $diff->d." ".trans('common.unit_day').(($diff->d>1)? trans('common.unit_plural') : "");
    } else if($diff->h !== 0) {
        $format .= $diff->h." ".trans('common.unit_hour').(($diff->h>1)? trans('common.unit_plural') : "");
    } else if($diff->i !== 0) {
        $format .= $diff->i." ".trans('common.unit_minute').(($diff->i>1)? trans('common.unit_plural') : "");
    }

    if ($format == '') {
      $format = "1 ".trans('common.unit_minute');
    }

    return trim($format).trans('common.ago');
  }
}

/* Mar 4, 2016 - Ri Chol Min */
if ( !function_exists('getFormattedDate') )
{
  function getFormattedDate($date, $format = 'M j, Y')
  {
    $locale = App::getLocale();
    $formats = [
      'ch' => [
        'M j, Y' => "Y年n月j日",
        'M Y' => "Y年n月",
        'Y' => "Y年",
      ],

      'kp' => [
        'M j, Y' => "Y년 n월 j일",
        'M Y' => "Y년 n월",
        'Y' => "Y",
      ]
    ];
    
    if ($locale != "en") {
      if (isset($formats[$locale][$format])) {
        $format = $formats[$locale][$format];
      }
    }

    return date($format, strtotime($date));
  }
}

/* Mar 7, 2016 - Ri Chol Min */
if ( !function_exists('displayMsgTime') )
{
  function displayMsgTime($date)
  {
    $diff = date_diff(date_create(), date_create($date));
    if ( $diff->d !== 0) {
      return date('M j, Y', strtotime($date));
    }else{
      return date('H:i', strtotime($date));
    }
  }
}


/**
* Returns array of {start_date, end_date} from given date range string
*
* e.g:
*      Mar 3, 2016 - Apr 21, 2016 => [2016-03-03, 2016-04-21]
*      Mar 3, 2016 => [2016-03-03, 2016-03-03]
*
* @author paulz
* @created Mar 10, 2016
*/
if ( !function_exists('parseDateRange') ) {
  function parseDateRange($str, $delim = '-', $format = 'Y-m-d')
  {
    if ( !$str ) {
      return false;
    }

    $delim = ' '.$delim.' ';
    $parts = explode($delim, $str, 2);

    $nParts = count($parts);
    if ($nParts == 1) {
      $startDate = date($format, strtotime($parts[0]));
      $endDate = $startDate;
    } else if ($nParts == 2) {
      $startDate = date($format, strtotime($parts[0]));
      $endDate = date($format, strtotime($parts[1]));
    } else {
      return false;
    }

    return [$startDate, $endDate];
  }
}

if ( !function_exists('formatMinuteInterval') ) {
  /**
  * @author paulz
  * @created Mar 20, 2016
  *
  * @param integer $minutes: Total minutes
  * @param boolean $hr24: Use 24 hours mode
  * 60 mins => 01:00
  * 130 mins => 02:10
  */
  function formatMinuteInterval($minutes, $hr24 = true)
  {
    $h = intval($minutes / 60);
    $m = $minutes % 60;

    if ( !$hr24 && $h != 12 ) {
      $h = $h % 12;
    }

    $str = sprintf("%02d:%02d", $h, $m);

    return $str;
  }
}

if ( !function_exists('timezoneToString')) {
  /**
  * Returns formatted timezone 
  * e.g.: UTC +08:00 ($includePrefix = true) or "+08:00" ( $includePrefix = false)
  *
  * @author paulz
  * @created Mar 22, 2016
  */
  function timezoneToString($gmt_offset, $includePrefix = true)
  {
    if ($gmt_offset == 0) {
      return "UTC";
    }

    $mins = $gmt_offset * 60;
    $symbol = $mins > 0 ? '+' : '-';

    $fm = $symbol . formatMinuteInterval(abs($mins));
    if ( !$includePrefix ) {
      return $fm;
    }

    return "UTC " . $fm;
  }
}


if ( !function_exists('weekRange')) {
  /**
  * Calculates first and last date of given week (first = Monday, last = Sunday)
  * 
  * @author paulz
  * @created Mar 22, 2016
  *
  * -case1 (returns date range of the week from [year, week])
  * @param integer $year: year value
  * @param integer $week: any number between 1 ~ 53
  * @param string $format: "Y-m-d" by default
  *
  * -case2 (returns date range of the week which contains this date)
  * @param string $date: any date
  * @param string $format: "Y-m-d" by default
  *
  * -case3 (returns date range of the week which contains this timestamp)
  * @param integer $timestamp: any timestamp
  * @param string $format: "Y-m-d" by default
  *
  * @return array [$monday, $sunday]
  *   e.g.: when $year = 2016, $week = 3, returns ['2016-01-11', '2016-01-17']
  *
  * How to use:
  *
  *
  *  $range = weekRange(); // week of today in "Y-m-d"
  *  $range = weekRange("2016-03-25"); // week of this date
  *  $range = weekRange("2016-03-25", "Ymd"); // week of this date in this format
  *  $range = weekRange(1458637464); // week of this timestamp
  *  $range = weekRange(2016, 12); // 12th week of year 2016, (date format: Y-m-d by default)
  *  $range = weekRange(2016, 12, "Ymd"); // 12th week of year 2016 in format of "Ymd"
  * -------
  * Output:
  *      $range = array (
  *        0 => "2016-03-21 00:00:00", // Monday
  *        1 => "2016-03-27 23:59:59", // Sunday
  *      );
  */
  function weekRange()
  {
    $default_format = "Y-m-d";

    $args = func_get_args();

    if (count($args) == 0) {
      // calc current week range
      $date = "now";
      $case = "from_date";
      $format = $default_format;
    } else {
      if (is_numeric($args[0])) {
        // assume this is year or timestamp
        if ($args[0] < 0) {
          return false;
          
        } elseif ($args[0] < 999999) {
          // year, weeknum
          $case = "from_weeknum";

          $year = $args[0];
          $week = $args[1];

          $format = isset($args[2]) ? $args[2] : $default_format;
        } else {
          // timestamp
          $case = "from_timestamp";

          $date = "@".$args[0];
          $format = isset($args[1]) ? $args[1] : $default_format;
        }
      } else {
        // assume this is date
        $case = "from_date";
        $date = $args[0];
        $format = isset($args[1]) ? $args[1] : $default_format;
      }
    }

    if ($case == "from_weeknum") {
      $date = date_create($year."-01-01");
      $dayOfWeek = intval(date_format($date, "N")); // 1 ~ 7
      if ($dayOfWeek == 1) {
        // New year's day is Monday
        $dates = 7 * ($week - 1);
      } else {
        // New year's day is not Monday
        $dates = 7 * $week;
      }
      $date = date_add($date, date_interval_create_from_date_string("$dates days"));
    } else {
      $date = date_create($date);
    }

    // Now we should calculate Monday and Sunday
    $dayOfWeek = intval(date_format($date, "N")); // 1 ~ 7
    $dayOfWeek--;  // 0 ~ 6
    
    if ($dayOfWeek == 0) {
      $monday = $date;
    } else {
      $monday = date_sub($date, date_interval_create_from_date_string("$dayOfWeek days"));
    }

    $strMonday = date_format($monday, $format) ." 00:00:00";
    $sunday = date_add($monday, date_interval_create_from_date_string("6 days"));
    $strSunday = date_format($sunday, $format) ." 23:59:59";

    return [$strMonday, $strSunday];
  }
}

if ( !function_exists('weekNum')) {
  /**
  * Calculate week number of given date (ISO-8601 week number of year, weeks starting on Monday)
  *
  * @author paulz
  * @created Mar 22, 2016
  */
  function weekNum($strDate = '')
  {
    $date = date_create($strDate);
    $weekNum = intval(date_format($date, "W"));

    return $weekNum;
  }
}

if ( !function_exists('monthRange')) {
  /**
  * Calculates first and last date of given month
  * How to use:
  *
  *
  *  $range = monthRange(); // month of today in "Y-m-d"
  *  $range = monthRange("2016-03-25"); // month of this date
  *  $range = monthRange("2016-03-25", "Ymd"); // month of this date in this format
  *  $range = monthRange(1458637464); // month of this timestamp
  *  $range = monthRange(2016, 12); // 12th month of year 2016, (date format: Y-m-d by default)
  *  $range = monthRange(2016, 12, "Ymd"); // 12th month of year 2016 in format of "Ymd"
  * -------
  * Output:
  *      $range = array (
  *        0 => "2016-03-01 00:00:00",
  *        1 => "2016-03-31 23:59:59",
  *      );
  */
  function monthRange()
  {
    $default_format = "Y-m-d";

    $args = func_get_args();
    if (count($args) == 0) {
      // calc current month range
      $date = "now";
      $case = "from_date";
      $format = $default_format;
    } else {
      if (is_numeric($args[0])) {
        // assume this is year or timestamp
        if ($args[0] < 999999) {
          // year, month
          $case = "from_month";

          $year = $args[0];
          $month= $args[1];

          $format = isset($args[2]) ? $args[2] : $default_format;
        } else {
          // timestamp
          $case = "from_timestamp";

          $date = "@".$args[0];
          $format = isset($args[1]) ? $args[1] : $default_format;
        }
      } else {
        // assume this is date
        $case = "from_date";
        $date = $args[0];
        $format = isset($args[1]) ? $args[1] : $default_format;
      }
    }

    if ($case == "from_month") {
      $date = date_create($year."-{$month}-01");
    } else {
      $date = date_create($date);
      $date = date_create(date_format($date, "Y-m-01"));
    }



    $strFirst = date_format($date, $format) ." 00:00:00";
    $lastDate = date_add($date, date_interval_create_from_date_string("1 month"));
    $strLast  = date_format(date_add($lastDate, date_interval_create_from_date_string("-1 day")), $format) ." 23:59:59";

    return [$strFirst, $strLast];
  }
}


if ( !function_exists('yearRange')) {
  /**
  * Calculates first and last date of given month
  * How to use:
  *
  *
  *  $range = yearRange(); // this year in "Y-m-d"
  *  $range = yearRange("2016-03-25"); // year of this date
  *  $range = yearRange("2016-03-25", "Ymd"); // year of this date in this format
  *  $range = yearRange(1458637464); // year of this timestamp
  *  $range = yearRange(2016); // range of year 2016, (date format: Y-m-d by default)
  *  $range = yearRange(2016, "Ymd"); // range of year 2016 in format of "Ymd"
  * -------
  * Output:
  *      $range = array (
  *        0 => "2016-01-01 00:00:00", 
  *        1 => "2016-12-31 23:59:59", 
  *      );
  */
  function yearRange()
  {
    $default_format = "Y-m-d";

    $args = func_get_args();
    if (count($args) == 0) {
      // calc current month range
      $date = "now";
      $case = "from_date";
      $format = $default_format;
    } else {
      if (is_numeric($args[0])) {
        // assume this is year or timestamp
        if ($args[0] < 999999) {
          // year
          $case = "from_year";

          $year = $args[0];

          $format = isset($args[1]) ? $args[1] : $default_format;
        } else {
          // timestamp
          $case = "from_timestamp";

          $date = "@".$args[0];
          $format = isset($args[1]) ? $args[1] : $default_format;
        }
      } else {
        // assume this is date
        $case = "from_date";
        $date = $args[0];
        $format = isset($args[1]) ? $args[1] : $default_format;
      }
    }

    if ($case == "from_year") {
      $date = date_create($year."-01-01");
    } else {
      $date = date_create($date);
      $date = date_create(date_format($date, "Y-01-01"));
    }



    $strFirst = date_format($date, $format) ." 00:00:00";
    $lastDate = date_add($date, date_interval_create_from_date_string("1 year"));
    $strLast  = date_format(date_add($lastDate, date_interval_create_from_date_string("-1 day")), $format) ." 23:59:59";

    return [$strFirst, $strLast];
  }
}


if ( !function_exists('convertTz')) {
  /**
  * Converts datetime from  to other timezone
  *
  * @author paulz
  * @created Mar 23, 2016
  *
  * @param string $datetime: Date/time in any format
  * @param string $to_tz: Target timezone
  * @param string $from_tz: Source timezone name (e.g. Europe/Minsk)
  * @param string $format: Date/time format
  */
  function convertTz($datetime, $to_tz, $from_tz = 'UTC', $format = 'Y-m-d H:i:s')
  {
    if ( !$datetime || !$to_tz ) {
      return false;
    }

    if ($to_tz == $from_tz) {
      return $datetime;
    }

    $date = date_create($datetime, timezone_open($from_tz));
    date_timezone_set($date, timezone_open($to_tz));
    
    return date_format($date, $format);
  }
}


if ( !function_exists('getPeriodUnit')) {
  function getPeriodUnit($from, $to, $format) {
    //Week
    list($w_from, $w_to) = weekRange($from);
    $w_from = date_format(date_create($w_from), $format);
    $w_to   = date_format(date_create($w_to), $format);
    if ($w_from == $from && $w_to == $to ) {
      return "week";
    }

    //Month
    list($m_from, $m_to) = monthRange($from);
    $m_from = date_format(date_create($m_from), $format);
    $m_to   = date_format(date_create($m_to), $format);
    if ($m_from == $from && $m_to == $to ) {
      return "month";
    }

    //Year
    list($y_from, $y_to) = yearRange($from);
    $y_from = date_format(date_create($y_from), $format);
    $y_to   = date_format(date_create($y_to), $format);
    if ($y_from == $from && $y_to == $to ) {
      return "year";
    }

    return false;
  }
}