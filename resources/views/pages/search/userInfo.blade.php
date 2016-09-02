<div class="row-user clearfix">
  <div class="col-user-avatar">
    <a href="{{ route('user.profile', [$user->id]) }}"><img alt="{{ $user->fullname() }}" class="user-avatar img-circle hide1" src="{{ avatarUrl($user) }}" width="100" height="100"></a>
  </div> 

  <div class="col-user-detail">
    <div class="row">
      <div class="col-md-9 margin-bottom-10px"> 
        <h4 class="title">
          <a href="{{ route('user.profile', [$user->id]) }}"><b>{{ $user->fullname() }}</b></a>
        </h4>
        <h4>{{ $user->profile->title }}</h4> 
        <p>
          <span><strong>{{ $user->contact->country->name }}</strong><span>
          -{{ trans('search.user.last_active') }}: 2 days ago - {{ trans('search.user.tests') }} 
          <span class="h_color">7</span>
          -{{ trans('search.user.portfolio') }}:
          <span class="h_color">10</span>
        </p>{{ $user->profile->desc }}<p>
        </p>
        <p>
          @foreach ($user->skills as $skill)
          <span class="label label-default">{{ parse_multilang($skill->name, App::getLocale()) }}&nbsp;&nbsp;
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
          </span>&nbsp;&nbsp;
          @endforeach
        </p>
      </div>
      <div class="col-md-3"> 
        <?php $uh = $user->howManyHours('total') ?>
        <div class="margin-top-10px"> 
          <!-- <p class="text-right">@TODO: Top Rated</p> -->
          <p class="text-right">${{ $user->profile->rate }} {{ trans('job.per_hour') }}</p>
          <p class="text-right">{{ $uh }} {{ str_plural(trans('common.hour'), $uh) }}</p>
          <!--
          <p class="text-right">90% Job Success</p>
          -->
          <div class="clearfix">
          <p class="rate-control pull-right hide">
          <?php $user_sc = $user->totalScore() ?>
            <span class="user-score">{!! $user_sc > 0 ? $user_sc : '<i>'.trans('profile.message.No_Feedback_Yet').'</i>' !!}</span>
            @if ($user_sc > 0)
            @for ($sc = 0.25; $sc <= 5.00; $sc += 0.25)
            <input name="rate{{ $user->id }}" type="radio" class="rate {split:4}" disabled value="{{ $sc }}"{{ $user_sc >= $sc && $user_sc < $sc + 0.25 ? ' checked' : ''}}>
            @endfor
            @endif
          </p>
          </div>
          @if ($current_user && $current_user->isBuyer())
          <p class="text-right margin-top-10px">
            <a href="{{ route('job.invite', ['uid'=>$user->id]) }}" id="send_btn" class="btn btn-primary btn-wide">{{ trans('search.invite_to_job') }}</a>
          </p>
          @endif
        </div>
      </div>
    </div>
  </div>              
</div>