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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon;
use File;
use AngelBroking\SmartApi;

class Authenticator extends Controller
{
    
    public function store(Request $request){
        $cd = TradingAccount::where('login_id', $request->loginId)->first();
        $cd->api_key  = $request->key;
        $cd->save();
        echo true;
    }
    

    
}