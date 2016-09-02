
        <li class="dd-item dd3-item" data-id="{{ $u->id }}" id="contract_{{ $u->id }}">
            <div class="item">
              <!-- contract row - header -->
              <div class="item-header">
                <div class="row">
                  <!-- contract row - header - Info section -->
                  <div class="col-sm-9 col-xs-12 infoset">
                    <span class="badge badge-default">{{ ($contracts->currentPage() - 1) * $per_page + $ind + 1 }}</span>
                    <a href="#"  class="collapse-toggler"><i></i></a>

                    <span class="mark-wrapper">

                      <span class="contract-title"><span class="badge number">#{{ $u->id }}</span> {{ $u->title }}</span>

                      <span class="badge-box">
                        <img class="avatar img-circle" width="32px" height="32px" src="{{ avatarUrl($u->buyer, 32) }}">
                        <a class="avatar-name" href="{{ route('admin.user.edit', $u->buyer->id) }}">{{ $u->buyer->username }}</a>
                      </span>

                      <span class="badge-box">
                        <img class="avatar img-circle" width="32px" height="32px" src="{{ avatarUrl($u->contractor, 32) }}">
                        <a class="avatar-name" href="{{ route('admin.user.edit', $u->contractor->id) }}">{{ $u->contractor->username }}</a>
                      </span>

                      <span class="marker contract-type contract-type-{{ $u->type }}"><i class="{{ trans('common.contract.type-icon.' . $u->type) }}"></i> <span>${{ number_format($u->price, 2, '.', '') }} {{ $u->isHourly() ? '/hr' : '' }}</span></span>
                      <span class="marker contract-state contract-state-{{ $u->status }}"><i class="{{ trans('common.contract.status-icon.' . $u->status) }}"></i></span>
                      
                    </span>

                  </div>
                  <!-- contract row - header - Action buttons -->
                  <div class="col-sm-3 col-xs-12 buttonset">
                    <div class="toolbar pull-right">
                      <!-- <button type="button" class="btn btn-primary btn-xs">More <i class="fa fa-eye"></i></button> -->
                      <!-- <button type="button" class="btn btn-info btn-xs">Financial&nbsp;<i class="fa fa-dollar"></i></button> -->
                      
                      <span class="dropdown view-more">
                        <button class="dropdown-toggler btn btn-info btn-xs" type="button" data-toggle="dropdown" aria-expanded="false">View <i class="fa fa-angle-down"></i></button>
                        <ul class="dropdown-menu action-menu" role="menu">
                          <li><a href="{{ route('admin.contract.details', ['id' => $u->id]) }}"><i class="fa fa-eye"></i> Details</a></li>
                          @if ($u->isHourly())
                          <li><a href="{{ route('admin.workdiary.view', ['cid' => $u->id]) }}"><i class="fa fa-calendar"></i> Work Diary</a></li>
                          @endif
                          <li><a href="{{ route('admin.report.transaction', ['cid' => $u->id]) }}"><i class="fa fa-dollar"></i> Transactions</a></li>
                          <li><a href="#"><i class="fa fa-history"></i> History</a></li>
                        </ul>
                      </span>

                      <span class="dropdown category-types action-menu-set action-status-{{ $u->status }}">
                        <button class="dropdown-toggler btn btn-danger btn-xs" type="button" data-toggle="dropdown" aria-expanded="false">Action <i class="fa fa-angle-down"></i></button>
                        <ul class="dropdown-menu action-menu" role="menu">
                          @for ($i = 1; $i < 5; $i++)
                          <li><a href="#" class="status-do status-do-{{ $i }}" ref="{{ $i }}"><i class="{{ trans('common.contract.status-do-icon.' . $i) }}"></i> {{ trans('common.contract.status-do.' . $i) }}</a></li>
                          @endfor
                        </ul>
                      </span>
                      <!-- <button type="button" class="btn btn-danger btn-xs">Block&nbsp;<i class="fa fa-times"></i></button> -->
                    </div>
                  </div>
                </div>
              </div>
              <!-- contract row - body -->
              <div class="item-body" style="display: none;">
                <div class="item-content">
                  <span class="arrow hidden"></span>
                  <div class="info-title">
                    
                  </div>
                  <div class="info-content">
                    <p>{{ $u->project->desc }}</p>
                  </div>
                </div>
              </div>
            </div>

        </li>