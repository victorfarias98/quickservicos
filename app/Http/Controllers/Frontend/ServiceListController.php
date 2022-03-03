<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Serviceinclude;
use App\Serviceadditional;
use App\Servicebenifit;
use App\Service;
use App\Country;
use App\Day;
use App\Order;
use App\OrderAdditional;
use App\Category;
use App\Helpers\FlashMsg;
use App\Subcategory;
use App\OrderInclude;
use App\Schedule;
use App\ServiceCity;
use App\ServiceArea;
use DB;
use Auth;
use App\Mail\OrderMail;
use App\Review;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Notifications\OrderNotification;
use App\ServiceCoupon;
use App\AdminCommission;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;
use Str;


class ServiceListController extends Controller
{

    private const CANCEL_ROUTE = 'frontend.order.payment.cancel';
    private const SUCCESS_ROUTE = 'frontend.order.payment.success';
    
    protected function cancel_page(){
        return redirect()->route('frontend.order.payment.cancel.static');
    }
    public function order_payment_cancel_static()
    {
        return view('frontend.payment.payment-cancel-static');
    }
    public function order_payment_success($id)
    {
        $order_details = Order::find(substr($id,30,-30));
        return view('frontend.payment.payment-success')->with(['order_details' => $order_details]);
    }


    public function serviceDetails($slug)
    {
        $service_details = Service::with('seller')->where('slug', $slug)->firstOrFail();
        $service_includes = ServiceInclude::where('service_id', $service_details->id)->get();
        $service_additionals = ServiceAdditional::where('service_id', $service_details->id)->get();
        $service_benifits = Servicebenifit::where('service_id', $service_details->id)->get();

        $another_service = Service::with('reviews')->where(['seller_id' => $service_details->seller_id, 'status' => 1, 'is_service_on' => 1])->inRandomOrder()->take(2)->get();

        //if buyer buy a service only add review on that particular service
        if (Auth::guard('web')->check()) {
            $buyer_order_services = Order::select('service_id', 'buyer_id')->where('buyer_id', Auth::guard('web')->user()->id)->get();
        } else {
            $buyer_order_services = '';
        }

        $service_reviews = Review::where('service_id', $service_details->id)->get();
        $completed_order = Order::where('seller_id', $service_details->seller_id)->where('status', 2)->count();
        $cancelled_order = Order::where('seller_id', $service_details->seller_id)->where('status', 4)->count();
        $seller_since = User::select('created_at')->where('id', $service_details->seller_id)->where('user_status', 1)->first();

        $order_completion_rate = 0;
        if ($completed_order > 0 || $cancelled_order > 0) {
            $order_completion_rate = $completed_order / ($completed_order + $cancelled_order) * 100;
        }

        $seller_rating = Review::where('seller_id', $service_details->seller_id)->avg('rating');
        $seller_rating_percentage_value = $seller_rating * 20;

        $service_rating = Review::where('service_id', $service_details->id)->avg('rating');

        $service_view = Service::select('view')->where('id', $service_details->id)->first();
        $view_count = $service_view->view + 1;
        Service::where('id', $service_details->id)->update([
            'view' => $view_count,
        ]);


        return view(
            'frontend.pages.services.service-details',
            compact(
                'service_details',
                'service_includes',
                'service_additionals',
                'service_benifits',
                'another_service',
                'buyer_order_services',
                'service_reviews',
                'completed_order',
                'seller_since',
                'order_completion_rate',
                'service_rating',
                'seller_rating_percentage_value'
            )
        );
    }

    public function serviceBook($slug)
    {
        $service_details_for_book = Service::where(['slug' => $slug, 'status' => 1, 'is_service_on' => 1])->firstOrFail();
        $days_count = Day::select('total_day')->where('seller_id',$service_details_for_book->seller_id)->first(); 
        $days_count =$days_count->total_day;

        $cities = ServiceCity::where('status', 1)->get();
        $areas = ServiceArea::where('status', 1)->get();
        $countries = Country::where('status', 1)->get();

        $service_includes = ServiceInclude::where('service_id', $service_details_for_book->id)->get();
        $service_additionals = ServiceAdditional::where('service_id', $service_details_for_book->id)->get();
        $service_benifits = Servicebenifit::where('service_id', $service_details_for_book->id)->get();

        return view('frontend.pages.services.service-book', compact(
            'cities', 
            'areas', 
            'countries',
            'service_details_for_book',
            'service_includes',
            'service_additionals',
            'service_benifits',
            'days_count'
            ));
    }

