<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use view;
use DB;
use Session;
use App\Http\Requests;
use App\Models\Groups;
use App\Models\GroupDetail;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon;
use File;
use AngelBroking\SmartApi;

class GeneralSettingController extends Controller
{
    
    public function store(Request $request){
        
        $gs = new GeneralSetting;
        $gs->trade_type  = $request->trade_type;
        $gs->variety  = $request->variety;
        $gs->product_type = $request->product_type;
        $gs->quantity = $request->quantity;
        if($gs->save()){
            return Redirect::back()->with('msg', 'General Setting update successfully');
        }else{
            return Redirect::back()->with('msg', 'Something went wrong');
        }

    }
    
    public function update(Request $request){
        
        $gs = GeneralSetting::where('id', $request->id)->first();
        $gs->trade_type  = $request->trade_type;
        $gs->variety  = $request->variety;
        $gs->product_type = $request->product_type;
        $gs->quantity = $request->quantity;
        if($gs->save()){
            return Redirect::back()->with('msg', 'Group updated successfully');    
        }else{
            return Redirect::back()->with('msg', 'Something went wrong');
        }
        
        
        
    }

  

    
}