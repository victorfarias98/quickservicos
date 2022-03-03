@extends('frontend.frontend-page-master')

@section('site-title')
    {{ __('Seller Profile') }}
@endsection

@section('content')
    <!-- Banner Inner area Starts -->
    @if(!empty($seller))
    <div class="banner-inner-area section-bg-2 padding-top-40 padding-bottom-70">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-6 margin-top-30">
                    <div class="profile-author-contents">
                        <div class="profile-flex-content">
                            <div class="thumb">
                                {!! render_image_markup_by_attachment_id($seller->image) !!}
                            </div>
                            <div class="profile-contents">
                                <h4 class="title"> <a href="{{ route('about.seller.profile',$seller->id) }}"> {{ $seller->name }} </a> </h4>
                                @if($service_rating >=1)
                                <div class="profiles-review">
                                    <span class="icon">{{ __('Rating:') }} </span>
                                    <span class="reviews"> 
                                        <b>({{ round($service_rating,1) }}) </b>
                                        ({{ $service_reviews->count() }})
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 margin-top-30">
                    <div class="profile-author-contents">
                        <ul class="profile-about">
                            <li> {{ __('From:') }} <span> {{ optional($seller->country)->country }} </span> </li>
                            <li> {{ __('Seller Since:') }} <span> {{ Carbon\Carbon::parse($seller_since->created_at)->year }}  </span> </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-5 margin-top-30">
                    <div class="profile-author-contents">
                        <div class="profile-single-achieve">
                            <div class="single-achieve">
                                <div class="achieve-inner">
                                    <div class="icon">
                                        <i class="las la-check"></i>
                                    </div>
                                    <div class="contents margin-top-10">
                                        <h3 class="title">@if(!empty($completed_order)){{ $completed_order }} @endif</h3>
                                        <span class="ratings-span"> {{ __('Order Completed ') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="single-achieve">
                                <div class="achieve-inner">
                                    <div class="icon">
                                        <i class="las la-star"></i>
                                    </div>
                                    <div class="contents margin-top-10">
                                        <h3 class="title">@if(!empty($seller_rating_percentage_value)) {{ $seller_rating_percentage_value }}%@endif</h3>
                                        <span class="ratings-span">{{ __('Seller Rating') }} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Banner Inner area end -->

    <!-- Featured Service area starts -->
    @if(!empty($services))
    <section class="services-area padding-top-100 padding-bottom-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-two">
                        <h3 class="title">{{ __('Services of this Seller') }} </h3>
                    </div>
                </div>
            </div>
            <div class="row margin-top-50">
                <div class="col-lg-12">
                    <div class="services-slider dot-style-one">
                        @foreach($services as $service)
                        <div class="single-services-item">
                            <div class="single-service">
                                <a href="{{ route('service.list.details',$service->slug) }}" class="service-thumb">
                                    {!! render_image_markup_by_attachment_id($service->image) !!}
                                    @if($service->featured == 1)
                                    <div class="award-icons">
                                        <i class="las la-award"></i>
                                    </div>
                                    @endif
                                </a>
                                <div class="services-contents">
                                    <ul class="author-tag">
                                        <li class="tag-list">
                                            <a href="{{ route('about.seller.profile',$service->seller_id) }}">
                                                <div class="authors">
                                                    <div class="thumb">
                                                        {!! render_image_markup_by_attachment_id(optional($service->seller)->image) !!}
                                                        <span class="notification-dot"></span>
                                                    </div>
                                                    <span class="author-title">{{ optional($service->seller)->name }} </span>
                                                </div>
                                            </a>
                                        </li>
                                        @if($service->reviews->count() >= 1)
                                        <li class="tag-list">
                                            <a href="javascript:void(0)">
                                                <span class="icon"> {{ __('Rating:') }} </span>
                                                <span class="reviews"> 
                                                    {{ round(optional($service->reviews)->avg('rating'),1) }}
                                                    ({{ optional($service->reviews)->count() }})
                                                </span>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                    <h5 class="common-title"> <a href="{{ route('service.list.details',$service->slug) }}">{{ $service->title }} </a> </h5>
                                    <p class="common-para"> {{ Str::limit($service->description,100) }} </p>
                                    <div class="service-price">
                                        <span class="starting">{{ __('Starting at') }} </span>
                                        <span class="prices"> {{ amount_with_currency_symbol( $service->price) }} </span>
                                    </div>
                                    <div class="btn-wrapper">
                                        <a href="{{ route('service.list.book',$service->slug) }}" class="cmn-btn btn-appoinment btn-bg-1">{{ __('Book Appoinment') }} </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!-- Featured Service area ends -->
    
    <!-- Review seller area Starts -->
    @if($service_reviews-> count() >= 1)
    <div class="review-seller-area padding-bottom-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-two">
                        <h3 class="title">{{ get_static_option('service_reviews_title') ?? __('Reviews as Seller') }}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="review-seller-wrapper">
                        <div class="about-review-tab">
                            @foreach($service_reviews as $review)
                            <div class="about-seller-flex-content style-02">
                                <div class="about-seller-thumb">
                                    {!! render_image_markup_by_attachment_id(optional($review->buyer)->image) !!}
                                </div>
                                <div class="about-seller-content">
                                    <h5 class="title"> {{ $review->name }} </h5>
                                    <div class="about-seller-list">
                                        <span class="icon">  <i class="las la-star"></i>  </span>
                                        <span class="icon">  <i class="las la-star"></i>  </span>
                                        <span class="icon">  <i class="las la-star"></i>  </span>
                                        <span class="icon">  <i class="las la-star"></i>  </span>
                                        <span class="icon">  <i class="las la-star"></i>  </span>
                                    </div>
                                    <p class="about-review-para">{{ $review->message }}</p>
                                    <span class="review-date"> {{ optional($review->created_at)->toFormattedDateString() }} </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Review seller area ends -->
    
@endsection