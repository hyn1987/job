<?php
/**
 * Profile Page (user/profile/2)
 *
 * @author  - sogwang
 */
?>
{{--
 * User profile page
--}}
@extends('layouts/freelancer/index')

@section('content')
  <div class="page-header">
	  <h3><span class="title">{{ trans('page.' . $page . '.title') }}</span></h3>
  </div>

  <div class="page-content-section freelancer-user-page">
    <div class="row">
      <div class="col-sm-9">
        <div class="row summary">
          <div class="col-sm-3 photo-side">
            <img src="{{ avatarUrl($user) }}" alt="{{ $user->fullname() }}" class="img-circle img-user-avatar" width="150" height="150">
          </div>

          <div class="col-sm-9">
            <div class="row name">
              <div class="col-sm-6 pull-left">
                {{ $user->contact->first_name }} {{ $user->contact->last_name }}
              </div>
              <div class="col-sm-6 right-align">
                ${{ $user->profile->rate }}/{{ trans('common.hr')}}
              </div>
            </div>

            <ul class="items">
              <li class="skill">{{ $user->profile->title }}</li>
              <li class="country">{{ $user->contact->city ? $user->contact->city . ', ' : ''}}{{ $user->contact->country->name }}</li>
              <li class="timezone">{{ $user->contact->timezone ? $user->contact->timezone->label : '' }}</li>
              <li class="skill-button">
                @foreach ($user->skills as $skill)
                  <span class="label label-default">{{ parse_multilang($skill->name, App::getLocale()) }} &nbsp;&nbsp;
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                  </span>&nbsp;&nbsp;
                @endforeach
              </li>
            </ul>
          </div>                
        </div>

        <div class="left-detail">
          <div class="overview">
            <h4>{{ trans('profile.overview') }}</h4>
            <div class="profile-desc">
            {!! ($user->profile && $user->profile->desc != null)? str_replace(array("\r\n", "\r", "\n"), "<br />", $user->profile->desc) : "&nbsp;" !!}
            </div>
          </div>

          {{-- Begin Work History and feedback --}}
          <div class="block">
            <div class="row block-header">
              <div class="col-sm-9">
                <h4>{{ trans('profile.work_history_and_feedback') }}</h4>
              </div>
              <div class="col-sm-3" style="display:none">
                <select class="form-control" name="options">
                  <option value="">OP1</option>
                  <option value="">OP2</option>
                  <option value="">OP3</option>
                </select> 
              </div>
            </div>

            {{--  begin content  --}}                
            <div class="block-content">
            @if ( !$user->freelancerContracts->isEmpty() )
              @foreach ($user->freelancerContracts as $contract)
                <div class="row content">
                  <div class="col-sm-8">
                    <ul class="items">
                      <li class=""> 
                      {{ getFormattedDate($contract->started_at, 'M Y') }} - 
                      {{ $contract->ended_at > '0000-00-00 00:00:00' ? getFormattedDate($contract->ended_at, 'M Y') : trans('common.present') }} 
                      </li>
                      <li class="">Web Developer</li>
                      @if ( $contract->feedback )
                      <li class="">
                         {!! $contract->feedback->freelancer_score > 0 ? '<span class="score">' . $contract->feedback->freelancer_score . '</span>' : '<i>' . trans('profile.message.No_Feedback_Yet') . '</i>' !!}
                        @if ($contract->feedback->freelancer_score > 0)
                          @for ($sc = 1; $sc <= 5; $sc += 1)
                          <input name="cont_rate_{{$contract->id}}" type="radio" class="rate" value="{{ $sc }}"
                          {{ $contract->feedback->freelancer_score == $sc ? ' checked' : ''}}>
                          @endfor
                        @endif
                      </li>
                      <li class="desc">
                        <span> {{ $contract->feedback->freelancer_feedback }} </span>
                      </li>
                      @else
                        Working...
                      @endif                    
                    </ul>                       
                  </div>

                  <div class="col-sm-4">
                    <ul class="items right-align">
                      <li><span>{{ trans('profile.hours', ['hours' => isset($contract->meter->total_mins) ? intval($contract->meter->total_mins/60) : 0]) }}</span></li>
                      <li> 
                      @if ( $contract->isHourly() )
                        <span>{{ trans('profile.hourly_rate', ['rate' => $contract->price]) }}</span>
                      @else
                        <span>${{ $contract->price }}</span>
                      @endif
                      </li>
                      <li><span>{{ trans('profile.earned', ['amount' => isset($contract->meter->total_amount) ? $contract->meter->total_amount : 0]) }}</span></li>
                    </ul>
                  </div>                      
                </div>  
              @endforeach           
            @else
              <span class="alert-no-found">{{ trans('profile.message.No_Found_Work_History_and_Feedback') }}</span>
            @endif
            </div>
            {{--  end content  --}}
          </div>
          {{-- End Work History and feedback --}}

          {{-- Begin Portfolio Section --}}
          <div class="block">
            <div class="row block-header">
              <div class="col-sm-6">
                <h4>{{ trans('profile.portfolio') }}</h4>
              </div>
              <div class="col-sm-6" style="display:none">
                <select class="form-control" name="portfolio_cats" id="portfolio_cats">
                  <option value="">Filter By Category</option>
                  @foreach ($categories as $category)
                  <option value="{{$category->id}}">{{parse_multilang($category->name, App::getLocale())}}</option>
                  @endforeach
                </select> 
              </div>
            </div>

            {{--  begin content  --}}                
            <div class="block-content">
            @if(!$portfolio_list->isEmpty())
              <div class="row content">
                @foreach ($portfolio_list as $portfolio)
                  <div class="col-sm-6 portfolio-item category{{$portfolio->cat_id}}">
                    <div class="portfolio-img">
                      @if ($portfolio->url != "")<a href="{{$portfolio->url}}">@endif
                      <img src="{{portfolioUrl($focus_user, $portfolio->id)}}" alt="" class="img-portfolio" width="400px" height="300px">
                      @if ($portfolio->url != "")</a>@endif
                    </div> 
                    <div class="portfolio-title">
                      <span>{{$portfolio->title}}</span>
                    </div>                       
                  </div>
                @endforeach                   
              </div>        
             @else
                  <span class="alert-no-found">{{ trans('profile.message.No_Found_Fortfolio') }}</span>
               @endif       
            </div>
            {{--  end content  --}}
          </div>
          {{-- End Portfolio Section --}}

          {{-- Begin Certifications Section --}}
          <div class="block" style="display: none;">
            <div class="row block-header">
              <div class="col-sm-9">
                <h4>@TODO: Certifications</h4>
              </div>                    
            </div>

            {{--  begin content  --}}                
            <div class="block-content ">
            </div>
            {{--  end content  --}}
          </div>
          {{-- End Certifications Section --}}

          {{-- Begin Tests Section --}}
          <div class="block" style="display: none;">
            <div class="row block-header">
              <div class="col-sm-9">
                <h4>@TODO: Tests</h4>
              </div>                    
            </div>

            {{--  begin content  --}}                
            <div class="block-content ">
              <ul class="table">
                <li class="table-header">
                  <div class="row">
                    <div class="col-sm-4">Name</div>
                    <div class="col-sm-3">Score (out of 5)</div>
                    <div class="col-sm-2">Time to Complete</div>
                    <div class="col-sm-3">
                      <span class="pull-right">Display on Profile</span>
                    </div>
                  </div>             
                </li>
                <li class="table-row">
                  <div class="row">
                    <div class="col-sm-4">Xml 1.0 Test</div>
                    <div class="col-sm-3">
                      <span>5.00</span>
                    </div>
                    <div class="col-sm-2">10 mins</div>
                    <div class="col-sm-3">
                      <div id="btn-group" class="btn-group pull-right" role="group" aria-label="...">
                        <button type="button" class="btn btn-custom active">Yes</button>
                        <button type="button" class="btn btn-custom">No</button>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="table-row">
                  <div class="row">
                    <div class="col-sm-4">Xml 1.0 Test</div>
                    <div class="col-sm-3">
                      <span>5.00</span>
                    </div>
                    <div class="col-sm-2">10 mins</div>
                    <div class="col-sm-3">
                      <div id="btn-group" class="btn-group pull-right" role="group" aria-label="...">
                        <button type="button" class="btn btn-custom active">Yes</button>
                        <button type="button" class="btn btn-custom">No</button>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
            {{--  end content  --}}
          </div>
          {{-- End Tests Section --}}   

          {{-- Begin Employment History Section --}}
          <div class="block">
            <div class="row block-header">
              <div class="col-sm-9">
                <h4>{{ trans('profile.employment_history') }}</h4>
              </div>                    
            </div>

            {{--  begin content  --}}                
            <div class="block-content ">
            @if (!$user->employments->isEmpty())
              @foreach ( $user->employments as $employment )
              <div class="cont">
                <div class="title">
                  <span>{{ $employment->position }} </span>
                  <span>{{ $employment->company }} </span>
                </div>
                <div class="duration">
                  {{ getFormattedDate($employment->from, 'M Y') }} 
                  &nbsp - &nbsp 
                  {{ getFormattedDate($employment->to, 'M Y') }} 
                </div>
              </div>
              @endforeach   
            @else
              <span class="alert-no-found">{{ trans('profile.message.No_Found_Employment_History') }}</span>
            @endif                  
            </div>
            {{--  end content  --}}
          </div>
          {{-- End Employment History Section --}}
          {{-- Begin Education Section --}}
          <div class="block">
            <div class="row block-header">
              <div class="col-sm-9">
                <h4>{{ trans('profile.education') }}</h4>
              </div>                    
            </div>

            {{--  begin content  --}}                
            <div class="block-content ">
            @if (!$user->educations->isEmpty())
              @foreach ( $user->educations as $education )
              <div class="cont">
                <div class="row">                      
                <div class="col-sm-12 title">
                  <span>{{ $education->major }}</span>
                  <span>{{ $education->minor }}</span>
                  <span>{{ $education->school }}</span>
                </div>
                <div class="col-sm-12 duration">
                  {{ date('Y', strtotime( $employment->from)) }} 
                  &nbsp - &nbsp 
                  {{ date('Y', strtotime( $employment->to)) }}
                </div>
                </div>
              </div>
              @endforeach
            @else
              <span class="alert-no-found">{{ trans('profile.message.No_Found_Education_History') }}</span>
            @endif
            </div>
            {{--  end content  --}}
          </div>
          {{-- End Education Section --}}        
        </div>
      </div>

      <div class="col-sm-3">
        @if ($current_user && $current_user->isBuyer())
        <div class="btn-section border-bottom">
          <span>
            <a href="{{ route('job.invite', ['uid'=>$user->id]) }}" id="send_btn" class="btn btn-primary btn-wide">{{ trans('profile.invite_to_job') }}</a>
          </span>
          <!-- <span>
            <a href="{{ route('job.make_offer', ['id'=>1, 'uid'=>$user->id]) }}" id="send_btn" class="btn btn-primary btn-wide">Make Offer</a>
          </span> -->
        </div>
        @endif
      
        {{--  begin right side content  --}}
        <div class="right-side">
          <div class="right-side-block">
            <div class="right-side-block-header">
              <h4>{{ trans('profile.work_history') }}</h4>
            </div>
            <div class="right-side-block-content">
              <p>
                <span class="freelancer-total-score">{!! $user->sc > 0 ? $user->sc : '<i>' . trans('profile.message.No_Feedback_Yet') . '</i>' !!}</span>
                @if ($user->sc > 0)
                @for ($sc = 0.25; $sc <= 5.00; $sc += 0.25)
                <input name="rate" type="radio" class="rate {split:4}" value="{{ $sc }}" {{ $user->sc >= $sc && $user->sc < $sc + 0.25 ? ' checked' : ''}}>
                @endfor
                @endif
              </p>
              <p>@TODO: TOP RATED</p>
              <p>
                <div class="progress" style="border: solid 1px #4AC1DC;">
                  <div class="progress-bar progress-bar-info" style="width: 95%">
                    <span class="">{{ trans('profile.success_percent', ['n' => $success_percent]) }}</span>
                  </div>
                </div>
              </p>
              <?php $uh = $user->howManyHours('total');
              $uj = $user->howManyJobs(); ?>
              <p>{{ App::getLocale() == "en" ? $uh.' '.str_plural('hour', $uh).' worked' : trans('profile.n_hours_worked', ['n' => $uh]) }}</p>
              <p>{{ App::getLocale() == "en" ? $uj.' '.str_plural('job', $uh) : trans('profile.n_jobs', ['n' => $uj]) }}</p>
            </div>
          </div>

          <div class="right-side-block">
            <div class="right-side-block-header">
              <h4>{{ trans('profile.availability') }}</h4>
            </div>
            <div class="right-side-block-content">
              <p>{{ $user->profile->toString() }}</p>
              <p>@TODO: 24 hours response time</p>
            </div>
          </div>

          <div class="right-side-block">
            <div class="right-side-block-header">
              <h4>{{ trans('profile.language') }}</h4>
              <span>{{ implode(', ', $user->getLanguageList()) }}</span>
            </div>
          </div>

          <div class="right-side-block">
            <div class="right-side-block-header">
              <h4>{{ trans('profile.verifications') }}</h4>
            </div>
          </div>

          <div class="right-side-block" style="display:none">
            <div class="right-side-block-header">
              <h4>Associated With</h4>  
            </div>
            
            <div class="row item-seperator">
              <div class="col-sm-4">
                <img src="/assets/images/tmp/logo/blue.png" alt="" class="img-circle" width="80px" height="80px">
              </div>
              <div class="col-sm-8">i4uSoft Corp</div>
            </div>
          </div>
          
          <div class="right-side-block">
            <div class="right-side-block-header">
              <h4>{{ trans('profile.group') }}</h4>
            </div>
            <div class="row item-seperator">
              <div class="col-sm-4">
                <img src="/assets/images/tmp/logo/cisco.png" alt="" class="img-circle" width="80px" height="80px">
              </div>
              <div class="col-sm-8">
                Cisco
              </div>
            </div>
            <div class="row item-seperator">
              <div class="col-sm-4">
                <img src="/assets/images/tmp/logo/spinwokrx.png" alt="" class="img-circle" width="80px" height="80px">
              </div>
              <div class="col-sm-8">
                Magento
              </div>
            </div>
            <div class="row item-seperator">
              <div class="col-sm-4">
                <img src="/assets/images/tmp/logo/vimeo.png" alt="" class="img-circle" width="80px" height="80px">
              </div>
              <div class="col-sm-8">
                PhpFox
              </div>
            </div>
          </div>              
        </div>
        {{--  end right side content  --}}        
      </div>      
    </div>
  </div>
@endsection