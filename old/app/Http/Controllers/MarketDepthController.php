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
use App\Models\TradingAccount;
use App\Models\Trade;
use App\Models\MarketDepth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon;
use File;
use AngelBroking\SmartApi;
use KiteConnect\KiteConnect;

class MarketDepthController extends Controller{
    
    
    public function store(Request $request){
        $md = new MarketDepth();
        $md->exchange = $request->exchange;
        $md->symbol = strtoupper($request->tradingsymbol);
        $md->save();
        return back();
    }

    public function delete($id){
        MarketDepth::where('id', $id)->delete();
        return Redirect::back()
        ->with('msg', 'Account delete successfull');
    }
    
}


