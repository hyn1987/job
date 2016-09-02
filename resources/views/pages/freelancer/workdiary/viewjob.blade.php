<?php

use Wawjob\Project;
use Wawjob\Contract;
?>
@extends('layouts/freelancer/index')

@section('additional-css')
<link rel="stylesheet" href="{{ url('assets/plugins/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-datepicker/css/datepicker3.css') }}">
@endsection

@section('content')
<div class="row workdiary-section">
  <div class="col-sm-12">
    {{-- Contract Selector --}}
    {{ show_messages() }}

    @if ($contract)
    <div class="row-contract">
      @if ($contract->isHourly() && ($contract->isOpen() || $contract->isPaused()) )
        @include('pages.freelancer.workdiary.modal.contract_selector')
      @else
        <div class="contract-title">
          <span>{{ $contract->contractor->fullname() }} - {{ $contract->title }}</span>
          <span class="contract-status">
            @if ($contract->isClosed())
              [{{ trans('contract.closed') }}]
            @endif
          </span>
        </div>
        <div class="contract-term">
          {{ date("M d, Y", strtotime($contract->started_at)) }} - 
          @if ($contract->isClosed())
            {{ date("M d, Y", strtotime($contract->ended_at)) }}
          @else
            {{ trans('common.present') }}
          @endif
        </div>
      @endif
    </div><!-- END OF .row-contract -->

    @if ($contract->isHourly())
      <div class="row row-toolbar">
        {{-- Timezone --}}
        <div class="col-md-4 form-group col-timezone clearfix">
          <label class="control-label pull-left">{{ trans('common.timezone') }}</label>
          <div class="col-sm-7">
            <select name="wtimezone" class="form-control wtimezone">
              <option value="UTC">UTC</option>
              @foreach ($options['tz'] as $label => $v)
              <option value="{{ $v }}"{{ $v == $meta['tz'] ? ' selected' : ''}}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Date --}}
        <div class="col-md-4 form-group col-calendar text-center">
          <a href="{{ $meta['dateUrls']['prev'] }}" class="date-nav prev-date"><span class="fa fa-arrow-left"></span></a>
          <span data-cid="{{ $contract['id'] }}" data-date="{{ $meta['wdate'] }}" class="current-date">{{ date("D, M d, Y", strtotime($meta['wdate'])) }}</span>
          <span class="pointer date-picker"><i class="fa fa-calendar"></i></span>
          <a href="{{ $meta['dateUrls']['next'] }}" class="date-nav next-date"><span class="fa fa-arrow-right"></span></a>

          <a href="{{ $meta['dateUrls']['today'] }}" class="goto-today strong">{{ trans('common.today') }}</a>
        </div>

        {{-- View mode --}}
      <div class="col-md-4 form-group col-viewmode text-right">
        <div class="btn-group btn-group-solid btn-group-viewmode">
          <button data-mode="grid" type="button" class="btn btn-mode{{ $meta['mode'] == 'grid' ? ' active' : ''}}"><i class="fa fa-th"></i></button>
          <button data-mode="list" type="button" class="btn btn-mode{{ $meta['mode'] == 'list' ? ' active' : ''}}"><i class="fa fa-list"></i></button>
        </div>
      </div>
      </div>  {{-- .row-toolbar --}}

      <div class="row row-logmeta">
        <div class="col-md-12">
          <div class="info-group info-total-time">
            <span>{{ trans('workdiary.total_time_logged') }}:</span> <span class="total-time">{{ $meta['time']['total'] }}</span>
          </div>
          <div class="info-group">
            <span class="rect rect-auto"></span><span>{{ trans('workdiary.auto_tracked') }}:</span> <span class="auto-time">{{ $meta['time']['auto'] }}</span>
          </div>
          <div class="info-group">
            <span class="rect rect-manual"></span><span>{{ trans('workdiary.manual_time') }}:</span> <span class="manual-time">{{ $meta['time']['manual'] }}</span>
          </div>
          <div class="info-group">
            <span class="rect rect-overlimit"></span><span>{{ trans('workdiary.overlimit') }}:</span> <span class="overlimit-time">{{ $meta['time']['overlimit'] }}</span>
          </div>
          <div class="info-group pull-right">
            <span class="rect rect-select"></span>
            <span>{{ trans('workdiary.selected') }}: </span><strong><span class="selected-time">00:00</span> min</strong>
          </div>
        </div>
      </div>

      @if ($is_this_week)
      <div class="row row-action">
        <div class="col-md-12">
          @if ($diary)
          <div class="btn-group pull-right">
            <a href="javascript:void(0);" class="action-btn btn require-slots disabled" id="deselectAll" name="deselectall">{{ trans('workdiary.deselect_all') }}</a>
          </div>
          <div class="btn-group pull-right">
            <a href="javascript:void(0);" class="action-btn btn require-slots disabled" id="delete" name="delete">{{ trans('workdiary.delete') }}</a>
          </div>
          <div class="btn-group pull-right">
            <a href="javascript:void(0);" class="action-btn btn require-slots disabled" id="editMemo" name="editmemo">{{ trans('workdiary.edit_memo') }}</a>
          </div>
          @endif

          @if ($contract->is_allowed_manual_time)
          <div class="btn-group pull-right">
            <a class="action-btn btn" data-target="#addManualModal" data-toggle="modal" id="addManual" name="addmanual">{{ trans('workdiary.add_manual_time') }}</a>
          </div>
          @endif
        </div>
      </div>
      @endif

      <div class="row-screenshots">
      @if ($diary)
        {{-- Grid mode --}}
        <div class="pane-grid"@if ($meta['mode'] == 'list') style="display: none;"@endif>
          @foreach($diary as $hr => $group)
          <div class="row-hour clearfix">
            <div class="ss-hour">
              <span class="num">{{ $group['label']['hour'] }}</span>
              <div class="ampm">{{ $group['label']['ampm'] }}</div>
              <div class="select-box"><input type="checkbox" class="selectable-box select-hour"></div>
            </div>
            <div class="ss-col-right">
              @foreach($group['seg'] as $seg)
              <div class="seg{{ $seg['start'] ? ' start' : ''}}{{ $seg['end'] ? ' end' : ''}} from{{ $seg['from'] }} to{{ $seg['to'] }}{{ $seg['is_manual'] ? ' manual' : ''}}{{ $seg['is_overlimit'] ? ' overlimit' : ''}}">{{ $seg['comment'] ?: 'No memo' }}</div>
              @endforeach

              <ul class="slots">
                @for ($si = 0; $si < $meta["maxSlot"]; $si++)
                <?php $slot = $group['slots'][$si]; ?>
                <li class="slot clearfix{{ $slot['is_empty'] == false && $slot['is_overlimit'] ? ' overlimit' : ''}}" data-comment="{{ isset($slot['comment']) ? $slot['comment'] : '' }}">
                  @if ($slot['is_empty'])
                  <div class="pic no-pic"></div>
                  @elseif ($slot['is_manual'])
                  <div class="pic manual"></div>
                  @else
                  <div class="pic{{ isset($slot['link']) ? ' has-pic' : '' }}">
                    <a href="{{ $slot['link']['full'] }}" target="_blank" class="link-full">
                      <!-- <div class="i-loading"> -->
                        <img class="ss" src="{{ $slot['link']['thumbnail'] }}" title="{{ $slot['active_window'] }}">
                      <!-- </div> -->
                    </a>
                  </div>
                  <div class="score" title="{{ trans('workdiary.score') }}: {{ $slot['score'] }}">
                    <div class="grey"></div>
                    <div class="green" style="height: {{ $slot['score'] * 11 }}px"></div>
                    <div class="borders">
                    @for ($bi = 0; $bi < 10; $bi++)<div class="border-block"></div>@endfor
                    </div>
                    <a data-id="{{ $slot['id'] }}" href="#modalSlot" data-toggle="modal" data-backdrop="static" class="a-slot"></a>
                  </div>
                  @endif

                  <div class="info">
                  @if ( !$slot['is_empty'] )
                  <span class="select-box margin-right-10"><input type="checkbox" class="selectable-box select-slot" data-id="{{ $slot['id'] }}"></span>
                  @endif
                  {{ $group["slots"][$si]["timeLabel"] }}</div>
                </li>
                @endfor
              </ul>
            </div>
          </div>
          @endforeach
        </div>

        {{-- List mode --}}
        <div class="pane-list"@if ($meta['mode'] == "grid") style="display: none;"@endif>
          @foreach($diary as $hr => $group)
          <div class="row-hour clearfix">
            <div class="ss-hour">
              <span class="num">{{ $group['label']['hour'] }}</span>
              <div class="ampm">{{ $group['label']['ampm'] }}</div>
              <div class="select-box"><input type="checkbox" class="selectable-box select-hour"></div>
            </div>
            <div class="ss-col-right">
              <ul class="list-slots">
              @for ($si = 0; $si < $meta["maxSlot"]; $si++)
                <?php $slot = $group['slots'][$si]; ?>
                <li class="slot{{ $slot['is_empty'] == false && $slot['is_overlimit'] ? ' overlimit' : ''}}{{ $slot['is_empty'] == false && $slot['is_manual'] ? ' manual' : ''}}" data-comment="{{ isset($slot['comment']) ? $slot['comment'] : '' }}">
                  @if (isset($slot['link']))
                  <div class="pic" style="display: none;">
                    <a href="{{ $slot['link']['full'] }}" target="_blank" class="link-full">
                      <img class="ss" src="{{ $slot['link']['thumbnail'] }}">
                    </a>
                  </div>
                  @endif
                  @if ( !$slot['is_empty'] )
                  <span class="select-box margin-right-10"><input type="checkbox" class="selectable-box select-slot" data-id="{{ $slot['id'] }}"></span>
                  @else
                  <span class="margin-right-20">&nbsp;</span>
                  @endif
                  <span class="info">{{ $group["slots"][$si]["timeLabel"] }}</span>
                  @if ( !$slot['is_empty'] )
                  <span class="score iblock" title="{{ trans('workdiary.score') }}: {{ $slot['score'] }}">
                    <div class="grey"></div>
                    @if ( !$slot['is_manual'] )
                    <div class="green" style="width: {{ $slot['score'] * 10 }}%"></div>
                    @endif
                    <div class="borders">
                    @for ($bi = 0; $bi < 10; $bi++)<span class="border-block"></span>@endfor
                    </div>
                    @if ( !$slot['is_manual'] )
                    <a data-id="{{ $slot['id'] }}" href="#modalSlot" data-toggle="modal" data-backdrop="static" class="a-slot"></a>
                    @endif
                  </span>
                  <span class="comment">{{ $slot['is_empty'] ? '' : ($slot['comment'] != '' ? $slot['comment'] : 'No memo') }}</span>
                  @if ( !$slot['is_manual'] )
                  <span class="active-window-wrp">[<span class="active-window">{{ $slot['active_window'] }}</span>]</span>
                  @endif
                  @endif
                </li>
              @endfor
              </ul>
            </div>
          </div>
          @endforeach
        </div>
        
      @else
        <div class="no-items">{{ trans('workdiary.no_work_diary_logged') }}</div>
      @endif
      </div>
    @else
    <div class="row-screenshots">
      <div class="no-items">{{ trans('contract.it_is_fixed_job') }}</div>
    </div>
    @endif

    @else
    <p>{{ trans('workdiary.no_active_hourly_jobs') }}</p>

    @endif
  </div>

</div>

@include('pages.freelancer.workdiary.modal.edit_memo')
@include('pages.freelancer.workdiary.modal.add_manual')
@include('pages.freelancer.workdiary.modal.slot')

@endsection