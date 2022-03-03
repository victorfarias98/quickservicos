
<nav class="navbar navbar-area navbar-two {{ $page_post->page_class ?? '' }} navbar-expand-lg">
    <div class="container container-two nav-container">
        <div class="responsive-mobile-menu">
            <div class="logo-wrapper">
                <a href="{{ route('homepage') }}" class="logo">
                    {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                </a>
            </div>
            <a href="#0" class="show-nav-right-contents">
                <i class="las la-ellipsis-v"></i>
            </a>
            <button class="navbar-toggler black-color" type="button" data-toggle="collapse" data-target="#bizcoxx_main_menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="bizcoxx_main_menu">
            <ul class="navbar-nav">
                {!! render_frontend_menu($primary_menu) !!}
            </ul>
        </div>
        <div class="nav-right-content">
            <div class="navbar-right-inner">
                <div class="info-bar-item">
                    @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type==0)
                    <div class="notification-icon icon">
                        @if(Auth::guard('web')->check())
                        <i class="las la-bell"></i>
                        <span class="notification-number style-02"> 
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                        @endif 
                        <div class="notification-list-item mt-2">
                            <h5 class="notification-title">{{ __('Notifications') }}</h5>
                            <div class="list">
                                @if(Auth::guard('web')->check() && Auth::guard('web')->user()->unreadNotifications->count() >=1)
                                <span>
                                    @foreach(Auth::user()->unreadNotifications->take(5) as $notification)
                                    <a class="list-order" href="{{ route('seller.order.details',$notification->data['order_id']) }}">
                                        <span class="order-icon"> <i class="las la-check-circle"></i> </span>
                                        {{ $notification->data['order_message'] }} #{{ $notification->data['order_id'] }}
                                    </a>
                                    @endforeach
                                </span>
                                <a class="p-2 text-center d-block" href="{{ route('seller.notification.all') }}">{{ __('View All Notification') }}</a>
                                @else
                                    <p class="text-center text-white padding-3">{{ __('No New Notification') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <x-frontend.user-menu/>
            </div>
        </div>
    </div>
</nav>