<!-- BEGIN HEADER -->
<div class="header-wrapper">
  <div class="header">
      <div class="header-section container">
        <div class="logo-section">
          <a href="/" class="logo">WawJob</a>
        </div>

        @if (!$current_user)
          

        @elseif ($current_user->isBuyer())
          @include('layouts.buyer.section.top_menu')
          @include('layouts.buyer.section.right_menu')
        @else
          @include('layouts.freelancer.section.top_menu')
          @include('layouts.freelancer.section.right_menu')
        @endif
        
      </div>
  </div>
  @if ($main_sub_menu && isset($main_sub_menu['sub_menu']) && !empty($main_sub_menu['sub_menu']) )
  <div class="header-2">
    <div class="header-section container">
      @if (!$current_user)

      @elseif ($current_user->isBuyer())
        @include('layouts.buyer.section.top_sub_menu')
      @else
        @include('layouts.freelancer.section.top_sub_menu')
      @endif
    </div>
  </div>
  @else
  <div class="header-2 no-sub-menu">
    <div class="header-section container"></div>
  </div>
  @endif
</div>
@include('layouts.notification.notification')
