<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use view;
use DB;
use Session;
use App\Http\Requests;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon;
use File;
use AngelBroking\SmartApi;

class CustomerController extends Controller
{
    
    public function store(Request $request){
        
        $smart_api  = new \AngelBroking\SmartApi(
            // OPTIONAL
            //  "YOUR_ACCESS_TOKEN",
            // "YOUR_REFRESH_TOKEN"
        );
        $jsonData = $smart_api ->GenerateSession($request->customer_code, $request->password);
        $preData = json_decode($jsonData, true);
        
        $cd = new Customer;
        $cd->name  = $request->name;
        $cd->email  = $request->email;
        $cd->mobile  = $request->mobile;
        $cd->address  = $request->address;
        $cd->customer_code  = $request->customer_code;
        $cd->password  = $request->password;
        $cd->jwt_token  = $preData['response_data']['data']['jwtToken'];
        $cd->refresh_token  = $preData['response_data']['data']['refreshToken'];
        $cd->feedToken  = $preData['response_data']['data']['feedToken'];
        $cd->is_active="active";
        $cd->save();
        
        return Redirect::back()->with('msg', 'Customer added successfully');
        
    }
    
    public function update(Request $request){
        
        $smart_api  = new \AngelBroking\SmartApi(
            // OPTIONAL
            //  "YOUR_ACCESS_TOKEN",
            // "YOUR_REFRESH_TOKEN"
        );
        $jsonData = $smart_api ->GenerateSession($request->customer_code, $request->password);
        $preData = json_decode($jsonData, true);
        
        
        $cd = Customer::where('id', $request->id)->first();
        $cd->name  = $request->name;
        $cd->email  = $request->email;
        $cd->mobile  = $request->mobile;
        $cd->address  = $request->address;
        $cd->customer_code  = $request->customer_code;
        $cd->password  = $request->password;
        $cd->jwt_token  = $preData['response_data']['data']['jwtToken'];
        $cd->refresh_token  = $preData['response_data']['data']['refreshToken'];
        $cd->feedToken  = $preData['response_data']['data']['feedToken'];
        $cd->is_active="active";
        $cd->save();
        
        return Redirect::back()->with('msg', 'Customer updated successfully');
        
    }

    public function delete($id){
        
        Customer::where('id', $id)->delete();
        return Redirect::back()
        ->with('msg', 'CUstomer delete successfull');
    }

    
}