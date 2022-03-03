@section('scripts')
    <script>
        (function($) {
            "use strict";

            $(document).ready(function() {
                let site_default_currency_symbol = '{{ site_currency_symbol() }}';

                function extra_service_calculate(){
                    let additional_total_price = 0; 
                    let additional_services = $("div.single-additional");

                    for (let i = 0; i < additional_services.length; i++) {
                        let service_data = $(additional_services[i]).find('.inc_dec_additional_service');
                        let service_count = service_data.find($('.room-count')).text();
                        let unit_price = service_data.find($('.value-count')).text().replace(site_default_currency_symbol, '');

                        additional_total_price += (service_count * unit_price);
                    }
                    $('.extra-service-fee').text(site_default_currency_symbol+additional_total_price);
                }

                function subtotal_calculate(){
                    let package_fee = parseInt($('.package-fee').text().replace(site_default_currency_symbol,''));
                    let extra_service_fee = parseInt($('.extra-service-fee').text().replace(site_default_currency_symbol,''));
                    let service_subtotal = package_fee+extra_service_fee;
                    $('.service-subtotal').text('$'+service_subtotal);
                }
                subtotal_calculate();

                function total_amount(){
                    tax_calculate()
                    let subtotal = parseFloat($('.service-subtotal').text().replace(site_default_currency_symbol,''));
                    let tax = parseFloat($('.tax-amount').text().replace(site_default_currency_symbol,''));
                    let total_amount = subtotal+tax;
                    
                    $('.total-amount').text(site_default_currency_symbol+total_amount);
                }
                total_amount()

                function tax_calculate(){
                    let subtotal = parseInt($('.service-subtotal').text().replace(site_default_currency_symbol,''));
                    let service_tax = parseInt($('.service-tax').text());
                    if(service_tax >0){
                        let tax_amount = (subtotal * service_tax)/100;
                        $('.tax-amount').text(site_default_currency_symbol+tax_amount);
                    }else{
                        let tax_amount = 0;
                        $('.tax-amount').text(site_default_currency_symbol+tax_amount);
                    }
                }
                 
                //location
                 // change country and get city
                $('#choose_service_country').on('change', function() {
                    var country_id = $(this).val();
                    var country_name = $('#choose_service_country option[value=' + country_id + ']').text();
                    $('.country_name').text(country_name);
                    getServiceCity(country_id)
                });

                fetchDefaultServiceCity();
                function fetchDefaultServiceCity(){
                    getServiceCity($('#choose_service_country').val())
                }

                function getServiceCity(countryId){
                    $.ajax({
                        method: 'post',
                        url: "{{ route('service.list.book.city') }}",
                        data: {
                            country_id: countryId
                        },
                        success: function(res) {
                            if (res.status == 'success') {
                                var alloptions = '';
                                var allCities = res.cities;
                                var userSelectedCity = '@if(Auth::guard('web')->check()){{  optional(Auth::guard('web')->user()->city)->id }} @endif'
                                $.each(allCities, function(index, value) {
                                    let selectedItem = userSelectedCity == value.id ? 'selected' : '';
                                    alloptions += "<option value='" + value.id + "' "+ selectedItem +" >" + value.service_city + "</option>";
                                });
                                $(".get_service_city").html(alloptions);
                                $('#choose_service_city').niceSelect('update');
                            }
                        }
                    })
                }
                
                // select city and area
                $('#choose_service_city').on('change', function() {
                    var city_id = $(this).val();
                    var city_name = $('#choose_service_city option[value=' + city_id + ']').text();
                    $('.city_name').text(city_name);
                    getServiceArea(city_id)
                });

                fetchDefaultServiceArea();
                function fetchDefaultServiceArea(){
                    getServiceArea($('#choose_service_city').val())
                }

                function getServiceArea(cityId){
                    $.ajax({
                        method: 'post',
                        url: "{{ route('service.list.book.area') }}",
                        data: {
                            city_id: cityId
                        },
                        success: function(res) {
                            if (res.status == 'success') {
                                var alloptions = '';
                                var allArea = res.areas;
                                var userSelectedCity = '@if(Auth::guard('web')->check()){{  optional(Auth::guard('web')->user()->area)->id }} @endif'
                                $.each(allArea, function(index, value) {
                                    let selectedItem = userSelectedCity == value.id ? 'selected' : '';
                                    alloptions += "<option value='" + value.id + "' "+ selectedItem +" >" + value.service_area + "</option>";
                                });
                                $(".get_service_area").html(alloptions);
                                $('#choose_service_area').niceSelect('update');
                            }
                        }
                    })
                }

                //show area
                $('#choose_service_area').on('change', function() {
                    var area_id = $(this).val();
                    var area_name = $('#choose_service_area option[value=' + area_id + ']').text();
                    $('.area_name').text(area_name);
                });


                //confirm-location
                $('.confirm-location .next').on('click', function() {

                    let city_name = $('.city_name').text();
                    let area_name = $('.area_name').text();
                    let country_name = $('.country_name').text();
                    
                    //set city,area and country in confirmation fieldset
                    $('.get_city_name').text(city_name);
                    $('.get_area_name').text(area_name);
                    $('.get_country_name').text(country_name);



                    if(city_name=='' || area_name=='' || country_name==''){
                        Command: toastr["warning"]("Please select city area & country.!", "Warning")
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        return false;
                    }else{
                        var current_fs, next_fs, previous_fs;
                        var opacity;
                        var current = 1;
                        var steps = $("fieldset").length;
                        current_fs = $(this).parent();
                        next_fs = $(this).parent().next();

                        $(".step-list li, .step-list-two li").eq($("fieldset").index(next_fs)).addClass(
                            "active");

                        next_fs.show();
                        current_fs.animate({
                            opacity: 0
                        }, {
                            step: function(now) {
                                opacity = 1 - now;

                                current_fs.css({
                                    'display': 'none',
                                    'position': 'relative'
                                });
                                next_fs.css({
                                    'opacity': opacity
                                });
                            },
                            duration: 500
                        });
                    }
                    
                })

                //location end 

                //Service start
                $(document).on('click', '.remove-service-list', function() {
                    let include_service_id = $(this).data('id');
                    $('.include_service_id_' + include_service_id).remove();

                    var include_total_price = 0;
                    var quantity = Number($(this).val());

                    $('#include_service_quantity_2_' + include_service_id).text(quantity);
                    $('#include_service_quantity_3_' + include_service_id).text(quantity);

                    if (isNaN(quantity)) {
                        alert('Please Enter Numbers Only');
                    } else {
                        let included_services = $("div.single-include");

                        for (let i = 0; i < included_services.length; i++) {
                            let service_data = $(included_services[i]).find('.inc_dec_include_service');
                            let service_count = Number(service_data.val());
                            let service_total_price = Number(service_data.data('price'));
                            include_total_price += (service_count * service_total_price);
                        }
                        
                        $('.package-fee').text('$'+include_total_price);
                        subtotal_calculate();
                        total_amount();
                    }

                })


                //Increment Decrement include service
                $(document).on('keyup click', '.inc_dec_include_service', function() {
                    var include_total_price = 0;
                    let include_service_id = $(this).data('id');
                    var quantity = Number($(this).val());

                    $('#include_service_quantity_2_' + include_service_id).text(quantity);
                    $('#include_service_quantity_3_' + include_service_id).text(quantity);

                    if (isNaN(quantity)) {
                        alert('Please Enter Numbers Only');
                    } else {
                        let included_services = $("div.single-include");

                        for (let i = 0; i < included_services.length; i++) {
                            let service_data = $(included_services[i]).find('.inc_dec_include_service');
                            let service_count = Number(service_data.val());
                            let service_total_price = Number(service_data.data('price'));
                            include_total_price += (service_count * service_total_price);
                        }
                        
                        $('.package-fee').text('$'+include_total_price);
                        subtotal_calculate();
                        total_amount();
                    }
                })

                //Upgrade order with extras
                $(document).on('click','.extra-services .check-input',function(){
                    
                    let additional_service_id = $(this).val();
                    let service_name = $('label[for=' + additional_service_id + ']').text();
                    let unit_price = $('span[price=' + additional_service_id + ']').text().replace(site_default_currency_symbol, '');
                    let quantity = $('#additional_service_quantity_'+additional_service_id).val();

                    if($(this).is(":checked")) {
                        $('.extra-service-list').append('<div class="single-additional">\
                            <li class="list inc_dec_additional_service" id="additional_service_id_'+additional_service_id+'">\
                                <span class="rooms">'+ service_name +'</span>\
                                <span class="room-count service_quantity_count">'+quantity+'</span>\
                                <span class="value-count">$'+unit_price+ '</span>\
                            </li>\
                        </div>');

                        $('.extra-service-list-2').append('<div class="single-additional-2">\
                            <li class="list inc_dec_additional_service additional_service_list" id="additional_service_id_2_'+additional_service_id+'">\
                                <input type="hidden" class="additionalServiceID" value="'+additional_service_id+'">\
                                <span class="rooms">'+ service_name +'</span>\
                                <span class="room-count additional_service_quantity service_quantity_count">'+quantity+'</span>\
                                <span class="value-count">$'+unit_price+ '</span>\
                            </li>\
                        </div>');  
                        extra_service_calculate();
                        subtotal_calculate();
                        total_amount();
                        tax_calculate()
                    }else{
                        $(".single-additional #additional_service_id_"+additional_service_id).remove();
                        $(".single-additional-2 #additional_service_id_2_"+additional_service_id).remove();
                         extra_service_calculate();
                         subtotal_calculate();
                         total_amount();                    
                    }  
                })

                $(document).on('keyup click', '.inc_dec_additional_service', function() {
                    let additional_service_id = $(this).data('id');
                    var quantity = Number($(this).val());

                    $('.single-additional #additional_service_id_'+additional_service_id+' .room-count').text(quantity);
                    $('.single-additional-2 #additional_service_id_2_'+additional_service_id+' .room-count').text(quantity);

                    if (isNaN(quantity)) {
                        alert('Please Enter Numbers Only');
                    } else {
                        extra_service_calculate(); 
                        subtotal_calculate();
                        total_amount();
                        tax_calculate()
                    }
                })

                //confirm-service
                $('.confirm-service .next').on('click', function() {
                    var current_fs, next_fs, previous_fs;
                    var opacity;
                    var current = 1;
                    var steps = $("fieldset").length;
                    current_fs = $(this).parent();
                    next_fs = $(this).parent().next();

                    $(".step-list li, .step-list-two li").eq($("fieldset").index(next_fs)).addClass(
                        "active");

                    next_fs.show();
                    current_fs.animate({
                        opacity: 0
                    }, {
                        step: function(now) {
                            opacity = 1 - now;

                            current_fs.css({
                                'display': 'none',
                                'position': 'relative'
                            });
                            next_fs.css({
                                'opacity': opacity
                            });
                        },
                        duration: 500
                    });
                })

                //Service end

                //Date and time
                function GetDates(startDate, daysToAdd) {
                    var aryDates = [];
                    for (var i = 0; i <= daysToAdd; i++) {
                        var currentDate = new Date();
                        currentDate.setDate(startDate.getDate() + i);
                        aryDates.push(DayAsString(currentDate.getDay()) + ", " + currentDate.getDate() + " " + MonthAsString(currentDate.getMonth()) + " " + currentDate.getFullYear());
                    }
                    return aryDates;
                }

                function MonthAsString(monthIndex) {
                    var d = new Date();
                    let month = {0:'Jan', 1:'Feb', 2:'Mar', 3:'Apr', 4:'May', 5:'Jun', 6:'Jul', 7:'Aug', 8:'Sep', 9:'Oct', 10:'Nov', 11:'Dec'}
                    return month[monthIndex];
                }

                function DayAsString(dayIndex) {
                    let weekdays = {0: 'Sun',1: 'Mon',2: 'Tue',3: 'Wed', 4: 'Thu',5: 'Fri',6: 'Sat',
                    }
                    return weekdays[dayIndex];
                }

                var startDate = new Date();
                var aryDates = GetDates(startDate, '{{ $days_count }}');
                //show next selected days
                for(var i=0; i<'{{ $days_count }}'; i++){
                    $('.date-overview .show-date').append('<li class="list"> <a href="javascript:void(0)" class="get-date">'+aryDates[i]+'</a> </li>');
                }

                //get day and find schedule for that day
                $(document).on('click','.get-date',function(){
                    let day_date = $(this).text();
                    let day = day_date.split(',')[0];
                    let seller_id = $('.seller-id-for-schedule').text();

                    $.ajax({
                        url:"{{ route('service.schedule.by.day') }}",
                        method:'post',
                        data:{day:day,seller_id:seller_id},
                        success:function(res){
                            if(res.status=='success'){
                                let all_lists = '';
                                let all_schedules = res.schedules;
                                $.each(all_schedules, function(index, value) {
                                    all_lists += '<li class="list"><a href="javascript:void(0)" class="get-schedule">'+value.schedule+'</a></li>';
                                });
                                $(".show-schedule").html(all_lists);
                            }if(res.status=='no schedule'){
                                $(".show-schedule").html('<div class="alert alert-warning"><li class="list">{{ __("Schedule not available") }}</li></div>');
                            }
                        }
                    })
                })

                //get available date
                var available_date='';
                $(document).on('click','.get-date',function(){
                    available_date = $(this).text();
                    //set value in confirmation fieldset
                    $('.confirm-overview-left .available_date').text(available_date);     
                })

                //get available schedule
                var available_schedule ='';
                $(document).on('click','.get-schedule',function(){
                    available_schedule = $(this).text();
                    //set value in confirmation fieldset
                    $('.confirm-overview-left .available_schedule').text(available_schedule);
                })

                //confirm-date-time
                $('.confirm-date-time .next').on('click',function(){
                    if(available_date=='' || available_schedule==''){
                        Command: toastr["warning"]("Please select date and schedule.!", "Warning")
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        return false;
                    }else{
                        var current_fs, next_fs, previous_fs;
                        var opacity;
                        var current = 1;
                        var steps = $("fieldset").length;
                        current_fs = $(this).parent();
                        next_fs = $(this).parent().next();

                        $(".step-list li, .step-list-two li").eq($("fieldset").index(next_fs)).addClass("active");

                        next_fs.show();
                        current_fs.animate({ opacity: 0 }, {
                            step: function(now) {
                                opacity = 1 - now;

                                current_fs.css({
                                    'display': 'none',
                                    'position': 'relative'
                                });
                                next_fs.css({ 'opacity': opacity });
                            },
                            duration: 500
                        });
                    }  
                })

                //confirm-information
                $('.confirm-information .next').on('click',function(){
                    let name =  $('#name').val();
                    let email = $('#email').val();
                    let phone = $('#phone').val();
                    let post_code = $('#post_code').val();
                    let address =   $('#address').val();
                    let order_note = $('#order_note').val();
                    
                    //set value in confirmation fieldset
                    $('.booking-details .get_name').text(name);
                    $('.booking-details .get_email').text(email);
                    $('.booking-details .get_phone').text(phone);
                    $('.booking-details .get_post_code').text(post_code);
                    $('.booking-details .get_address').text(address);
                    $('.booking-details .get_order_note').text(order_note);
                    if(name=='' || email=='' || phone=='' || post_code=='' || address==''){
                        Command: toastr["warning"]("Please fill all fields.!", "Warning")
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        return false;
                    }else{
                        var current_fs, next_fs, previous_fs;
                        var opacity;
                        var current = 1;
                        var steps = $("fieldset").length;
                        current_fs = $(this).parent();
                        next_fs = $(this).parent().next();

                        $(".step-list li, .step-list-two li").eq($("fieldset").index(next_fs)).addClass("active");

                        next_fs.show();
                        current_fs.animate({ opacity: 0 }, {
                            step: function(now) {
                                opacity = 1 - now;

                                current_fs.css({
                                    'display': 'none',
                                    'position': 'relative'
                                });
                                next_fs.css({ 'opacity': opacity });
                            },
                            duration: 500
                        });
                    }  
                })

                //Order Confirm
                $(document).on('submit','.ms-order-form',function(e){

                    if(!$('.terms-and-conditions .check-input').is(":checked")){
                        //error msg 
                        Command: toastr["warning"]("Please agree with terms and conditions.!", "Warning")
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        return false;
                    }
                    if($('input[name="selected_payment_gateway"]').val() == ''){
                        //error msg 
                        Command: toastr["warning"]("Please select payment gateway.!", "Warning")
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        return false;
                    }

                    let formContainer = $('#msform');

                    let available_date = $('.available_date').text();
                    formContainer.find('input[name=date]').val(available_date);
                    let available_schedule = $('.available_schedule').text();
                    formContainer.find('input[name=schedule]').val(available_schedule);
                    let coupon_code = $('.coupon_code').val();
                    formContainer.find('input[name=coupon_code]').val(coupon_code);

                    let services = [];
                    let included_services = $("li.include_service_list");
                    
                    for (let i = 0; i < included_services.length; i++) {
                        let include_service_quantity = $(included_services[i]).find('.include_service_quantity').text();
                        let include_service_id = $(included_services[i]).find('.includeServiceID').val();
                        services.push({
                            id: include_service_id,
                            quantity: include_service_quantity
                        })
                        $('#msform').append('<input type="hidden" name="services['+i+'][id]" value="'+include_service_id+'"/>');
                        $('#msform').append('<input type="hidden" name="services['+i+'][quantity]" value="'+include_service_quantity+'"/>');
                    }
                    
                    let additionals = [];
                    let additional_services = $("li.additional_service_list");

                    for (let i = 0; i < additional_services.length; i++) {
                        let additional_service_quantity = $(additional_services[i]).find('.additional_service_quantity').text();
                        let additional_service_id = $(additional_services[i]).find('.additionalServiceID').val();
                        additionals.push({
                            id: additional_service_id,
                            quantity: additional_service_quantity
                        })
                        $('#msform').append('<input type="hidden" name="additionals['+i+'][id]" value="'+additional_service_id+'"/>');
                        $('#msform').append('<input type="hidden" name="additionals['+i+'][quantity]" value="'+additional_service_quantity+'"/>');
                    }

                });
                
                //apply coupon code
                $(document).on('click','.apply-coupon',function(e){
                    e.preventDefault();
                    let total_amount = $('.total_amount_for_coupon').text().replace(site_default_currency_symbol,'');
                    let coupon_code = $('.coupon_code').val();
                    let seller_id = $('#seller_id').val();

                    $.ajax({
                            url:"{{ route('service.coupon.apply') }}",
                            method:"get",
                            data:{
                                coupon_code:coupon_code,
                                total_amount:total_amount,
                                seller_id:seller_id, 
                            },
                            success:function(res){
                                if (res.status == 'success') {
                                    let coupon_amount = res.coupon_amount;
                                    let new_total = (total_amount-coupon_amount)*1;
                                    $('#total_amount_for_coupon').text(site_default_currency_symbol+new_total.toFixed(2));
                                    $('.coupon_input_field').hide();
                                    $('.coupon_amount_for_apply_code').html('<strong>Coupon Discount</strong>' + site_default_currency_symbol+coupon_amount.toFixed(2))
                                }
                                if (res.status == 'invalid') {
                                    Command: toastr["warning"]("Coupon is invalid.!", "Warning")
                                    toastr.options = {
                                        "closeButton": true,
                                        "debug": false,
                                        "newestOnTop": false,
                                        "progressBar": true,
                                        "positionClass": "toast-top-right",
                                        "preventDuplicates": false,
                                        "onclick": null,
                                        "showDuration": "300",
                                        "hideDuration": "1000",
                                        "timeOut": "5000",
                                        "extendedTimeOut": "1000",
                                        "showEasing": "swing",
                                        "hideEasing": "linear",
                                        "showMethod": "fadeIn",
                                        "hideMethod": "fadeOut"
                                    }
                                    return false;
                                }
                                if (res.status == 'expired') {
                                    Command: toastr["warning"]("Coupon already expired.!", "Warning")
                                    toastr.options = {
                                        "closeButton": true,
                                        "debug": false,
                                        "newestOnTop": false,
                                        "progressBar": true,
                                        "positionClass": "toast-top-right",
                                        "preventDuplicates": false,
                                        "onclick": null,
                                        "showDuration": "300",
                                        "hideDuration": "1000",
                                        "timeOut": "5000",
                                        "extendedTimeOut": "1000",
                                        "showEasing": "swing",
                                        "hideEasing": "linear",
                                        "showMethod": "fadeIn",
                                        "hideMethod": "fadeOut"
                                    }
                                    return false;
                                }
                                if (res.status == 'notapplicable') {
                                    Command: toastr["warning"]("Coupon is not applicable for this service.!", "Warning")
                                    toastr.options = {
                                        "closeButton": true,
                                        "debug": false,
                                        "newestOnTop": false,
                                        "progressBar": true,
                                        "positionClass": "toast-top-right",
                                        "preventDuplicates": false,
                                        "onclick": null,
                                        "showDuration": "300",
                                        "hideDuration": "1000",
                                        "timeOut": "5000",
                                        "extendedTimeOut": "1000",
                                        "showEasing": "swing",
                                        "hideEasing": "linear",
                                        "showMethod": "fadeIn",
                                        "hideMethod": "fadeOut"
                                    }
                                    return false;
                                }
                                if (res.status == 'emptycoupon') {
                                    Command: toastr["warning"]("Please enter your coupon.!", "Warning")
                                    toastr.options = {
                                        "closeButton": true,
                                        "debug": false,
                                        "newestOnTop": false,
                                        "progressBar": true,
                                        "positionClass": "toast-top-right",
                                        "preventDuplicates": false,
                                        "onclick": null,
                                        "showDuration": "300",
                                        "hideDuration": "1000",
                                        "timeOut": "5000",
                                        "extendedTimeOut": "1000",
                                        "showEasing": "swing",
                                        "hideEasing": "linear",
                                        "showMethod": "fadeIn",
                                        "hideMethod": "fadeOut"
                                    }
                                    return false;
                                }
                                
                                
                            }
                    });
                })

                //select payment gateway 
                $(document).on('click', '.payment_getway_image ul li',function(){
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');
                    let payment_gateway_name = $(this).data('gateway');
                    $('#msform input[name=selected_payment_gateway]').val(payment_gateway_name);

                });

            });
        })(jQuery);
    </script>
@endsection