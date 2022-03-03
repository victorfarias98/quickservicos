<?php

namespace App\Http\Controllers\Auth;

use App\Admin;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\BasicMail;
use App\ServiceCity;
use App\ServiceArea;
use App\Country;
use Toastr;
use Str;
USE Auth;
use Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
//    protected $redirectTo = '/';
    public function redirectTo(){
        return route('homepage');
    }
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:admin');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:191'],
            'captcha_token' => ['nullable'],
            'username' => ['required', 'string', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ],[
            'captcha_token.required' => __('google captcha is required'),
            'name.required' => __('name is required'),
            'name.max' => __('name is must be between 191 character'),
            'username.required' => __('username is required'),
            'username.max' => __('username is must be between 191 character'),
            'username.unique' => __('username is already taken'),
            'email.unique' => __('email is already taken'),
            'email.required' => __('email is required'),
            'password.required' => __('password is required'),
            'password.confirmed' => __('both password does not matched'),
        ]);
    }
    protected function adminValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:admins'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['user_name'],
            'phone' => $data['phone'],
            'service_city' => $data['service_city'],
            'service_area' => $data['service_area'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    public function userRegister(Request $request)
    {   
        if($request->isMethod('post')){
            $request->validate([
                'name' => 'required|regex:/^[\pL\s\-]+$/u|max:191',
                'email' => 'required|email|unique:users|max:191',
                'username' => 'required|unique:users|max:191',
                'phone' => 'required|unique:users|max:191',
                'password' => 'required|max:191',
                'service_city' => 'required',
                'service_area' => 'required',
                'country' => 'required',
            ]);

            $email_verify_tokn = Str::random(8);
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'service_city' => $request->service_city,
                'service_area' => $request->service_area,
                'country_id' => $request->country,
                'user_type' => $request->get_user_type,
                'terms_conditions' =>1,
                'email_verify_token'=> $email_verify_tokn,
            ]);
            
            try {
                $message_body = __('Here is your verification code').' <span class="verify-code">'.$email_verify_tokn.'</span>';
                Mail::to($request->email)->send(new BasicMail([
                    'subject' => __('Verify your email address'),
                    'message' => $message_body
                ]));
            }catch (\Exception $e){
                
            }
            Session::put('email',$request->email);
            Session::put('email_verify_tokn',$email_verify_tokn);
            return redirect()->route('email.verify');
        }

        $cities = ServiceCity::all();
        $countries = Country::all();
        return view('frontend.user.register',compact('cities','countries'));
    }

    public function getCity(Request $request)
    {
        $cities = ServiceCity::where('country_id', $request->country_id)->where('status', 1)->get();
        return response()->json([
            'status' => 'success',
            'cities' => $cities,
        ]);
    }

    public function getAarea(Request $request)
    {
        $areas = ServiceArea::where('service_city_id', $request->city_id)->where('status', 1)->get();
        return response()->json([
            'status' => 'success',
            'areas' => $areas,
        ]);
    }

    public function emailVerify(Request $request)
    {   
        if($request->isMethod('post')){

            $this->validate($request,[
                'email_verify_token' => 'required|max:191'
            ],[
                'email_verify_token.required' => __('verify code is required')
            ]);

            $email_verify_token = User::select('email_verify_token')->where('email_verified',NULL)->get();
            if($email_verify_token->count() >=1){
                foreach($email_verify_token as $token){
                    if($token->email_verify_token==$request->email_verify_token){
                        User::where('email_verify_token',$request->email_verify_token)->update(['email_verified'=>'1']);
                        Session::flash('msg', __('Your email is verified. You can login now.'));
                        return redirect()->route('user.login');
                    }
                }
            }
            return redirect()->back()->with(Session::flash('msg', __('Your verification code is wrong.') ));
        }
        return view('frontend.user.email-verify')->with(Session::flash('msg_success', __('Please check email for verification code.') ));
    }

    public function resendCode(){
        $email = Session::get('email');
        $email_verify_tokn = Session::get('email_verify_tokn');
        try {
            $message_body = __('Here is your verification code').' <span class="verify-code">'.$email_verify_tokn.'</span>';
            Mail::to($email)->send(new BasicMail([
                'subject' => __('Verify your email address'),
                'message' => $message_body
            ]));
        }catch (\Exception $e){
            
        }
        return redirect()->back();
    }
}