@extends('backend.admin-master')

@section('site-title')
    {{__('Edit Category')}}
@endsection
@section('style')
    <x-media.css/>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.success/>
                <x-msg.error/>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h4 class="header-title">{{__('Edit Category')}}   </h4>
                            </div>
                            <div class="right-content">
                                <a class="btn btn-info btn-sm" href="{{route('admin.category')}}">{{__('All Categories')}}</a>
                            </div>
                        </div>
                        <form action="{{route('admin.category.edit',$category->id)}}" method="post" enctype="multipart/form-data" id="edit_category_form">
                            @csrf

                            <div class="tab-content margin-top-40">
                                
                                <div class="form-group">
                                    <label for="name">{{__('Name')}}</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{$category->name}}" placeholder="{{__('Name')}}">
                                </div>

                                <div class="form-group permalink_label">
                                    <label class="text-dark">{{__('Permalink * : ')}}
                                        <span id="slug_show" class="display-inline"></span>
                                        <span id="slug_edit" class="display-inline">
                                             <button class="btn btn-warning btn-sm slug_edit_button"> <i class="fas fa-edit"></i> </button>
                                            
                                            <input type="text" name="slug" class="form-control category_slug mt-2" value="{{$category->slug}}" style="display: none">
                                            <button class="btn btn-info btn-sm slug_update_button mt-2" style="display: none">{{__('Update')}}</button>
                                        </span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="icon" class="d-block">{{__('Category Icon')}}</label>
                                    <div class="btn-group icon">
                                        <button type="button" class="btn btn-primary iconpicker-component">
                                            <i class="{{$category->icon}}"></i>
                                        </button>
                                        <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                                data-selected="{{$category->icon}}" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">{{__('Toggle Dropdown')}}</span>
                                        </button>
                                        <div class="dropdown-menu"></div>
                                    </div>
                                    <input type="hidden" class="form-control" name="icon" id="edit_icon" value="{{$category->icon}}">
                                </div>

                                <div class="form-group">
                                    <label for="image">{{__('Upload Category Image')}}</label>
                                    <div class="media-upload-btn-wrapper">
                                        <div class="img-wrap">
                                            {!! render_image_markup_by_attachment_id($category->image,'','thumb') !!}
                                        </div>
                                        <input type="hidden" name="image">
                                        <button type="button" class="btn btn-info media_upload_form_btn"
                                                data-btntitle="{{__('Select Image')}}"
                                                data-modaltitle="{{__('Upload Image')}}" data-toggle="modal"
                                                data-target="#media_upload_modal">
                                            {{__('Upload Image')}}
                                        </button>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3 submit_btn">{{__('Submit ')}}</button>

                              </div>
                        </form>
                   </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection

@section('script')
<script>
    <x-icon-picker/> 
</script> 
<x-media.js />

<script>
    (function ($) {
        "use strict";

        $(document).ready(function () {
            //Permalink Code
                var sl =  $('.category_slug').val();
                var url = `{{url('/category/')}}/` + sl;
                var data = $('#slug_show').text(url).css('color', 'blue');

                $(document).on('keyup', '#name', function (e) {
                    var slug = $(this).val().trim().toLowerCase().split(' ').join('-');
                    var url = "{{url('/category')}}/" + slug;
                    $('.permalink_label').show();
                    var data = $('#slug_show').text(url).css('color', 'blue');
                    $('.category_slug').val(slug);

                });

                //Slug Edit Code
                $(document).on('click', '.slug_edit_button', function (e) {
                    e.preventDefault();
                    $('.category_slug').show();
                    $(this).hide();
                    $('.slug_update_button').show();
                });

                //Slug Update Code
                $(document).on('click', '.slug_update_button', function (e) {
                    e.preventDefault();
                    $(this).hide();
                    $('.slug_edit_button').show();
                    var update_input = $('.category_slug').val();
                    var slug = update_input.trim().toLowerCase().split(' ').join('-');
                    var url = `{{url('/category/')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.category_slug').hide();
                });

        });
    })(jQuery)
</script>
@endsection 


