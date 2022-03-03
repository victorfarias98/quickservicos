@extends('frontend.user.seller.seller-master')
@section('site-title')
    {{__('Edit Service Attributes')}}
@endsection

@section('style')
    <x-media.css/>
@endsection

@section('content')
  
    <x-frontend.seller-buyer-preloader/>

    <!-- Dashboard area Starts -->
    <div class="body-overlay"></div>
    <div class="dashboard-area dashboard-padding">
        <div class="container-fluid">
            <div class="dashboard-contents-wrapper">
                <div class="dashboard-icon">
                    <div class="sidebar-icon">
                        <i class="las la-bars"></i>
                    </div>
                </div>
                @include('frontend.user.seller.partials.sidebar')
                <div class="dashboard-right">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="dashboard-settings margin-top-40">
                                <h2 class="dashboards-title"> {{__('Edit Service Attributes')}} </h2>
                            </div>
                        </div>
                    </div>
                    <x-error-message/>
                    <form action="{{route('seller.edit.service.attribute',$service->id)}}" method="post">
                    @csrf
                    <div class="row">  
                        <div class="col-xl-4 margin-top-50">
                            <div class="edit-service-wrappers">
                                <div class="dashboard-edit-thumbs">
                                    {!! render_image_markup_by_attachment_id($service->image) !!}
                                </div>
                                <div class="content-edit margin-top-40">
                                    <h4 class="title"> {{$service->title}} </h4>
                                    <p class="edit-para"> {{$service->description}} </p>
                                </div>
                                <div class="single-dashboard-input">
                                    <div class="single-info-input margin-top-50">
                                        <label class="info-title"> {{__('Service Price')}}</label>
                                        <input class="form--control" type="text" name="price" id="service_total_price" value="{{$service->price}}">
                                    </div>
                                </div>

                                <div class="btn-wrapper margin-top-40">
                                    <button type="submit" class="cmn-btn btn-bg-1">{{ __('Update Attributes') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8 margin-top-50">
                            
                            <div class="single-settings">
                                <h4 class="input-title"> {{__('Whats Included This Package')}} </h4>
                                <div class="append-additional-includes">
                                    @foreach($service_includes as $include)
                                    <div class="single-dashboard-input what-include-element">
                                        <input type="hidden" name="service_include_id[]" value="{{ $include->id }}">
                                        <div class="single-info-input margin-top-20">
                                            <label>{{ __('Title') }}</label>
                                            <input class="form--control" type="text" name="include_service_title[]" placeholder="{{__('Service tilte')}}" value="{{$include->include_service_title}}">
                                        </div>
                                        <div class="single-info-input margin-top-20">
                                            <label>{{ __('Unit Price') }}</label>
                                            <input class="form--control include-price" type="text" name="include_service_price[]" placeholder="{{__('Add Price')}}" value="{{$include->include_service_price}}">
                                        </div>
                                        <div class="single-info-input margin-top-20">
                                            <label>{{ __('Quantity') }}</label>
                                            <input class="form--control numeric-value" type="text" name="include_service_quantity[]" placeholder="{{__('Add Quantity')}}" value="{{$include->include_service_quantity}}" readonly>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            @if($service_additionals->count() >= 1)
                                <div class="single-settings margin-top-40">
                                    <h4 class="input-title"> {{__('Aditional Services')}} </h4>
                                    <div class="append-additional-services">
                                        @foreach($service_additionals as $additional) 
                                            <div class="single-dashboard-input additional-services">
                                                <input type="hidden" name="service_additional_id[]" value="{{ $additional->id }}">
                                                <div class="single-info-input margin-top-20">
                                                    <label>{{ __('Title') }}</label>
                                                    <input class="form--control" type="text" name="additional_service_title[]" placeholder="{{__('Service tilte')}}"  value="{{$additional->additional_service_title}}">
                                                </div>
                                                <div class="single-info-input margin-top-20">
                                                    <label>{{ __('Unit Price') }}</label>
                                                    <input class="form--control numeric-value" type="text" name="additional_service_price[]" placeholder="{{__('Add Price')}}" value="{{$additional->additional_service_price}}">
                                                </div>
                                                <div class="single-info-input margin-top-20">
                                                    <label>{{ __('Quantity') }}</label>
                                                    <input class="form--control numeric-value" type="text" name="additional_service_quantity[]" placeholder="{{__('Add Quantity')}}" value="{{$additional->additional_service_quantity}}" readonly>
                                                </div>

                                                <div class="single-info-input margin-top-30">
                                                    <div class="form-group ">
                                                        <div class="media-upload-btn-wrapper">
                                                            <div class="img-wrap">
                                                                {!! render_image_markup_by_attachment_id($additional->additional_service_image) !!}
                                                            </div>
                                                            <input type="hidden" name="image[]">
                                                            <button type="button" class="btn btn-info media_upload_form_btn"
                                                                    data-btntitle="{{__('Select Image')}}"
                                                                    data-modaltitle="{{__('Upload Image')}}" data-toggle="modal"
                                                                    data-target="#media_upload_modal">
                                                                {{__('Upload Image')}}
                                                            </button>
                                                            <small>{{ __('image format: jpg,jpeg,png')}}</small> <br>
                                                            <small>{{ __('recomended size 78x78') }}</small>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($service_benifits->count() >= 1)
                                <div class="single-settings margin-top-40">
                                    <h4 class="input-title"> {{__('Benifit Of This Package')}} </h4>
                                    <div class="append-benifits">
                                        @foreach($service_benifits as $benifit) 
                                        <div class="single-dashboard-input benifits">
                                            <input type="hidden" name="service_benifit_id[]" value="{{ $benifit->id }}">
                                            <div class="single-info-input margin-top-20">
                                                <input class="form--control" type="text" name="benifits[]" placeholder="{{__('Type Here')}}" value="{{$benifit->benifits}}">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    <x-media.markup :type="'web'"/>
    <!-- Dashboard area end -->
@endsection  

@section('scripts')

<x-media.js :type="'web'"/>

 <script>
    (function ($) {
        'use strict'
        $(document).ready(function() {  
            //total price
            $(document).on("change", ".include-price", function() {
                var sum = 0;
                $(".include-price").each(function() {
                    if(isNaN($(this).val())){
                       alert('Please Enter Numeric Value only')  
                    }else{
                        sum += +$(this).val();
                    }
                });
                $("#service_total_price").val(sum);
            }); 

           //include quantity
           $(document).on("change", ".numeric-value", function() {
                if(isNaN($(this).val())){
                    alert('Please Enter Numeric Value only')  
                }
            }); 

        })
    })(jQuery)
 </script>
@endsection