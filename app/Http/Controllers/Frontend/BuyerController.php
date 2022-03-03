<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\OrderAdditional;
use App\OrderInclude;
use App\ServiceCity;
use App\ServiceArea;
use App\Country;
use App\Order;
use App\User;
use Auth;
use App\SupportTicket;
use App\SupportDepartment;
use App\SupportTicketMessage;
use App\Events\SupportMessage;
use App\Helpers\FlashMsg;

class BuyerController extends Controller
{
     public function __construct(){
        $this->middleware('inactiveuser');
    }
    
    public function buyerDashboard()
    {
        $buyer_id = Auth::guard('web')->user()->id;

        $pending_order = Order::where(['buyer_id'=>$buyer_id, 'status'=>0])->count();
        $active_order = Order::where(['buyer_id'=>$buyer_id, 'status'=>1])->count();
        $complete_order = Order::where(['buyer_id'=>$buyer_id, 'status'=>2])->count();
        $total_order = Order::where('buyer_id',$buyer_id)->count();
        $last_10_order = Order::where('buyer_id',$buyer_id)->take(10)->latest()->get();
        $last_10_tickets = SupportTicket::where('buyer_id',$buyer_id)->take(10)->latest()->get();

        return view('frontend.user.buyer.dashboard.dashboard',compact('pending_order','active_order','complete_order','total_order','last_10_order','last_10_tickets'));
    }

    public function buyerOrders()
    {
        $orders = Order::where('buyer_id', Auth::guard('web')->user()->id)->paginate(10);
        return view('frontend.user.buyer.order.orders', compact('orders'));
    }

    public function orderDetails($id=null)
    {
        $order_details = Order::with('seller')
        ->where('id',$id)
        ->where('buyer_id',Auth::guard('web')->user()->id)->first();

        if(!is_null($order_details)){
            $order_includes = OrderInclude::where('order_id',$id)->get();
            $order_additionals = OrderAdditional::where('order_id',$id)->get();
            return view('frontend.user.buyer.order.order-details', compact('order_details','order_includes','order_additionals'));
        }
        abort(404);
    }

    public function orderStatus(Request $request,$id=null)
    {
        Order::where('id',$request->order_id)->update(['status'=>$request->status]);
        toastr_success(__('Status Change Success---'));
        return redirect()->back();
    }

    public function buyerProfile()
    {
        return view('frontend.user.buyer.profile.buyer-profile');
    }

