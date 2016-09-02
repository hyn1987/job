<?php
  /**
   * Compoenent in Dashboard page.
   *
   * @author Ray
   * @since April 1, 2016
   * @version 1.0 show regional user stat
   */
?>


          <div class="portlet block purple-wisteria" id="complete_stats">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-circle-o-notch"></i>General Stats
              </div>
              <div class="actions">
                <a href="javascript:;" class="btn btn-sm btn-default reload pull-right"><i class="fa fa-repeat"></i> Reload </a>
              </div>
            </div>
            <div class="portlet-body">
              <div class="row">

                <div class="col-sm-4">
                  <div class="easy-pie-chart">
                    <div class="number transactions" data-percent="0" ref="tr">
                      <span>0</span>%
                    </div>
                    <a class="title" href="#" style="visibility: hidden;"><span></span> <i class="icon-arrow-right"></i></a>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="easy-pie-chart">
                    <div class="number client_profile" data-percent="0" ref="cp">
                      <span>0</span>%
                    </div>
                    <a class="title" href="#" style="visibility: hidden;"><span></span> <i class="icon-arrow-right"></i>
                    </a>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="easy-pie-chart">
                    <div class="number buyer_profile" data-percent="0" ref="bp">
                      <span>0</span>%
                    </div>
                    <a class="title" href="#" style="visibility: hidden;"><span></span> <i class="icon-arrow-right"></i></a>
                  </div>
                </div>

              </div>
            </div>
          </div>