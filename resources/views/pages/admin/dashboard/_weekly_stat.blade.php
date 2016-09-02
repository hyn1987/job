<?php
  /**
   * Compoenent in Dashboard page.
   *
   * @author Ray
   * @since March 25, 2016
   * @version 1.0 show weekly stat
   */
?>

  <div class="row weekly-stat">
    <!-- <h3 class="section">Weekly Report</h3> -->
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
      @include('layouts.admin.stat_box', ['_inn_data' => [
        'blue-madison roundd',
        'fa fa-user',
        '0',
        'New Users',
        '#',
        'View More',
      ]])
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
      @include('layouts.admin.stat_box', ['_inn_data' => [
        'red-intense roundd',
        'fa fa-cubes',
        '0',
        'New Jobs',
        '#',
        'View More',
      ]])
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
      @include('layouts.admin.stat_box', ['_inn_data' => [
        'green-haze roundd',
        'fa fa-dollar',
        $trans['weekly'],
        'Transactions',
        '#',
        'View More',
      ]])
    </div>


    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
      @include('layouts.admin.stat_box', ['_inn_data' => [
        'purple-plum roundd',
        'fa fa-ticket',
        $tickets['weekly'],
        'Tickets',
        '#',
        'View More',
      ]])
    </div>
  </div>
