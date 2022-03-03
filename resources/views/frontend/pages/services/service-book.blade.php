@extends('frontend.frontend-page-master')

@section('site-title')
    {{ $service_details_for_book->title }}
@endsection

@section('page-title')
    <?php 
    $page_info = request()->url();
    $str = explode("/",request()->url());
    $page_info = $str[count($str)-2];
    ?>  
    {{ ucfirst(str_replace(' ', '-', $page_info)) }}

@endsection 

@section('inner-title')
    {{ $service_details_for_book->title}}
@endsection 

@section('content')

    <!-- Location Overview area starts -->
    <section class="location-overview-area padding-top-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('service.create.order') }}" id="msform" class="msform ms-order-form" method="post" name="msOrderForm" novalidate>
                        @csrf
                        @if (!empty($service_details_for_book))
                            <ul class="overview-list step-list">
                                <li class="list active" id="account">
                                    <a class="list-click" href="javascript:void(0)"> <span class="list-number">1</span>{{ __('Location') }}</a>
                                </li>
                                <li class="list">
                                    <a class="list-click" href="javascript:void(0)"> <span
                                            class="list-number">2</span>{{ __('Service') }}</a>
                                </li>
                                <li class="list">
                                    <a class="list-click" href="javascript:void(0)"> <span
                                            class="list-number">3</span>{{ __('Date & Time') }}</a>
                                </li>
                                <li class="list">
                                    <a class="list-click" href="javascript:void(0)"> <span
                                            class="list-number">4</span>{{ __('Information ') }}</a>
                                </li>
                                <li class="list">
                                    <a class="list-click" href="javascript:void(0)"> <span
                                        class="list-number">5</span> {{ __('Confirmation') }} </a>
                                </li>
                            </ul>
                            <!-- Location -->
                            <div> <x-msg.error_for_service_book/> </div>
                            <fieldset class="padding-top-20 padding-bottom-100 confirm-location">
                                <div class="overview-list-all">
                                    <div class="overview-location">
                                        <div class="single-location active margin-top-30 wow fadeInUp" data-wow-delay=".3s">
                                            <span class="location">{{ __('Your Location') }} </span>
                                            @if(Auth::guard('web')->check())
                                              <span class="location loacation_add country_name">{{ optional(Auth::guard('web')->user()->country)->country }}</span>
                                              <span class="location loacation_add city_name">{{ optional(Auth::guard('web')->user()->city)->service_city }}</span>
                                              <span class="location loacation_add area_name">{{ optional(Auth::guard('web')->user()->area)->service_area }}</span>
                                              
                                            @else 
                                              <span class="location loacation_add country_name"></span>
                                              <span class="location loacation_add city_name"></span>
                                              <span class="location loacation_add area_name"></span>
                                              
                                            @endif  
                                        </div>
                                        <div class="select_city_area_country">
                                            <label for="" class="location">{{ __('Choose Country') }}</label>
                                            <select name="choose_service_country" id="choose_service_country">
                                                <option value="">{{ __('Select Country') }}</option>
                                                @foreach ($countries as $country)
                                                    @if(Auth::guard('web')->check())
                                                        <option value="{{ $country->id }}" @if( optional(Auth::guard('web')->user()->country)->id == $country->id) selected @endif>{{ $country->country }}
                                                    @else
                                                        <option value="{{ $country->id }}">{{ $country->country }}
                                                    </option>
                                                    @endif 
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select_city_area_country">
                                            <label for="choose_service_city"
                                                class="location">{{ __('Choose City') }}</label>
                                            <select name="choose_service_city" id="choose_service_city" class="get_service_city">
                                                <option value="">{{ __('Select City') }}</option>
                                                @foreach ($cities as $city)
                                                    @if(Auth::guard('web')->check())
                                                        <option value="{{ $city->id }}" @if(optional(Auth::guard('web')->user()->city)->id ===  $city->id) selected @endif >{{ $city->service_city }}</option>
                                                    @else    
                                                        <option value="{{ $city->id }}">{{ $city->service_city }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="select_city_area_country">
                                            <label for="choose_service_area"
                                                class="location">{{ __('Choose Area') }}</label>
                                            <select name="choose_service_area" id="choose_service_area"
                                                class="get_service_area">
                                                <option value="">{{ __('Select Area') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="button" name="next" class="next action-button" value="Next" />
                            </fieldset>
                            <!-- Service -->
                            <fieldset class="padding-top-20 padding-bottom-100 confirm-service">
                                <div class="row">
                                    <div class="col-lg-8 margin-top-30">
                                        <div class="service-overview-wrapper padding-bottom-30">
                                            <div class="overview-author overview-author-border">
                                                <div class="overview-flex-author">
                                                    <div class="overview-thumb">
                                                        {!! render_image_markup_by_attachment_id($service_details_for_book->image) !!}
                                                    </div>
                                                    <div class="overview-contents">
                                                        <h4 class="overview-title"> <a
                                                                href="{{ route('service.list.details',$service_details_for_book->slug) }}">{{ $service_details_for_book->title }}</a>
                                                        </h4>
                                                        @if($service_details_for_book->reviews->count() >= 1)
                                                        <span class="overview-review"> {{ __('Rating:') }} {{ round(optional($service_details_for_book->reviews)->avg('rating'),1) }}
                                                            <b>({{ optional($service_details_for_book->reviews)->count() }})</b> 
                                                        </span>
                                                        @endif
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="overview-single padding-top-40">
                                                <h4 class="title">{{ get_static_option('service_main_attribute_title') ?? __('What\'s Included') }}</h4>
                                                <div class="include-contents margin-top-30">
                                                    @foreach ($service_includes as $include)
                                                        <div class="single-include include_service_id_{{ $include->id }}">
                                                            <ul class="include-list">
                                                                <li class="lists">
                                                                    <div class="list-single">
                                                                        <span
                                                                            class="rooms">{{ $include->include_service_title }}</span>
                                                                    </div>
                                                                    <div class="list-single">
                                                                        <span class="values"
                                                                            id="include_service_unit_price_{{ $include->id }}">
                                                                            {{ amount_with_currency_symbol($include->include_service_price) }}
                                                                        </span>
                                                                        <span class="value-input">
                                                                            <input type="number" min="1" 
                                                                                class="inc_dec_include_service"
                                                                                data-id="{{ $include->id }}"
                                                                                data-price="{{ $include->include_service_price }}"
                                                                                value="{{ $include->include_service_quantity }}">
                                                                        </span>
                                                                    </div>
                                                                </li>
                                                                <li class="lists remove-service-list"
                                                                    data-id="{{ $include->id }}">
                                                                     <a class="remove"
                                                                        href="javascript:void(0)">{{ __('Remove') }}
                                                                    </a> 
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="overview-single padding-top-60 extra-services">
                                                <h4 class="title">{{ get_static_option('service_additional_attribute_title') ?? __('Upgrade your order with extras') }}
                                                </h4>
                                                <div class="row">
                                                    @foreach ($service_additionals as $additional)
                                                        <div class="col-lg-6 margin-top-30">
                                                            <div class="overview-extra">
                                                                <div class="checkbox-inlines">
                                                                    <input class="check-input" type="checkbox"
                                                                        id="{{ $additional->id }}" value="{{ $additional->id }}">
                                                                    <label class="checkbox-label" for="{{ $additional->id }}">
                                                                        {{ $additional->additional_service_title }}
                                                                    </label>
                                                                </div>
                                                                <div class="overview-extra-flex-content">
                                                                    <div class="list-single">
                                                                        <span class="values" price="{{ $additional->id }}">
                                                                            {{ $additional->additional_service_price }}
                                                                        </span>
                                                                        <span class="value-input"> 
                                                                            <input type="number" min="1" class="inc_dec_additional_service" 
                                                                            id="additional_service_quantity_{{ $additional->id }}"
                                                                            data-id="{{ $additional->id }}"
                                                                            data-price="{{ $additional->additional_service_price }}" 
                                                                            value="{{ $additional->additional_service_quantity }}">
                                                                        </span>
                                                                    </div>
                                                                    <span class="price-value">
                                                                        {{ amount_with_currency_symbol($additional->additional_service_price) }}
                                                                    </span>
                                                                    <div class="overview-extra-thumb">
                                                                        {!! render_image_markup_by_attachment_id($additional->additional_service_image) !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="overview-single padding-top-60">
                                                <h4 class="title">{{ get_static_option('service_benifits_title') ?? __('Benifits of the Package:') }}</h4>
                                                <ul class="overview-benefits margin-top-30">
                                                    @foreach ($service_benifits as $benifit)
                                                        <li class="list">{{ $benifit->benifits }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 margin-top-30">
                                        <div class="service-overview-summery">
                                            <h4 class="title"> {{ get_static_option('service_booking_title') ?? __('Booking Summery') }} </h4>
                                            <div class="overview-summery-contents">
                                                <div class="single-summery">
                                                    <span class="summery-title"> {{ get_static_option('service_appoinment_package_title') ?? __('Appointment Package Service') }}
                                                    </span>
                                                    <div class="summery-list-all">
                                                        <ul class="summery-list">
                                                            @foreach ($service_includes as $include)
                                                                <li class="list include_service_id_{{ $include->id }}">
                                                                    <span
                                                                        class="rooms">{{ $include->include_service_title }}
                                                                    </span>
                                                                    <span class="value-count service_quantity_count"
                                                                        id="include_service_quantity_2_{{ $include->id }}">{{ $include->include_service_quantity }}
                                                                    </span>
                                                                    <span
                                                                        class="room-count">{{ amount_with_currency_symbol($include->include_service_price) }}
                                                                    </span>
                                                                    
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <ul class="summery-result-list">
                                                            <li class="result-list">
                                                                <span class="rooms">
                                                                    {{ get_static_option('service_package_fee_title') ?? __('Package Fee') }}</span>
                                                                <span class="value-count package-fee">{{ amount_with_currency_symbol($service_details_for_book->price) }}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="single-summery">
                                                    <span class="summery-title">{{ get_static_option('service_extra_title') ?? __('Extra Service') }}</span>
                                                    <div class="summery-list-all">
                                                        <ul class="summery-list extra-service-list">
                                                            
                                                        </ul>
                                                        <ul class="summery-result-list result-border padding-bottom-20">
                                                            <li class="result-list">
                                                                <span class="rooms">{{ get_static_option('service_extra_title') ?? __('Extra Service') }}</span>
                                                                <span class="value-count extra-service-fee">$00</span>
                                                            </li>
                                                        </ul>
                                                        <ul class="summery-result-list result-border padding-bottom-20">
                                                            <li class="result-list">
                                                                <span class="rooms">{{ get_static_option('service_subtotal_title') ?? __('Subtotal') }}</span>
                                                                <span class="value-count service-subtotal">$00</span>
                                                            </li>
                                                        </ul>
                                                        <ul class="summery-result-list result-border padding-bottom-20">
                                                            <li class="result-list">
                                                                <span class="rooms"> {{ __('Tax(+)') }} <span class="service-tax">{{ $service_details_for_book->tax }}</span> %</span>
                                                                <span class="value-count tax-amount">$00</span>
                                                            </li>
                                                        </ul>
                                                        <ul class="summery-result-list">
                                                            <li class="result-list">
                                                                <span class="rooms"> <strong>{{ get_static_option('service_total_amount_title') ?? __('Total') }}</strong></span>
                                                                <span class="value-count total-amount">$00</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="button" name="next" class="next action-button" value="Next" /> <input
                                    type="button" name="previous" class="previous action-button-previous"
                                    value="Previous" />
                            </fieldset>
                            <!-- Date & Time -->
                            <fieldset class="padding-top-20 padding-bottom-100 confirm-date-time">
                                <div class="date-overview">
                                    <div class="single-date-overview margin-top-30">
                                        <h4 class="date-time-title"> {{ get_static_option('service_available_date_title') ?? __('Available Date') }} </h4>
                                        <ul class="date-time-list margin-top-20 show-date">
                                            <span class="seller-id-for-schedule" style="display:none">{{ $service_details_for_book->seller_id }}</span>

                                        </ul>
                                    </div>
                                    <div class="single-date-overview margin-top-30">
                                        <h4 class="date-time-title"> {{ get_static_option('service_available_schudule_title') ?? __('Available Schedule') }} </h4>
                                        <ul class="date-time-list margin-top-20 show-schedule">
                                            
                                        </ul>
                                    </div>
                                </div>
                                <input type="button" name="next" class="next action-button" value="Next" /> <input
                                    type="button" name="previous" class="previous action-button-previous"
                                    value="Previous" />
                            </fieldset>
                            <!-- Information -->
                            <fieldset class="padding-top-20 padding-bottom-100 confirm-information">
                                <div class="Info-overview padding-top-30">
                                    <h3 class="date-time-title">{{ get_static_option('service_booking_information_title') ?? __(' Booking Information') }} </h3>
                                    <div class="single-info-overview margin-top-30">
                                        <div class="single-info-input">
                                            <label class="info-title"> {{ __('Your Name*') }} </label>
                                            <input class="form--control" type="text" name="name" id="name" placeholder="{{ __('Type Your Name') }}"
                                                @if(Auth::guard('web')->check()) value="{{ Auth::user()->name }}" @else value="" @endif>
                                        </div>
                                        <div class="single-info-input">
                                            <label class="info-title"> {{ __('Your Email*') }} </label>
                                            <input class="form--control" type="email" name="email" id="email" placeholder="{{ __('Type Your Email') }}"
                                                @if(Auth::guard('web')->check()) value="{{ Auth::user()->email }}" @else value="" @endif>
                                        </div>
                                    </div>
                                    <div class="single-info-overview margin-top-30">
                                        <div class="single-info-input">
                                            <label class="info-title"> {{ __('Phone Number*') }} </label>
                                            <input class="form--control" type="text" name="phone" id="phone" placeholder="{{ __('Type Your Number') }}" 
                                            @if(Auth::guard('web')->check()) value="{{ Auth::user()->phone }}" @else value="" @endif>
                                        </div>
                                        <div class="single-info-input">
                                            <label class="info-title"> {{ __('Post Code*') }} </label>
                                            <input class="form--control" type="text" name="post_code" id="post_code" placeholder="{{ __('Type Post Code') }}"
                                            @if(Auth::guard('web')->check()) value="{{ Auth::user()->post_code }}" @else value="" @endif>
                                        </div>
                                    </div>
                                    <div class="single-info-overview margin-top-30">
                                        <div class="single-info-input">
                                            <label class="info-title"> {{ __('Your Address*') }} </label>
                                            <input class="form--control" type="text" name="address" id="address" placeholder="{{ __('Type Your Address') }}"
                                            @if(Auth::guard('web')->check()) value="{{ Auth::user()->address }}" @else value="" @endif>
                                        </div>
                                    </div>
                                    <div class="single-info-overview margin-top-30">
                                        <div class="single-info-input">
                                            <label class="info-title">{{ __('Order Note*') }} </label>
                                            <textarea class="form--control textarea--form" name="order_note" id="order_note" placeholder="{{ __('Type Order Note') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <input type="button" name="next" class="next action-button" value="Next" /> 
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                            </fieldset>
                            <!-- Confirmation -->
                            <fieldset class="padding-top-20 padding-bottom-100">
                                <input type="hidden" id="service_id" value="{{ $service_details_for_book->id }}">
                                <input type="hidden" id="seller_id" value="{{ $service_details_for_book->seller_id }}">
                                <div class="confirm-overview padding-top-30">
                                    <div class="overview-author overview-author-border">
                                        <div class="overview-flex-author">
                                            <div class="overview-thumb confirm-thumb">
                                                {!! render_image_markup_by_attachment_id($service_details_for_book->image,'','thumb') !!}
                                            </div>
                                            <div class="overview-contents">
                                                <h2 class="overview-title confirm-title"> <a
                                                        href="{{ route('service.list.details',$service_details_for_book->slug) }}">{{ $service_details_for_book->title }}</a> </h2>
                                                    @if($service_details_for_book->reviews->count() >= 1)
                                                    <span class="overview-review"> {{ __('Rating:') }} {{ round(optional($service_details_for_book->reviews)->avg('rating'),1) }}
                                                        <b>({{ optional($service_details_for_book->reviews)->count() }})</b> 
                                                    </span>
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="confirm-overview-left margin-top-30">
                                            <div class="single-confirm-overview">
                                                <div class="single-confirm margin-top-30">
                                                    <h3 class="titles">{{ __('Location ') }}</h3>
                                                    @if(Auth::guard('web')->check())
                                                      <span class="location details get_city_name">{{ optional(Auth::guard('web')->user()->city)->service_city }}</span>
                                                    @else 
                                                      <span class="location details get_city_name"></span>  
                                                    @endif
                                                    
                                                    @if(Auth::guard('web')->check())
                                                      <span class="location details get_area_name">{{ optional(Auth::guard('web')->user()->area)->service_area }}</span>
                                                    @else 
                                                      <span class="location details get_area_name"></span>  
                                                    @endif
                                                    
                                                    @if(Auth::guard('web')->check())
                                                      <span class="location details get_country_name">{{ optional(Auth::guard('web')->user()->country)->country }}</span>
                                                    @else 
                                                      <span class="location details get_country_name"></span>  
                                                    @endif
                                                </div>
                                                <div class="single-confirm margin-top-30">
                                                    <h3 class="titles">{{ __('Date & Time') }}</h3>
                                                    <span class="details available_date"></span>
                                                    <span class="details available_schedule"></span>
                                                </div>
                                            </div>
                                            <div class="booking-info padding-top-60">
                                                <h2 class="title">{{ __('Booking Information') }}</h2>
                                                <div class="booking-details">
                                                    <ul class="booking-list">
                                                        <li class="lists">
                                                            <span class="list-span"> {{ __('Name:') }} </span>
                                                            <span class="list-strong get_name"></span>
                                                        </li>
                                                        <li class="lists">
                                                            <span class="list-span">{{ __('Email:') }}</span>
                                                            <span class="list-strong get_email"></span>
                                                        </li>
                                                        <li class="lists">
                                                            <span class="list-span">{{ __('Phone: ') }}</span>
                                                            <span class="list-strong get_phone"></span>
                                                        </li>
                                                        <li class="lists">
                                                            <span class="list-span">{{ __('Post Code:') }}</span>
                                                            <span class="list-strong get_post_code"></span>
                                                        </li>
                                                        <li class="lists">
                                                            <span class="list-span">{{ __('Address:') }}</span>
                                                            <span class="list-strong get_address"></span>
                                                        </li>
                                                        <li class="lists">
                                                            <span class="list-span">{{ __('Order Note:') }}</span>
                                                            <span class="list-strong get_order_note"></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 margin-top-60">
                                        <div class="service-overview-summery">
                                            <h4 class="title">{{ get_static_option('service_booking_title') ?? __('Booking Summery') }}</h4>
                                            <div class="overview-summery-contents">
                                                <div class="single-summery">
                                                    <span class="summery-title">{{ get_static_option('service_appoinment_package_title') ?? __('Appointment Package Service') }}</span>
                                                    <div class="summery-list-all">
                                                        <ul class="summery-list ">
                                                            @foreach ($service_includes as $include)
                                                                <li class="list include_service_id_{{ $include->id }} include_service_list">
                                                                    <input type="hidden" class="includeServiceID" value="{{ $include->id }}">
                                                                    <span class="rooms">{{ $include->include_service_title }}</span>
                                                                    <span class="value-count include_service_quantity service_quantity_count" id="include_service_quantity_3_{{ $include->id }}">{{ $include->include_service_quantity }}</span>
                                                                    <span class="room-count">{{ amount_with_currency_symbol($include->include_service_price) }}</span>

                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <ul class="summery-result-list">
                                                            <li class="result-list">
                                                                <span class="rooms">
                                                                    {{ get_static_option('service_package_fee_title') ?? __('Package Fee') }}</span>
                                                                <span class="value-count package-fee">{{ amount_with_currency_symbol($service_details_for_book->price) }}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="single-summery">
                                                    <span class="summery-title">{{ get_static_option('service_extra_title') ?? __('Extra Service') }}</span>
                                                    <div class="summery-list-all">
                                                        <ul class="summery-list extra-service-list-2">
                                                            
                                                        </ul>
                                                        <ul class="summery-result-list result-border padding-bottom-20">
                                                            <li class="result-list">
                                                                <span class="rooms">{{ get_static_option('service_extra_title') ?? __('Extra Service') }}</span>
                                                                <span class="value-count extra-service-fee">$00</span>
                                                            </li>
                                                        </ul>
                                                        <ul class="summery-result-list result-border padding-bottom-20">
                                                            <li class="result-list">
                                                                <span class="rooms">{{ get_static_option('service_subtotal_title') ?? __('Subtotal') }}</span>
                                                                <span class="value-count service-subtotal">$00</span>
                                                            </li>
                                                        </ul>
                                                        <ul class="summery-result-list result-border padding-bottom-20">
                                                            <li class="result-list">
                                                                <span class="rooms"> {{ __('Tax(+)') }} <span>{{ $service_details_for_book->tax }}</span> %</span>
                                                                <span class="value-count tax-amount">$00</span>
                                                            </li>
                                                        </ul>
                                                        <ul class="summery-result-list">
                                                            <li class="result-list">
                                                                <span class="rooms"> <strong>{{ get_static_option('service_total_amount_title') ?? __('Total') }}</strong></span>
                                                                <span class="value-count total-amount total_amount_for_coupon" id="total_amount_for_coupon">$00</span>
                                                            </li>
                                                        </ul>
                                                        <ul class="summery-result-list">
                                                            <li class="result-list coupon_amount_for_apply_code"></li>
                                                        </ul>
                                                        <ul class="summery-result-list coupon_input_field">
                                                            <li class="result-list">
                                                                <input type="text" name="coupon_code" class="form-control coupon_code" placeholder="Enter Coupon Code">
                                                                <button class="apply-coupon">{{ __('Apply') }}</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="confirm-bottom-content">
                                                    <div class="confirm-payment payment-border">
                                                        <div class="single-checkbox">
                                                            <div class="checkbox-inlines">
                                                                <label class="checkbox-label" for="check2">
                                                                    {!! render_payment_gateway_for_form() !!}
                                                                </label>     
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="checkbox-inlines bottom-checkbox terms-and-conditions">
                                                        <input class="check-input" type="checkbox" id="check3">
                                                        <label class="checkbox-label" for="check3">{{ __('I agree with') }}
                                                             <a href="javascript:void(0)">{{ __('terms and conditions *') }}</a></label>
                                                    </div>
                                                </div>
                                                {{-- form inputs  --}}
                                               
                                                <input type="hidden" name="service_id" value="{{ $service_details_for_book->id }}">
                                                <input type="hidden" name="seller_id" value="{{ optional($service_details_for_book->seller)->id }}">
                                                
                                                <input type="hidden" name="date">
                                                <input type="hidden" name="schedule">
                                                <input type="hidden" id="payment_form_services" name="services[]">
                                                <input type="hidden" id="payment_form_additionals" name="additionals[]">
                                                <input type="hidden" name="order_note">
                                                <div class="btn-wrapper">
                                                <button type="submit" class="cmn-btn btn-appoinment btn-bg-1">{{ get_static_option('service_order_confirm_title') ?? __(' Pay & Confirm Your Order') }} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </section>

    
@endsection

@include('frontend.pages.services.service-book-js')
