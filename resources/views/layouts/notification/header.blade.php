<!-- BEGIN HEADER -->
<div class="header-wrapper">
  <div class="header">
      <div class="header-section container">
        <div class="logo-section">
          <a href="#" class="logo">WawJob</a>
        </div>
        @include('layouts.freelancer.section.top_menu')
        @include('layouts.freelancer.section.right_menu')
      </div>
  </div>
  @if ($main_sub_menu)
  <div class="header-2">
    <div class="header-section container">
      @include('layouts.freelancer.section.top_sub_menu')
    </div>
  </div>
  @else
  <div class="header-2 no-sub-menu">
    <div class="header-section container"></div>
  </div>
  @endif
</div>