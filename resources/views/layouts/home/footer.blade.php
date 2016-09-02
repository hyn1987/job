{{-- Start Footer --}}
<footer class="page-footer" role="footer">
  <div class="container-fluid">
    {{-- Start Footer Navigation --}}
    <div class="footer-nav row">
      <div class="container">
        <div class="col-md-3 col-sm-6">
          <h4>{{ trans('footer.about_us') }}</h4>
          <ul>
            <li><a href="#">{{ trans('footer.about_us') }}</a></li>
            <li><a href="#">{{ trans('footer.our_team') }}</a></li>
            <li><a href="#">{{ trans('footer.blog') }}</a></li>
            <li><a href="#">{{ trans('footer.press') }}</a></li>
          </ul>
        </div>
        <div class="col-md-3 col-sm-6">
          <h4>{{ trans('footer.service_support') }}</h4>
          <ul>
            <li><a href="{{ route('frontend.terms_service') }}">{{ trans('footer.terms_of_service') }}</a></li>
            <li><a href="{{ route('frontend.privacy_policy') }}">{{ trans('footer.privacy_policy') }}</a></li>
            <li><a href="#">{{ trans('footer.download_tools') }}</a></li>
          </ul>
        </div>
        <div class="col-md-3 col-sm-6">
          <h4>{{ trans('footer.connect_with_us') }}</h4>
          <ul>
            <li><a href="#">{{ trans('footer.contact_us') }}</a></li>
            <li>
              <div class="social">
                <a class="facebook" href="#"><i class="icon-social-facebook"></i></a>
                <a class="twitter" href="#"><i class="icon-social-twitter"></i></a>
                <a class="youtube" href="#"><i class="icon-social-youtube"></i></a>
                <a class="dribbble" href="#"><i class="icon-social-dribbble"></i></a>
                <a class="tumblr" href="#"><i class="icon-social-tumblr"></i></a>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-3 col-sm-6">
          <h4>{{ trans('footer.browse') }}</h4>
          <ul>
            <li><a href="#">{{ trans('footer.find_jobs') }}</a></li>
            <li><a href="#">{{ trans('footer.freelancer_by_skill') }}</a></li>
            <li><a href="#">{{ trans('footer.freelancer_in_usa') }}</a></li>
            <li><a href="#">{{ trans('footer.freelancer_in_uk') }}</a></li>
            <li><a href="#">{{ trans('footer.freelancer_in_canada') }}</a></li>
            <li><a href="#">{{ trans('footer.freelancer_in_australia') }}</a></li>
          </ul>
        </div>
      </div>
    </div>{{-- End Footer Navigation --}}
    <div class="copyright row">
      {{ trans('page.footer.copyright', ['year' => date('Y')]) }}
    </div>
  </div>
</footer>{{-- End Footer --}}
