<?php

namespace App\Http\Controllers;

use App\User;
use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BasicMail;
use App\Mail\SingleMailToUser;
use App\Accountdeactive;
use App\Service;
use Str;

class FrontendUserManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:user-list|user-create|user-edit|user-delete',['only' => 'all_user']);
        $this->middleware('permission:user-delete',['only' => ['bulk_action','new_user_delete']]);
    }

    public function all_user()
    {
        $all_user = User::all();
        return view('backend.frontend-user.all-user')->with(['all_user' => $all_user]);
    }

    public function userStatus($id=null)
    {   
        $user_status = User::select('user_status')->find($id);
        User::where('id', $id)->update([
            'user_status' => $user_status->user_status== 0 ? 1 : 0
        ]);

        $user_status_2 = User::select('user_status')->find($id);
        if($user_status_2->user_status == 0){
            Service::where('seller_id',$id)
            ->update(['status'=>0]);
        }
        if($user_status_2->user_status == 1){
            Service::where('seller_id',$id)
            ->update(['status'=>1]);
        }
        return redirect()->back()->with(FlashMsg::item_new('Status change success--'));
    }

    public function userDelete($id=null)
    {
        User::find($id)->delete();
        return redirect()->back()->with(FlashMsg::item_new('User delete success--'));
    }

    public function bulk_action(Request $request)
    {
        $all = User::find($request->ids);
        foreach ($all as $item) {
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function email_verify_code($email=null){
       $email_verify_token = Str::random(16);
        User::where('email',$email)->update(['email_verify_token'=>$email_verify_token]);
       
       try {
            $message_body = __('Here is your verification code').' <span class="verify-code">'.$email_verify_token.'</span>';
            Mail::to($email)->send(new BasicMail([
                'subject' => __('Verify your email address'),
                'message' => $message_body
            ]));
        }catch (\Exception $e){

        }
       return redirect()->back()->with(FlashMsg::item_new(__('Verification Code Send Success')));
    }

    public function send_mail_to_single_user(Request $request){

        $this->validate($request,[
           'email' => 'required|email',
           'subject' => 'required',
           'message' => 'required',
        ]);

        $data = [
          'email' => $request->email,
          'subject' => $request->subject,
          'message' => $request->message,
        ];

        try {
            Mail::to($request->email)->send(new SingleMailToUser($data));
        }catch (\Exception $ex){
            return redirect()->back()->with(FlashMsg::item_delete($ex->getMessage()));
        }

        return redirect()->back()->with([
            'msg' => __('Mail Send Success...'),
            'type' => 'success'
        ]);
    }
    
     public function deactive_user()
    {
        $all_user = Accountdeactive::all();
        return view('backend.frontend-user.all-deactive-user')->with(['all_user' => $all_user]);
    }
}