    //get area by city
    public function serviceBookGetCity(Request $request)
    {
        $cities = ServiceCity::where('country_id', $request->country_id)->where('status', 1)->get();
        return response()->json([
            'status' => 'success',
            'cities' => $cities,
        ]);
    }

    //get area by city
    public function serviceBookGetArea(Request $request)
    {
        $areas = ServiceArea::where('service_city_id', $request->city_id)->where('status', 1)->get();
        return response()->json([
            'status' => 'success',
            'areas' => $areas,
        ]);
    }

    //get schedule by seller 
    public function scheduleByDay(Request $request)
    {
        if ($request->ajax()) {
            $day = Day::select('id', 'day')
            ->where('day', $request->day)
            ->where('seller_id', $request->seller_id)
            ->first();

            if (!is_null($day)) {
                $schedules = Schedule::select('schedule')
                ->where('seller_id', $request->seller_id)
                ->where('day_id', $day->id)
                ->get();
                
                if($schedules->count() >= 1){
                    return response()->json([
                        'status' => 'success',
                        'schedules' => $schedules,
                        'day' => $day,
                    ]);
                }
                return response()->json([
                    'status' => 'no schedule',
                ]);
            }
            return response()->json([
                'status' => 'no schedule',
            ]);
        }
    }

    public function couponApply(Request $request)
    {
        if(empty($request->coupon_code)){
            return response()->json([
                'status' => 'emptycoupon',
                'msg' => 'Please Enter Your Coupon Code',
            ]);
        }
        

        $coupon_code = ServiceCoupon::where('code',$request->coupon_code)->first();
        $current_date = date('Y-m-d');

        
        if(!empty($coupon_code)){

            if($coupon_code->seller_id != $request->seller_id){
                return response()->json([
                    'status' => 'notapplicable',
                    'msg' => 'Coupon is not Applicable for this Service',
                ]);
            } 

            if($coupon_code->code == $request->coupon_code && $coupon_code->expire_date > $current_date){

                if($coupon_code->discount_type == 'percentage'){
                    $coupon_amount = ($request->total_amount * $coupon_code->discount)/100;
                    return response()->json([
                        'status' => 'success',
                        'coupon_amount' => $coupon_amount,
                    ]);
                }else{
                    $coupon_amount = $coupon_code->discount;
                    return response()->json([
                        'status' => 'success',
                        'coupon_amount' => $coupon_amount,
                    ]);
                }
            }
        
            if($coupon_code->expire_date < $current_date ){
                return response()->json([
                    'status' => 'expired',
                    'msg' => 'Coupon is Expired',
                ]);
            }

        }else{
            return response()->json([
                'status' => 'invalid',
                'msg' => 'Coupon is Invalid',
            ]);
        }
            
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|max:191',
            'email' => 'required|max:191',
            'phone' => 'required|max:191',
            'address' => 'required|max:191',
            'choose_service_city' => 'required',
            'choose_service_area' => 'required',
            'choose_service_country' => 'required',
            'date' => 'required|max:191',
            'schedule' => 'required|max:191',
            'services' => 'required|array',
            'services.*.id' => 'required|exists:serviceincludes',
            'services.*.quantity' => 'required|numeric',
        ]);

        $commission =AdminCommission::first();
        
        if($request->selected_payment_gateway=='cash_on_delivery'){
            $payment_status='pending';
        }else{
            $payment_status='';
        }


        if (empty($request->seller_id)){
            \Toastr::error('Seller Id missing, please try another another seller services');
            return back();
        }
        
        Order::create([
            'service_id' => $request->service_id,
            'seller_id' => $request->seller_id,
            'buyer_id' => Auth::guard('web')->check() ? Auth::guard('web')->user()->id : NULL,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'post_code' => $request->post_code,
            'address' => $request->address,
            'city' => $request->choose_service_city,
            'area' => $request->choose_service_area,
            'country' => $request->choose_service_country,
            'date' => $request->date,
            'schedule' => $request->schedule,
            'package_fee' => 0,
            'extra_service' => 0,
            'sub_total' => 0,
            'tax' => 0,
            'total' => 0,
            'commission_type' => $commission->commission_charge_type,
            'commission_charge' => $commission->commission_charge,
            'status' => 0,
            'order_note' => $request->order_note, 
            'payment_gateway' => $request->selected_payment_gateway,
            'payment_status' => $payment_status,
        ]);

        $last_order_id = DB::getPdo()->lastInsertId();

        $servs = [];
        $service_ids = [];
        $package_fee = 0;

        if (isset($request->services) && is_array($request->services)) {
            foreach ($request->services as $key => $service) {
                $service_ids[] = $service['id'];
            }

            $included_services = Serviceinclude::whereIn('id', $service_ids)->get();

            foreach ($request->services as $key => $requested_service) {
                $service = $included_services->find($requested_service['id']);
                $servs[] = [
                    'id' => $service->id,
                    'title' => $service->include_service_title,
                    'unit_price' => $service->include_service_price,
                    'quantity' => $requested_service['quantity'],
                ];

                $package_fee += $requested_service['quantity'] * $service->include_service_price;

                OrderInclude::create([
                    'order_id' => $last_order_id,
                    'title' => $service->include_service_title,
                    'price' => $service->include_service_price,
                    'quantity' => $requested_service['quantity'],
                ]);
            }
        }


        $addis = [];
        $additional_ids = [];
        $extra_service = 0;

        if($request->additionals['0'] != NULL){
            if (isset($request->additionals) && is_array($request->additionals)) {
                foreach ($request->additionals as $key => $additional) {
                    $additional_ids[] = $additional['id'];
                }
    
                $additional_services = Serviceadditional::whereIn('id', $additional_ids)->get();
    
                foreach ($request->additionals as $key => $requested_additional) {
                    $service = $additional_services->find($requested_additional['id']);
                    $addis[] = [
                        'id' => $service->id,
                        'title' => $service->additional_service_title,
                        'unit_price' => $service->additional_service_price,
                        'quantity' => $requested_additional['quantity'],
                    ];
    
                    $extra_service += $requested_additional['quantity'] * $service->additional_service_price;
    
                    OrderAdditional::create([
                        'order_id' => $last_order_id,
                        'title' => $service->additional_service_title,
                        'price' => $service->additional_service_price,
                        'quantity' => $requested_additional['quantity'],
                    ]);
                }
            }
        }
        

        $sub_total = 0;
        $total = 0;
        $tax = Service::select('tax')->where('id', $request->service_id)->first();
        $sub_total = $package_fee + $extra_service;
        $tax_amount = ($sub_total * $tax->tax) / 100;
        $total = $sub_total + $tax_amount;
 
        //calculate coupon amount
        $coupon_code = '';
        $coupon_type = '';
        $coupon_amount = 0;

        if(!empty($request->coupon_code)){
            $coupon_code = ServiceCoupon::where('code',$request->coupon_code)->first();
            $current_date = date('Y-m-d');
            if(!empty($coupon_code)){
                if($coupon_code->seller_id == $request->seller_id){
                    if($coupon_code->code == $request->coupon_code && $coupon_code->expire_date > $current_date){
                        if($coupon_code->discount_type == 'percentage'){
                            $coupon_amount = ($total * $coupon_code->discount)/100;
                            $total = $total-$coupon_amount;
                            $coupon_code = $request->coupon_code;
                            $coupon_type = 'percentage';
                        }else{
                            $coupon_amount = $coupon_code->discount;
                            $total = $total-$coupon_amount;
                            $coupon_code = $request->coupon_code;
                            $coupon_type = 'amount';
                        }
                    }else{
                        $coupon_code = '';
                    }
                }else{
                    $coupon_code = '';
                } 
            }
        }

        //commission amount 
        $commission_amount = 0;
        if($commission->commission_charge_type=='percentage'){
           $commission_amount = ($sub_total*$commission->commission_charge)/100;
        }else{
            $commission_amount = $commission->commission_charge;
        }

        Order::where('id', $last_order_id)->update([
            'package_fee' => $package_fee,
            'extra_service' => $extra_service,
            'sub_total' => $sub_total,
            'tax' => $tax_amount,
            'total' => $total,
            'coupon_code' => $coupon_code,
            'coupon_type' => $coupon_type,
            'coupon_amount' => $coupon_amount,
            'commission_amount' => $commission_amount,
        ]);


        //Send order notification to seller
        $seller = User::where('id',$request->seller_id)->first();
        $buyer_id = Auth::guard('web')->check() ? Auth::guard('web')->user()->id : NULL;
        $order_message = __('You have a new order');
        $seller->notify(new OrderNotification($last_order_id,$request->service_id, $request->seller_id, $buyer_id,$order_message));
        

        if(Auth::guard('web')->check()){
            $user_name = Auth::guard('web')->user()->name;
            $user_email = Auth::guard('web')->user()->email;
        }else{
            $user_name = $request->name;
            $user_email = $request->email;
        }
        
        $get_service_id_from_last_order = Order::select('service_id')->where('id',$last_order_id)->first();
        $title = Str::limit(optional($get_service_id_from_last_order->service)->title,20);
        $description = Str::limit(optional($get_service_id_from_last_order->service)->description,30);

        if ($request->selected_payment_gateway === 'cash_on_delivery') {
                $order_details = Order::find($last_order_id);
                $random_order_id_1 = Str::random(30);
                $random_order_id_2 = Str::random(30);
                $new_order_id = $random_order_id_1.$last_order_id.$random_order_id_2;

                //Send order email to buyer for cash on delivery
                try {
                    $subject = __('You have successfully created order');
                    Mail::to($order_details->email)->send(new OrderMail($subject,$order_details));
                    Mail::to($seller->email)->send(new OrderMail($subject,$order_details));
                } catch (\Exception $e) {
                    \Toastr::error($e->getMessage());
                }
                return redirect()->route('frontend.order.payment.success',$new_order_id);  

        } else{
            if ($request->selected_payment_gateway === 'paypal') {
           
                $redirect_url = XgPaymentGateway::paypal()->charge_customer([
                    'amount' => $total, // amount you want to charge from customer
                    'title' => $title, // payment title
                    'description' => $description, // payment description
                    'ipn_url' => route('frontend.paypal.ipn'), //you will get payment response in this route
                    'order_id' => $last_order_id, // your order number
                    'track' => 'asdfasdfsdf', // a random number to keep track of your payment 
                    'cancel_url' => route(self::CANCEL_ROUTE,$last_order_id), //payment gateway will redirect here if the payment is failed
                    'success_url' => route(self::SUCCESS_ROUTE,$last_order_id), // payment gateway will redirect here after success
                    'email' => $user_email, // user email
                    'name' => $user_name, // user name
                    'payment_type' => 'order', // which kind of payment your are receving from customer
                ]);
                session()->put('order_id',$last_order_id);
                return $redirect_url;
    
            }
            elseif($request->selected_payment_gateway === 'paytm'){
                $redirect_url = XgPaymentGateway::paytm()->charge_customer([
                    'amount' => $total, 
                    'title' => $title,
                    'description' => $description,
                    'ipn_url' => route('frontend.paytm.ipn'),
                    'order_id' => $last_order_id,
                    'track' => 'asdfasdfsdf',
                    'cancel_url' => route(self::CANCEL_ROUTE,$last_order_id),
                    'success_url' => route(self::SUCCESS_ROUTE,$last_order_id),
                    'email' => $user_email, 
                    'name' => $user_name, 
                    'payment_type' => 'order',
                ]);
               
                session()->put('order_id',$last_order_id);
                return $redirect_url;
            }
            elseif($request->selected_payment_gateway === 'mollie'){
                $redirect_url = XgPaymentGateway::mollie()->charge_customer([
                    'amount' => $total, 
                    'title' => $title,
                    'description' => $description,
                    'ipn_url' => route('frontend.mollie.ipn'),
                    'order_id' => $last_order_id,
                    'track' => 'asdfasdfsdf',
                    'cancel_url' => route(self::CANCEL_ROUTE,$last_order_id),
                    'success_url' => route(self::SUCCESS_ROUTE,$last_order_id),
                    'email' => $user_email, 
                    'name' => $user_name, 
                    'payment_type' => 'order',
                ]);
                session()->put('order_id',$last_order_id);
                return $redirect_url;
            }
            elseif($request->selected_payment_gateway === 'stripe'){
                $redirect_url = XgPaymentGateway::stripe()->charge_customer([
                    'amount' => $total, 
                    'title' => $title,
                    'description' => $description,
                    'ipn_url' => route('frontend.stripe.ipn'),
                    'order_id' => $last_order_id,
                    'track' => 'asdfasdfsdf',
                    'cancel_url' => route(self::CANCEL_ROUTE,$last_order_id),
                    'success_url' => route(self::SUCCESS_ROUTE,$last_order_id),
                    'email' => $user_email, 
                    'name' => $user_name, 
                    'payment_type' => 'order',
                ]);
                session()->put('order_id',$last_order_id);
                return $redirect_url;
            }
            elseif($request->selected_payment_gateway === 'razorpay'){
                $redirect_url = XgPaymentGateway::razorpay()->charge_customer([
                    'amount' => $total, 
                    'title' => $title,
                    'description' => $description,
                    'ipn_url' => route('frontend.razorpay.ipn'),
                    'order_id' => $last_order_id,
                    'track' => 'asdfasdfsdf',
                    'cancel_url' => route(self::CANCEL_ROUTE,$last_order_id),
                    'success_url' => route(self::SUCCESS_ROUTE,$last_order_id),
                    'email' => $user_email, 
                    'name' => $user_name, 
                    'payment_type' => 'order',
                ]);
                session()->put('order_id',$last_order_id);
                return $redirect_url;
            }
            elseif($request->selected_payment_gateway === 'flutterwave'){
                $redirect_url = XgPaymentGateway::flutterwave()->charge_customer([
                    'amount' => $total, 
                    'title' => $title,
                    'description' => $description,
                    'ipn_url' => route('frontend.flutterwave.ipn'),
                    'order_id' => $last_order_id,
                    'track' => 'asdfasdfsdf',
                    'cancel_url' => route(self::CANCEL_ROUTE,$last_order_id),
                    'success_url' => route(self::SUCCESS_ROUTE,$last_order_id),
                    'email' => $user_email, 
                    'name' => $user_name, 
                    'payment_type' => 'order',
                ]);
                session()->put('order_id',$last_order_id);
                return $redirect_url;
            }
            elseif($request->selected_payment_gateway === 'paystack'){
                $redirect_url = XgPaymentGateway::paystack()->charge_customer([
                    'amount' => $total, 
                    'title' => $title,
                    'description' => $description,
                    'ipn_url' => route('frontend.paystack.ipn'),
                    'order_id' => $last_order_id,
                    'track' => 'asdfasdfsdf',
                    'cancel_url' => route(self::CANCEL_ROUTE,$last_order_id),
                    'success_url' => route(self::SUCCESS_ROUTE,$last_order_id),
                    'email' =>  $user_email, 
                    'name' => $user_name,
                    'payment_type' => 'order',
                ]);
                session()->put('order_id',$last_order_id);
                return $redirect_url;
            }
            elseif($request->selected_payment_gateway === 'payfast'){
                $redirect_url = XgPaymentGateway::payfast()->charge_customer([
                    'amount' => $total, 
                    'title' => $title,
                    'description' => $description,
                    'ipn_url' => route('frontend.payfast.ipn'),
                    'order_id' => $last_order_id,
                    'track' => 'asdfasdfsdf',
                    'cancel_url' => route(self::CANCEL_ROUTE,$last_order_id),
                    'success_url' => route(self::SUCCESS_ROUTE,$last_order_id),
                    'email' => $user_email, 
                    'name' =>  $user_name,
                    'payment_type' => 'order',
                ]);
                session()->put('order_id',$last_order_id);
                return $redirect_url;
            }
            elseif($request->selected_payment_gateway === 'cashfree'){
                $redirect_url = XgPaymentGateway::cashfree()->charge_customer([
                    'amount' => $total, 
                    'title' => $title,
                    'description' => $description,
                    'ipn_url' => route('frontend.cashfree.ipn'),
                    'order_id' => $last_order_id,
                    'track' => 'asdfasdfsdf',
                    'cancel_url' => route(self::CANCEL_ROUTE,$last_order_id),
                    'success_url' => route(self::SUCCESS_ROUTE,$last_order_id),
                    'email' => $user_email, 
                    'name' =>  $user_name,
                    'payment_type' => 'order',
                ]);
                session()->put('order_id',$last_order_id);
                return $redirect_url;
            }
            elseif($request->selected_payment_gateway === 'instamojo'){
                $redirect_url = XgPaymentGateway::instamojo()->charge_customer([
                    'amount' => $total, 
                    'title' => $title,
                    'description' => $description,
                    'ipn_url' => route('frontend.instamojo.ipn'),
                    'order_id' => $last_order_id,
                    'track' => 'asdfasdfsdf',
                    'cancel_url' => route(self::CANCEL_ROUTE,$last_order_id),
                    'success_url' => route(self::SUCCESS_ROUTE,$last_order_id),
                    'email' => $user_email, 
                    'name' => $user_name,
                    'payment_type' => 'order',
                ]);
                session()->put('order_id',$last_order_id);
                return $redirect_url;
            }
            elseif($request->selected_payment_gateway === 'marcadopago'){
                $redirect_url = XgPaymentGateway::marcadopago()->charge_customer([
                    'amount' => $total, 
                    'title' => $title,
                    'description' => $description,
                    'ipn_url' => route('frontend.marcadopago.ipn'),
                    'order_id' => $last_order_id,
                    'track' => 'asdfasdfsdf',
                    'cancel_url' => route(self::CANCEL_ROUTE,$last_order_id), 
                    'success_url' => route(self::SUCCESS_ROUTE,$last_order_id),
                    'email' => $user_email, 
                    'name' => $user_name, 
                    'payment_type' => 'order',
                ]);
                session()->put('order_id',$last_order_id);
                return $redirect_url;
            }
            elseif($request->selected_payment_gateway === 'midtrans'){
                $redirect_url = XgPaymentGateway::midtrans()->charge_customer([
                    'amount' => $total,
                    'title' => $title,
                    'description' => $description,
                    'ipn_url' => route('frontend.midtrans.ipn'),
                    'order_id' => $last_order_id,
                    'track' => 'asdfasdfsdf',
                    'cancel_url' => route(self::CANCEL_ROUTE,$last_order_id),
                    'success_url' => route(self::SUCCESS_ROUTE,$last_order_id),
                    'email' => $user_email,
                    'name' => $user_name,
                    'payment_type' => 'order',
                ]);
                session()->put('order_id',$last_order_id);
                return $redirect_url;
            }
        }
        
        return redirect()->route('homepage');  
    }

    //service review add
    public function serviceReviewAdd(Request $request)
    {
        $request->validate([
            'rating' => 'required',
            'name' => 'required|max:191',
            'email' => 'required|max:191',
            'message' => 'required',
        ]);

        Review::create([
            'service_id' => $request->service_id,
            'seller_id' => $request->seller_id,
            'buyer_id' => Auth::guard()->check() ? Auth::guard('web')->user()->id : NULL,
            'rating' => $request->rating,
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 'success'
        ]);
    }

    //seller all services
    public function sellerAllServices($seller_id = null)
    {
        $all_services = Service::with('reviews')
        ->where(['seller_id' => $seller_id, 'status' => 1, 'is_service_on' => 1])
        ->paginate(9);

        $single_service = Service::select('id','seller_id')
        ->where(['seller_id' => $seller_id, 'status' => 1, 'is_service_on' => 1])
        ->first();

        $categories = Category::select('id', 'name')->where('status', 1)->get();
        $sub_categories = Subcategory::select('id', 'name')->where('status', 1)->get();
        if($all_services->count() >= 1){
            return view('frontend.pages.services.seller-all-services', compact('all_services','single_service', 'categories', 'sub_categories'));
        }
        abort(404);
        
    }

    //search by category
    public function searchByCategory(Request $request)
    {
        $services = Service::where('category_id', $request->category_id)
        ->where('status', 1)
        ->where('is_service_on', 1)
        ->where('seller_id',$request->seller_id)
        ->get();

        $single_service = Service::select('id','seller_id')
        ->where(['seller_id' => $request->seller_id, 'status' => 1, 'is_service_on' => 1])
        ->first();

        return response()->json([
            'status' => 'success',
            'services' => $services,
            'result' => view('frontend.pages.services.partials.search-result', compact('services','single_service'))->render(),
        ]);
    }

    //search by sub category
    public function searchBySubcategory(Request $request)
    {
        $services = Service::where('subcategory_id', $request->subcategory_id)
        ->where('status', 1)
        ->where('is_service_on', 1)
        ->where('seller_id',$request->seller_id)
        ->get();

        $single_service = Service::select('id','seller_id')
        ->where(['seller_id' => $request->seller_id, 'status' => 1, 'is_service_on' => 1])
        ->first();

        return response()->json([
            'status' => 'success',
            'services' => $services,
            'result' => view('frontend.pages.services.partials.search-result', compact('services','single_service'))->render(),
        ]);
    }

    //search by rating
    public function searchByRating(Request $request)
    {
        $this->validate($request, ['rating' => 'numeric|min:1|max:5']);

        $rating = $request->rating;
        $services = Service::with('reviews')
        ->where('status', 1)
        ->where('is_service_on', 1)
        ->where('seller_id',$request->seller_id)
        ->whereHas('reviews', function ($q) use ($rating) {                
                $q->havingRaw('AVG(reviews.rating) >= ?', [$rating])
                ->havingRaw('AVG(reviews.rating) <= ?', [$rating + 1]);
                })->get();
        

        $single_service = Service::select('id','seller_id')
        ->where(['seller_id' => $request->seller_id, 'status' => 1, 'is_service_on' => 1])
        ->first();                

        return response()->json([
            'status' => 'success',
            'services' => $services,
            'result' => view('frontend.pages.services.partials.search-result', compact('services','single_service'))->render(),
        ]);
    }

    //search by sub sorting
    public function searchBySorting(Request $request)
    {

        if ($request['sorting'] == 'latest_service') {
            $services = Service::orderBy('id', 'Desc')
            ->where('status', 1)
            ->where('is_service_on', 1)
            ->where('seller_id',$request->seller_id)
            ->get();
        }
        if ($request['sorting'] == 'price_lowest') {
            $services = Service::orderBy('price', 'Asc')
            ->where('status', 1)
            ->where('is_service_on', 1)
            ->where('seller_id',$request->seller_id)
            ->get();
        }
        if ($request['sorting'] == 'price_highest') {
            $services = Service::orderBy('price', 'Desc')
            ->where('status', 1)
            ->where('is_service_on', 1)
            ->where('seller_id',$request->seller_id)
            ->get();
        }
        
        $single_service = Service::select('id','seller_id')
        ->where(['seller_id' => $request->seller_id, 'status' => 1, 'is_service_on' => 1])
        ->first();
        return response()->json([
            'status' => 'success',
            'services' => $services,
            'result' => view('frontend.pages.services.partials.search-result', compact('services','single_service'))->render(),
        ]);
    }

    //search by category from all services
    public function allSearchByCategory(Request $request)
    {
        $services = Service::where('category_id', $request->category_id)->where('status', 1)->where('is_service_on', 1)->get();

        return response()->json([
            'status' => 'success',
            'services' => $services,
            'result' => view('frontend.pages.services.partials.search-result', compact('services'))->render(),
        ]);
    }

     //search by subcategory from all services
     public function allSearchBySubcategory(Request $request)
     {
         $services = Service::where('subcategory_id', $request->subcategory_id)->where('status', 1)->where('is_service_on', 1)->get();
 
         return response()->json([
             'status' => 'success',
             'services' => $services,
             'result' => view('frontend.pages.services.partials.search-result', compact('services'))->render(),
         ]);
     }

     //search by rating from all services
    public function allSearchByRating(Request $request)
    {
        $this->validate($request, ['rating' => 'numeric|min:1|max:5']);

        $rating = $request->rating;
        $services = Service::with('reviews')
        ->where('status', 1)
        ->where('is_service_on', 1)
        ->whereHas('reviews', function ($q) use ($rating) {                
                $q->havingRaw('AVG(reviews.rating) >= ?', [$rating])
                    ->havingRaw('AVG(reviews.rating) <= ?', [$rating + 1]);
                })->get();

        return response()->json([
            'status' => 'success',
            'services' => $services,
            'result' => view('frontend.pages.services.partials.search-result', compact('services'))->render(),
        ]);
    }

    //search by sorting from all services
    public function allSearchBySorting(Request $request)
    {

        if ($request['sorting'] == 'latest_service') {
            $services = Service::orderBy('id', 'Desc')->where('status', 1)->where('is_service_on', 1)->get();
        }
        if ($request['sorting'] == 'price_lowest') {
            $services = Service::orderBy('price', 'Asc')->where('status', 1)->where('is_service_on', 1)->get();
        }
        if ($request['sorting'] == 'price_highest') {
            $services = Service::orderBy('price', 'Desc')->where('status', 1)->where('is_service_on', 1)->get();
        }

        return response()->json([
            'status' => 'success',
            'services' => $services,
            'result' => view('frontend.pages.services.partials.search-result', compact('services'))->render(),
        ]);
    }

     //category wise services
     public function categoryServices($slug = null)
     {
         $category = Category::select('id','name')->where('slug',$slug)->first();
         $sub_category = Subcategory::select('id','name')->where('slug',$slug)->first();

         if(!is_null($category)){
            $all_services = Service::with('reviews')
            ->where(['category_id' => $category->id, 'status' => 1, 'is_service_on' => 1])
            ->paginate(9);
         }
        
         if(!is_null($sub_category)){
            $all_services = Service::with('reviews')
            ->where(['subcategory_id' => $sub_category->id, 'status' => 1, 'is_service_on' => 1])
            ->paginate(9);
         }
         
         return view('frontend.pages.services.category-services', compact(
             'all_services',
             'category',
             'sub_category',
            ));
     }

      //all featured service
      public function allfeaturedService()
      {
         $all_featurd_service = Service::select('id','title','image','description','price','slug','seller_id')
         ->with('reviews')
        ->where(['status'=>1,'is_service_on'=>1,'featured'=>1])
        ->paginate(9);
          return view('frontend.pages.services.featured-services',compact('all_featurd_service'));
      }

     //all popular service
      public function allPopularService()
      {
         $all_popular_service = Service::select('id','title','image','description','price','slug','seller_id','view','featured')
         ->with('reviews')
         ->where(['status'=>1,'is_service_on'=>1])
         ->orderBy('view','DESC')
         ->paginate(9);
        return view('frontend.pages.services.popular-service',compact('all_popular_service'));
      }

     //all categories
     public function allCategory()
     {
        $all_category = Category::select('id','name','slug','image')->with('services')
        ->whereHas('services')
        ->get();
        $all_subcategory = Subcategory::select('id','name','slug','image')->with('services')
        ->whereHas('services')
        ->get();
         return view('frontend.pages.category.all-category',compact('all_category','all_subcategory'));
     }

}
