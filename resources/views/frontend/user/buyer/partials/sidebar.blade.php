<div class="dashboard-left-content">
    <div class="dashboard-close-main">
        <div class="close-bars"> <i class="las la-times"></i> </div>
        <div class="dashboard-top padding-top-40">
            <div class="thumb">
                @if(!is_null(Auth::guard('web')->user()->image))
                {!! render_image_markup_by_attachment_id(Auth::guard('web')->user()->image) !!}
                @else
                <img src="{{ asset('assets/frontend/img/static/user_profile.png') }}" alt="{{ __('No Image') }}"> 
                @endif
            </div>
            <div class="author-content">
                <h4 class="title"> {{ Auth::guard('web')->user()->name }} </h4>
                <strong><a href="{{ route('homepage') }}">{{ __('Visit Site') }}</a></strong>
            </div>
        </div>
        <div class="dashboard-bottom margin-top-35 margin-bottom-50">
            <ul class="dashboard-list ">

                <li class="list @if(request()->is('buyer/dashboard*')) active @endif">
                    <a href="{{ route('buyer.dashboard') }}"> <i class="las la-th"></i> {{__('Dashboard')}} </a>
                </li>
                <li class="list @if(request()->is('buyer/orders*')) active @endif">
                    <a href="{{ route('buyer.orders') }}"> <i class="las la-tasks"></i>{{ __('All Orders') }}</a>
                </li>
                <li class="list @if(request()->is('buyer/all-tickets*')) active @endif">
                    <a href="{{ route('buyer.support.ticket') }}"> <i class="lar la-star"></i>{{ __('Suppot Ticket') }}</a>
                </li>
                <li class="list @if(request()->is('buyer/profile*')) active @endif">
                    <a href="{{ route('buyer.profile')}}"> <i class="las la-user"></i> {{__('Profile')}} </a>
                </li>
                <li class="list @if(request()->is('buyer/account-settings*')) active @endif">
                    <a href="{{ route('buyer.account.settings') }}"> <i class="las la-cog"></i> {{__('Password Change')}} </a>
                </li>
                <li class="list">
                    <a href="{{ route('buyer.logout')}}"> <i class="las la-sign-out-alt"></i> {{__('Log Out' )}} </a>
                </li>

            </ul>
        </div>
        <div class="dashboard-logo padding-top-100">
            <a href="{{ route('homepage') }}" class="logo"> 
                {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
            </a>
        </div>
    </div>
</div>