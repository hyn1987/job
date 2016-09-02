<?php
  /**
   * Compoenent in Dashboard page.
   *
   * @author Ray
   * @since March 31, 2016
   * @version 1.0 show regional user stat
   */
?>



      <div class="portlet block purple-plum" id="stat_rt">
        <div class="portlet-title">
          <div class="caption">
            <i class="fa fa-dollar"></i>Recent Transactions
          </div>
          <div class="tools">
            <a href="" class="reload" title="Reload"></a>
          </div>
          <div class="actions">
            <div class="btn-group pull-right">
              <a class="btn btn-sm btn-default dropdown-toggle" href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
              Filter By <i class="fa fa-angle-down"></i>
              </a>
              <div class="dropdown-menu hold-on-click dropdown-checkboxes pull-right">
                <label><input type="checkbox"/> Finance</label>
                <label><input type="checkbox" checked=""/> Membership</label>
                <label><input type="checkbox"/> Customer Support</label>
                <label><input type="checkbox" checked=""/> HR</label>
                <label><input type="checkbox"/> System</label>
              </div>
            </div>
          </div>
        </div>
        <div class="portlet-body">
          <div class="scroller" style="height: 300px;">
            <ul class="feeds">
              <li>
                <div class="col1">
                  <div class="cont">
                    <div class="cont-col1">
                      <div class="label label-sm label-info">
                        <i class="fa fa-check"></i>
                      </div>
                    </div>
                    <div class="cont-col2">
                      <div class="desc">
                         You have 4 pending tasks. <span class="label label-sm label-warning ">
                        Take action <i class="fa fa-share"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col2">
                  <div class="date">
                     Just now
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="scroller-footer">
            <div class="btn-arrow-link pull-right">
              <a href="#">See All</a>
              <i class="icon-arrow-right"></i>
            </div>
          </div>
        </div>
      </div>