
        <li class="dd-item dd3-item user-item" data-id="{{ $u->id }}" id="user_{{ $u->id }}">
            <div class="item">
              <!-- user row - header -->
              <div class="item-header">
                <div class="row">
                  <!-- user row - header - Info section -->
                  <div class="col-sm-9 col-xs-12 infoset">
                    <span class="badge badge-default">{{ ($users->currentPage() - 1) * $per_page + $id + 1 }}</span>
                    <a href="#"  class="collapse-toggler"><i></i></a>
                    <img class="avatar img-circle for-collapse" width="32px" height="32px" src="{{ avatarUrl($u, 32) }}">
                    <h5>
                      @if ($u->contact){!! $u->contact->country ? '<span class="marker country"><img src="/assets/images/common/flags/' . strtolower($u->contact->country->charcode) . '.png" /></span>' : '' !!}@endif
                      {{ $u->username }} 
                      <span class="star_wrapper" title=""><div class="rating_wrapper"><div class="star set set5" style="width: {{ $u->getRatingPercent() }}%;" title=""></div><div class="star set5"></div></div></span>
                      <span class="marker email"><i class="fa fa-envelope"></i>&nbsp;{{ $u->email }}</span>
                      <span class="marker user-type user-type-@if ($u->contact){{ $u->userType() }}@endif">
                        @if ($u->contact){{ trans('common.user.types.' . $u->userType()) }}@endif
                      </span>

                      <span class="marker user-state state-{{$u->status}}">{{ trans('common.user.status.' . $u->status ) }}</span>

                    </h5>
                  </div>

                  <!-- user row - header - Action buttons -->
                  <div class="col-sm-3 col-xs-12 buttonset">
                    <div class="toolbar pull-right">
                      @if ($u->userType() != "user_sadmin" && $u->userType() != "user_admin")
                      <span class="dropdown view-more">
                        <button class="dropdown-toggler btn btn-info btn-xs" type="button" data-toggle="dropdown" aria-expanded="false">View <i class="fa fa-angle-down"></i></button>
                        <ul class="dropdown-menu action-menu" role="menu">
                          <li><a href="{{ route('admin.report.usertransaction', ['uid' => $u->id]) }}"><i class="fa fa-info"></i> Public Profile</a></li>
                          @if ($u->userType() == 'user_buyer')
                          <li><a href="{{ route('admin.job.list') }}?un={{ $u->username }}"><i class="fa fa-file-powerpoint-o"></i> Job Postings</a></li>
                          <li><a href="{{ route('admin.contract.list') }}?buyer={{ $u->username }}"><i class="fa fa-magic"></i> Contracts</a></li>
                          @elseif ($u->userType() == 'user_freelancer')
                          <li><a href="{{ route('admin.contract.list') }}?lancer={{ $u->username }}"><i class="fa fa-magic"></i> Contracts</a></li>
                          @endif
                          <li><a href="{{ route('admin.report.usertransaction', ['uid' => $u->id]) }}"><i class="fa fa-dollar"></i> Transactions</a></li>
                        </ul>
                      </span>
                      @endif
                      <a href="{{ route('admin.user.edit', ['id' => $u->id]) }}"><button type="button" class="btn btn-primary btn-xs">Edit&nbsp;<i class="fa fa-user"></i></button></a>
                      
                      <span class="dropdown do-more for-status-{{ $u->status }}">
                        <button class="dropdown-toggler btn btn-danger btn-xs" type="button" data-toggle="dropdown" aria-expanded="false">Action <i class="fa fa-angle-down"></i></button>
                        <ul class="dropdown-menu action-menu" role="menu">
                          <li><a href="#" class="do-ajaxaction" ref="message"><i class="fa fa-comments"></i> Message</a></li>
                          <li class="divider divider4status"></li>
                          @for ($i = 1; $i < 5; $i++)
                          <li class="status-{{ $i }}"><a href="#" class="do-ajaxaction" ref="status" refv="{{ $i }}"><i class="{{ trans('common.user.status-do-icon.' . $i) }}"></i> {{ trans('common.user.status-do.' . $i) }}</a></li>
                          @endfor
                          <li class="status-9"><a href="#" class="do-ajaxaction" ref="status" refv="9"><i class="{{ trans('common.user.status-do-icon.9') }}"></i> {{ trans('common.user.status-do.9') }}</a></li>
                          
                        </ul>
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- user row - body -->
              <div class="item-body" style="display: none;">
                <img class="avatar img-circle" width="48px" height="48px" src="{{ avatarUrl($u, 48) }}">
                <div class="item-content">
                  <span class="arrow hidden"></span>
                  <div class="info-title">
                    <a class="name" href="#">
                      @if ($u->contact)
                        {{ $u->contact->first_name }}
                        {{ $u->contact->last_name }}
                      @endif
                    </a>
                    <span class="country">@if ($u->contact){!! $u->contact->country ? ' - '.$u->contact->country->name:'' !!}@endif</span>
                    <span class="phone">@if ($u->contact){{ $u->contact->phone }}@endif</span>
                  </div>

                  <div class="info-content" style="min-height: 25px;">
                    
                    @if ($u->userType() == "user_buyer" || $u->userType() == "user_freelancer")
                    <div>

                      @include('pages.admin.user.list_row_cs', [
                        '__inner_v_type' => '1',
                        '__inner_v_class' => 'marker open-job',
                        '__inner_v_label' => 'Open: ',
                      ])

                      @include('pages.admin.user.list_row_cs', [
                        '__inner_v_type' => '2',
                        '__inner_v_class' => 'marker paused-job',
                        '__inner_v_label' => 'Paused: ',
                      ])

                      @include('pages.admin.user.list_row_cs', [
                        '__inner_v_type' => '3',
                        '__inner_v_class' => 'marker suspended-job',
                        '__inner_v_label' => 'Suspended: ',
                      ])

                      <span class="marker open-closed">
                        <span class="label">Closed: </span>
                        <span class="value">{{ $u->getContractCount([4, 5, 6]) }}</span>
                      </span>
                    </div>
                    @endif

                  </div>
                </div>
              </div>
            </div>

        </li>