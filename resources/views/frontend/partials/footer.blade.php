
@php
$footer_variant = !is_null(get_footer_style()) ? get_footer_style() : '02';
@endphp
@include('frontend.partials.pages-portion.footers.footer-'.$footer_variant)



<!-- back to top area start -->
<div class="back-to-top {{$page_post->back_to_top ?? ''}}">
    <span class="back-top"><i class="las la-angle-up"></i></span>
</div>
<!-- back to top area end -->


<script src="{{asset('assets/frontend/js/slick.js')}}"></script>
<script src="{{asset('assets/frontend/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/wow.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/jquery.magnific-popup.js')}}"></script>
<script src="{{asset('assets/frontend/js/jquery.nice-select.js')}}"></script>
<script src="{{asset('assets/frontend/js/main.js')}}"></script>
<script src="{{asset('assets/frontend/js/dynamic-script.js')}}"></script>
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{csrf_token()}}'
    }
});
</script>

@include('frontend.pages.services.partials.service-search')
@include('frontend.partials.home-search')
@include('frontend.partials.inline-scripts')

{{-- <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script> --}}
<script src="{{asset('assets/common/js/toastr.min.js')}}"></script>
{!! Toastr::message() !!}

<script>
    $('[data-toggle="tooltip"]').tooltip()
</script>

@yield('scripts')

</body>
</html>