    public function buyerProfileEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = Auth::guard('web')->user()->id;
            $request->validate([
                'name' => 'required|regex:/^[\pL\s\-]+$/u|max:191',
                'email' => 'required|max:191|email|unique:users,email,'.$user,
                'phone' => 'required|max:191',
                'service_area' => 'required|max:191',
                'post_code' => 'required|max:191',
                'address' => 'required|max:191',
                'about' => 'required|max:5000',
            ]);
            $old_image = User::select('image')->where('id',Auth::guard('web')->user()->id)->first();
            User::where('id', Auth::guard('web')->user()->id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'image' => $request->image ?? $old_image->image,
                    'profile_background' => $request->profile_background ?? $old_image->profile_background,
                    'service_city' => $request->service_city,
                    'service_area' => $request->service_area,
                    'country_id' => $request->country,
                    'post_code' => $request->post_code,
                    'address' => $request->address,
                    'about' => $request->about,
                ]);
            toastr_success(__('Profile Update Success---'));
            return redirect()->back();
        }
        
        $cities = ServiceCity::where('status',1)->get();
        $areas = ServiceArea::where('status',1)->get();
        $countries = Country::where('status',1)->get();
        return view('frontend.user.buyer.profile.buyer-profile-edit',compact('cities','areas','countries'));
    }

    public function buyerAccountSetting(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'current_password' => 'required|min:6',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|min:6',
            ]);

            $buyer = User::where('id', Auth::user()->id)->first();

            if (Hash::check($request->current_password, $buyer->password)) {
                if ($request->new_password == $request->confirm_password) {
                    User::where('id', $buyer->id)->update(['password' => Hash::make($request->new_password)]);
                    toastr_success(__('Password Update Success---'));
                    return redirect()->back();
                }
                toastr_error(__('Password and Confirm Password not match---'));
                return redirect()->back();
            }
            toastr_error(__('Current Password is Wrong---'));
            return redirect()->back();
        }
        return view('frontend.user.buyer.profile.buyer-account-settings');
    }

    public function buyerLogout()
    {
        Auth::logout();
        return redirect('/');
    }

    //support tickets
    public function allTickets()
    {
        $tickets = SupportTicket::where('buyer_id',Auth::guard('web')->user()->id)->paginate(5);
        return view('frontend.user.buyer.support-ticket.all-tickets', compact('tickets'));
    }

    public function addNewTicket(Request $request, $id=null)
    {
        if($request->isMethod('post')){
            $this->validate($request,[
                'title' => 'required|string|max:191',
                'subject' => 'required|string|max:191',
                'priority' => 'required|string|max:191',
                'description' => 'required|string',
             ],[
                 'title.required' => __('title required'),
                 'subject.required' =>  __('subject required'),
                 'priority.required' =>  __('priority required'),
                 'description.required' => __('description required'),
             ]);

             SupportTicket::create([
                'title' => $request->title,
                'description' => $request->description,
                'subject' => $request->subject,
                'status' => 'open',
                'priority' => $request->priority,
                'buyer_id' => Auth::guard('web')->user()->id,
                'seller_id' => $request->seller_id,
                'service_id' => $request->service_id,
                'order_id' => $request->order_id,
            ]);
            toastr_success(__('Ticket successfully created.'));
            return redirect()->back();
        }

        $order = Order::select('id','service_id','seller_id')->where('id',$id)->first();
        return view('frontend.user.buyer.support-ticket.add-new-ticket', compact('order'));
    }

    public function ticketDelete($id=null)
    {
        SupportTicket::find($id)->delete();
        toastr_error(__('Ticket Delete Success---'));
        return redirect()->back();
    }

    //view ticket 
    public function view_ticket(Request $request,$id)
    {
        $ticket_details = SupportTicket::findOrFail($id);
        $all_messages = SupportTicketMessage::where(['support_ticket_id'=>$id])->get();
        $q = $request->q ?? '';
        return view('frontend.user.buyer.support-ticket.view-ticket', compact('ticket_details','all_messages','q'));
    }

    //priority status 
    public function priorityChange(Request $request)
    {
        SupportTicket::where('id',$request->ticket_id)->update(['priority'=>$request->priority]);
        toastr_success(__('Priority Change Success---'));
        return redirect()->back();
    }

    //change status 
    public function statusChange($id=null)
    {
        $status = SupportTicket::find($id);
        if($status->status=='open'){
            $status = 'close';
        }else{
            $status = 'open';
        }
        SupportTicket::where('id',$id)->update(['status'=>$status]);
        toastr_success(__('Status Change Success---'));
        return redirect()->back();
    }

    //send message 
    public function support_ticket_message(Request $request)
    {
        $this->validate($request,[
            'ticket_id' => 'required',
            'user_type' => 'required|string|max:191',
            'message' => 'required',
            'send_notify_mail' => 'nullable|string',
            'file' => 'nullable|mimes:zip',
        ]);

        $ticket_info = SupportTicketMessage::create([
            'support_ticket_id' => $request->ticket_id,
            'type' => $request->user_type,
            'message' => $request->message,
            'notify' => $request->send_notify_mail ? 'on' : 'off',
        ]);

        if ($request->hasFile('file')){
            $uploaded_file = $request->file;
            $file_extension = $uploaded_file->getClientOriginalExtension();
            $file_name =  pathinfo($uploaded_file->getClientOriginalName(),PATHINFO_FILENAME).time().'.'.$file_extension;
            $uploaded_file->move('assets/uploads/ticket',$file_name);
            $ticket_info->attachment = $file_name;
            $ticket_info->save();
        }

        //send mail to user
        event(new SupportMessage($ticket_info));
        return redirect()->back()->with(FlashMsg::item_new(__('Message Send')));
    }
}
