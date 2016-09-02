
        <form class="navbar-form navbar-right" role="search" name="user_list" method="post" action="{{ route('admin.user.list') }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="input-group">
              <span class="input-group-addon input-circle-left"><i class="fa fa-user"></i></span>
              <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username">
            </div>

            <div class="input-group">
              <span class="input-group-addon input-circle-left"><i class="fa fa-envelope"></i></span>
              <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
            </div>

            <div class="input-group">
              <select class="form-control" name="user_type">
                <option value="">All Users</option>
                @foreach ($userTypeList as $utype)
                  <option value="{{ $utype->id }}" {{ old('user_type') != null && old('user_type') == $utype->id ? ' selected' : '' }}>{{ trans('common.user.types.' . $utype->slug) }}</option>
                @endforeach
              </select>
            </div>

            <div class="input-group">
              <select class="form-control" name="status">
                <option value="">All Status</option>
                @foreach ($userStatusList as $status)
                  <option value="{{ $status }}" {{ old('status') != null && old('status') == $status ? ' selected' : '' }}>{{ trans('common.user.status.' . $status) }}</option>
                @endforeach
              </select>
            </div>

            <button class="btn blue btn-search" type="submit">Search</button>

        </form>