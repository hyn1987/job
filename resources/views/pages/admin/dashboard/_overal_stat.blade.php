<?php
  /**
   * Compoenent in Dashboard page.
   *
   * @author Ray
   * @since March 25, 2016
   * @version 1.0 show weekly stat
   */
?>

  <div class="row overal-stat">
    <!-- <h3 class="section">Overal Report</h3> -->
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
      @include('layouts.admin.stat_box', ['_inn_data' => [
        'jade roundd',
        'fa fa-users',
        $stat_users['total_active'],
        'Active Users',
        '#',
        'View More',
      ]])
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
      @include('layouts.admin.stat_box', ['_inn_data' => [
        'pink roundd',
        'fa fa-folder-open',
        $stat_jobs['total_open'],
        'Open Jobs',
        '#',
        'View More',
      ]])
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
      @include('layouts.admin.stat_box', ['_inn_data' => [
        'green roundd',
        'fa fa-money',
        $trans['pending'],
        'Pendings',
        '#',
        'View More',
      ]])
    </div>


    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
      @include('layouts.admin.stat_box', ['_inn_data' => [
        'brown roundd',
        'fa fa-exclamation-triangle',
        $tickets['unresolved'],
        'Unresolved Tickets',
        '#',
        'View More',
      ]])
    </div>
  </div>
