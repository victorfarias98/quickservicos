@extends('frontend.frontend-master')
@section('site-title')
    {{__('Verify Account')}}
@endsection
@section('content')
<div class="signup-area padding-top-70 padding-bottom-100">
    <div class="container">
        <div class="signup-wrapper">
            <div class="signup-contents">
                <h3 class="signup-title"> {{ __('Verify Your Account')}} </h3>

                @if(Session::has('msg_success'))
                <p class="alert alert-success">{{ Session::get('msg_success') }}</p>
                @endif
                
                @if(Session::has('msg'))
                <p class="alert alert-danger">{{ Session::get('msg') }}</p>
                @endif

                <x-msg.error/>

                <form class="signup-forms" action="{{ route('email.verify')}}" method="post">
                    @csrf
                    <div class="single-signup margin-top-30">
                        <label class="signup-label"> {{'Enter code*'}} </label>
                        <input class="form--control" type="text" name="email_verify_token" placeholder="Enter code">
                    </div>
                    <button type="submit">{{ __('Verify Account') }}</button>
                </form>
                <a class="mt-5 text-center" href="{{ route('resend.verify.code') }}">{{ __('Resend Code') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
