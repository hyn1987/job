<?php
  /**
   * Compoenent in Dashboard page.
   *
   * @author Ray
   * @since March 30, 2016
   * @version 1.0 show regional user stat
   */
?>

      <div class="portlet block jade" id="region_stat_user">
        <div class="portlet-title">
          <div class="caption">
            <i class="fa fa-globe"></i>Regional User Stats
          </div>
          <div class="tools">
            <a href="" class="reload" title="Reload"></a>
          </div>
        </div>
        <div class="portlet-body">

          <div class="region_statistics_content">
            <div class="btn-toolbar margin-bottom-10 open-on-hover">
              
              <!--
              <div class="btn-group pull-right">
                <a href="" class="btn default btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Region <span class="fa fa-angle-down"></span></a>
                <ul class="dropdown-menu pull-right filter-menu">
                  <li class=" active"><a href="javascript:;" class="stat_vmap_region" ref="world">World</a></li>
                  <li><a href="javascript:;" class="stat_vmap_region" ref="usa">USA</a></li>
                  <li><a href="javascript:;" class="stat_vmap_region" ref="europe">Europe</a></li>
                  <li><a href="javascript:;" class="stat_vmap_region" ref="russia">Russia</a></li>
                  <li><a href="javascript:;" class="stat_vmap_region" ref="germany">Germany</a></li>
                </ul>
              </div>
              -->

              <div class="btn-group pull-right btn-group-userstatus">
                <a href="" class="btn default btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Status <span class="fa fa-angle-down"></span></a>
                <ul class="dropdown-menu pull-right filter-menu">
                  <li class="active"><a href="javascript:;" class="stat_vmap_status stat_userstatus_all" ref="">All</a></li>
                  @foreach ($userStatusList as $status)
                  <li><a href="javascript:;" class="stat_vmap_status stat_userstatus_{{ $status }}" ref="{{ $status }}">{{ trans('common.user.status.' . $status) }}</a></li>
                  @endforeach
                </ul>
              </div>

              <div class="btn-group pull-right btn-group-usertype">
                <a href="" class="btn default btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">Type <span class="fa fa-angle-down"></span></a>
                <ul class="dropdown-menu pull-right filter-menu">
                  <li class=" active"><a href="javascript:;" class="stat_vmap_type stat_usertype_all" ref="">All</a></li>
                  @foreach ($userTypeList as $utype)
                  <li><a href="javascript:;" class="stat_vmap_type stat_usertype_{{ $utype->id }}" ref="{{ $utype->id }}">{{ trans('common.user.types.' . $utype->slug) }}</a></li>
                  @endforeach
                </ul>
              </div>
              

            </div>

            <div class="vmaps vmap_world"></div>
            <div class="vmaps vmap_usa hide"></div>
            <div class="vmaps vmap_europe hide"></div>
            <div class="vmaps vmap_russia hide"></div>
            <div class="vmaps vmap_germany hide"></div>

          </div>
        </div>
      </div>