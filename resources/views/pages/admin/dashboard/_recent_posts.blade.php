<?php
  /**
   * Compoenent in Dashboard page.
   *
   * @author Ray
   * @since March 31, 2016
   * @version 1.0 show regional user stat
   */
?>


      <div class="portlet block green-haze" id="stat_rp">
        <div class="portlet-title">
          <div class="caption">
            <i class="fa fa-rss"></i>Recent Job Posts
          </div>
          <div class="tools">
            <a href="" class="reload" title="Reload"></a>
          </div>
          <div class="actions">
            <div class="btn-group pull-right">
              <a class="btn btn-sm btn-default dropdown-toggle" href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
              Filter By <i class="fa fa-angle-down"></i>
              </a>
              <div class="dropdown-menu hold-on-click dropdown-checkboxes pull-right pr-cats-list">
                <div class="btn-toolbar">
                  <label class="btn-like btn-xs pull-left no-padding" style=""><input type="checkbox" checked="true" class="check-all"/> Check All</label>
                  <a class="btn btn-default btn-xs btn-apply pull-right reload" href="#"><i class="fa fa-refresh"></i>Apply</a>
                  <!--
                  <a class="btn btn-default btn-xs btn-apply pull-right checkNone" href="#"><i class="fa fa-square-o"></i>Uncheck All</a>
                  <a class="btn btn-default btn-xs btn-apply pull-right checkAll" href="#"><i class="fa fa-check-square-o"></i>Check All</a>
                  -->
                </div>
                @forelse ($pr_cats as $ind => $p)
                <label><input type="checkbox" checked="true" class="pr_cat" ref="{{ $p['id'] }}" /> {{ $p['name'] }}</label>
                @empty
                <label>No category available</label>
                @endforelse
              </div>
            </div>
          </div>
        </div>
        <div class="portlet-body">
          <div class="scroller" style="height: 300px;">
            <!-- ======================================== -->
            <!-- Model of li -->
            <!-- ======================================== -->
              <li class="__model">
                <div class="col1">
                  <div class="cont">
                    <div class="cont-col1">
                      <label class="label label-sm label-info">
                        <img class="avatar img-circle for-collapse" width="16px" height="16px" src="#">
                      </label>
                    </div>
                    <div class="cont-col2">
                      <div class="desc">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col2">
                  <div class="date">
                  </div>
                </div>
              </li>
            <!-- ======================================== -->
            <ul class="feeds">
            </ul>
          </div>
          <div class="scroller-footer">
            <div class="btn-arrow-link pull-right">
              <a href="{{ route('admin.job.list') }}">See All</a>
              <i class="icon-arrow-right"></i>
            </div>
          </div>
        </div>
      </div>