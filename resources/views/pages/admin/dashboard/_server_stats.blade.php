<?php
  /**
   * Compoenent in Dashboard page.
   *
   * @author Ray
   * @since March 31, 2016
   * @version 1.0 show regional user stat
   */
?>



					<div class="portlet block red-sunglo" id="server_stats">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bar-chart-o"></i>Server Stats
							</div>
							<div class="actions">
								<a href="javascript:;" class="btn btn-sm btn-default reload pull-right"><i class="fa fa-repeat"></i> Reload </a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-sm-4">
									<div class="sparkline-chart">
										<div class="number" id="sparkline_bar">
										</div>
										<a class="title" href="#" style="visibility: hidden;">Network <i class="icon-arrow-right"></i></a>
									</div>
								</div>
								<div class="margin-bottom-10 visible-sm">
								</div>
								<div class="col-sm-4">
									<div class="sparkline-chart">
										<div class="number" id="sparkline_bar2">
										</div>
										<a class="title" href="#" style="visibility: hidden;">CPU Load <i class="icon-arrow-right"></i></a>
									</div>
								</div>
								<div class="margin-bottom-10 visible-sm">
								</div>
								<div class="col-sm-4">
									<div class="sparkline-chart">
										<div class="number" id="sparkline_line">
										</div>
										<a class="title" href="#" style="visibility: hidden;">Load Rate <i class="icon-arrow-right"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>