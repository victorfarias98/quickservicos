<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderInclude;
use App\OrderAdditional;
use Illuminate\Http\Request;
use App\Helpers\FlashMsg;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\DataTableHelpers\General;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:order-list|order-status|order-view',['only' => ['index']]);
        $this->middleware('permission:order-status',['only' => ['orderStatus']]);
        $this->middleware('permission:order-view',['only' => ['orderDetails']]);
    }

    public function index(Request $request){

        if ($request->ajax()){

            $data = Order::select('*')->orderBy('id','desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('checkbox',function ($row){
                    return General::bulkCheckbox($row->id);
                })

                ->addColumn('id',function ($row){
                    return $row->id;
                })

                ->addColumn('name',function ($row){
                    return $row->name;
                })

                ->addColumn('email',function ($row){
                    return $row->email;
                })

                ->addColumn('phone',function ($row){
                    return $row->phone;
                })

                ->addColumn('address',function ($row){
                    return $row->address;
                })

                ->addColumn('amount',function ($row){
                    return float_amount_with_currency_symbol($row->total);
                })

                ->addColumn('create_date',function ($row){
                    return date_format($row->created_at,'d-M-Y');
                })

                ->addColumn('status',function ($row){
                    return General::orderStatus($row->status);
                }) 

                ->addColumn('action', function($row){
                    $action = '';
                    $admin = auth()->guard('admin')->user();
                    if ($admin->can('order-view')){
                        $action .= General::viewIcon(route('admin.orders.details',$row->id));
                    }
                    return $action;
                })
                ->rawColumns(['checkbox','status','action'])
                ->make(true);
        }
        return view('backend.pages.orders.index');
    }

    public function orderDetails($id){
        $order_details = Order::where('id',$id)->first();
        $order_includes = OrderInclude::where('order_id',$id)->get();
        $order_additionals = OrderAdditional::where('order_id',$id)->get();
        return view('backend.pages.orders.order-details',compact('order_details','order_includes','order_additionals'));
    }

    public function orderStatus(Request $request)
    {
        Order::where('id',$request->order_id)->update(['status'=>$request->status]);
        return redirect()->back()->with(FlashMsg::item_new('Status Update Success'));
    }
    
    public function order_success_settings()
     {
        return view('backend.pages.orders.order-success-settings');
     }

    public function order_success_settings_update(Request $request)
    {
         $this->validate($request, [
             'success_title' => 'nullable|string',
             'success_subtitle' => 'nullable|string',
             'success_details_title' => 'nullable|string',
             'button_title' => 'nullable|string',
             'button_url' => 'nullable|string',
         ]);
     
         $all_fields = [
             'success_title',
             'success_subtitle',
             'success_details_title',
             'button_title',
             'button_url',
         ];
         foreach ($all_fields as $field) {
             update_static_option($field, $request->$field);
         }
         return redirect()->back()->with(FlashMsg::settings_update());
    }
}
