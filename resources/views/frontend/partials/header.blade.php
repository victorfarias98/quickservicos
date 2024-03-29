<!DOCTYPE html>
<html lang="{{get_user_lang()}}" dir="{{get_user_lang_direction()}}">

<head>
   @if(!empty(get_static_option('site_google_analytics')))
        {!! get_static_option('site_google_analytics') !!}
    @endif
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}
    {!! load_google_fonts() !!}

    <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/line-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/slick.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/helpers.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/dynamic-style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/common/css/toastr.min.css')}}">

    @if( get_user_lang_direction() === 'rtl')
    <link rel="stylesheet" href="{{asset('assets/common/css/rtl.css')}}">
    @endif

    <link rel="canonical" href="{{request()->url()}}" />
    <script src="{{asset('assets/common/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('assets/common/js/jquery-migrate-3.3.2.min.js')}}"></script>



    @include('frontend.partials.root-style')
    @yield('style')


    @if(request()->routeIs('homepage'))
        <title>{{get_static_option('site_title')}} - {{get_static_option('site_tag_line')}}</title>

           {!! render_site_meta() !!}

     @elseif( request()->routeIs('frontend.dynamic.page'))
           {!! render_site_title($page_post->title) !!}
           {!! render_site_meta() !!}  

    @else
        @yield('page-meta-data')
        <title> @yield('site-title') - {{ get_static_option('site_title')}} </title>
    @endif


</head>


<body>

@include('frontend.partials.preloader')

@if(request()->routeIs('frontend.dynamic.page'))
    @include('frontend.partials.navbar',$page_post)
@else
    @include('frontend.partials.navbar')
@endif


