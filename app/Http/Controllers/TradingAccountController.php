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
use KiteConnect\KiteConnect;
use AngelBroking\SmartApi;

class TradingAccountController extends Controller{
    
    public function getOrders(Request $request){
        $GetOrderBook="";$GetOrderBook1="";$preOrder="";
        $kiteOrder="";$kiteOrderArray=array();
        $array2=[];$GetOrderData = array();
        // $td = TradingAccount::where('login_id', 'D229903')->get();
        $td = TradingAccount::all();
        if($td){
            foreach($td as $alltd){
                if($alltd->stock_brocker=="Angel"){
                    if($alltd->trading_platform=="SMART_API"){
                        $smart_api  = new \AngelBroking\SmartApi(
                            // OPTIONAL
                            //  "YOUR_ACCESS_TOKEN",
                            // "YOUR_REFRESH_TOKEN"
                        );
                        // echo $alltd->login_id."<br>";
                        $jsonData = $smart_api ->GenerateSession($alltd->login_id, $alltd->password);
                        
                        // $GetOrderBook1 = $smart_api ->GetOrderBook();
                        
                        $preHoldings = json_decode($smart_api ->GetOrderBook(), true);
                        
                        if($preHoldings['response_data']['data']!=null){
                            
                            for($i=0;$i<count($preHoldings['response_data']['data']);$i++){
                                $preHoldings['response_data']['data'][$i]['accountId']=$alltd->id;
                                $preHoldings['response_data']['data'][$i]['loginId']=$alltd->login_id;
                                $preHoldings['response_data']['data'][$i]['platform']=$alltd->trading_platform;
                                $preHoldings['response_data']['data'][$i]['broker']=$alltd->stock_brocker;
                            }
                            
                        }
                        
                        // return $preHoldings;
                        if(isset($preHoldings['response_data']['data'])){
                            if($preHoldings['response_data']['data']!=null){
                                $GetOrderData[] = array('data'=>$preHoldings['response_data']['data'], 
                                );
                            }
                        }
                    }
                } else if($alltd->stock_brocker=="Zerodha"){
                    if($alltd->access_token==null || $alltd->access_token==""){

                    }else{
                        try{
                            $kite = new KiteConnect(env('KITE_KEY'));
                            
                            $kite->setAccessToken($alltd->access_token);
                            $kiteOrder = $kite->getOrders();

                            for($i=0;$i<count($kiteOrder);$i++){
                                $array2[] = array(
                                    "variety"=> $kiteOrder[$i]->variety,
                                    "ordertype"=> $kiteOrder[$i]->order_type,
                                    "producttype"=> $kiteOrder[$i]->product,
                                    "duration"=> $kiteOrder[$i]->validity,
                                    "price"=> $kiteOrder[$i]->price,
                                    "triggerprice"=> $kiteOrder[$i]->trigger_price,
                                    "quantity"=> $kiteOrder[$i]->quantity,
                                    "disclosedquantity"=> $kiteOrder[$i]->disclosed_quantity,
                                    "squareoff"=> "",
                                    "stoploss"=> "",
                                    "trailingstoploss"=> "",
                                    "tradingsymbol"=> $kiteOrder[$i]->tradingsymbol,
                                    "transactiontype"=> $kiteOrder[$i]->transaction_type,
                                    "exchange"=> $kiteOrder[$i]->exchange,
                                    "symboltoken"=> $kiteOrder[$i]->tradingsymbol,
                                    "ordertag"=> $kiteOrder[$i]->tag,
                                    "instrumenttype"=> "",
                                    "strikeprice"=> "",
                                    "optiontype"=> "",
                                    "expirydate"=> "",
                                    "lotsize"=> "",
                                    "cancelsize"=> $kiteOrder[$i]->cancelled_quantity,
                                    "averageprice"=> $kiteOrder[$i]->average_price,
                                    "filledshares"=> $kiteOrder[$i]->filled_quantity,
                                    "unfilledshares"=> $kiteOrder[$i]->pending_quantity,
                                    "orderid"=> $kiteOrder[$i]->order_id,
                                    "text"=> $kiteOrder[$i]->status_message,
                                    "status"=> $kiteOrder[$i]->status,
                                    "orderstatus"=> $kiteOrder[$i]->status,
                                    "updatetime"=> $kiteOrder[$i]->exchange_update_timestamp,
                                    "exchtime"=> $kiteOrder[$i]->exchange_timestamp,
                                    "exchorderupdatetime"=> $kiteOrder[$i]->exchange_timestamp,
                                    "fillid"=> $kiteOrder[$i]->exchange_order_id,
                                    "filltime"=> "",
                                    "parentorderid"=> $kiteOrder[$i]->parent_order_id,
                                    "accountId"=>$alltd->id,
                                    "loginId"=>$alltd->login_id,
                                    "platform"=>$alltd->trading_platform,
                                    "broker"=>$alltd->stock_brocker
                                );
                            }
                            $GetOrderData[] = array('data'=>$array2);
                        } catch (Handler $e) {
                            \Log::debug($e->getMessage());
                        } catch(\KiteConnect\Exception\TokenException $e){
                            \Log::debug($e->getMessage());
                        }   
                    }
                }
            }
            return view('orders.index', compact('GetOrderData'));
        }else{
            
        }
        
    }
    
    public function store(Request $request){
        if($request->stock_brocker=="Angel"){
            $smart_api  = new \AngelBroking\SmartApi( );
            $jsonData = $smart_api ->GenerateSession($request->login_id, $request->password);
            $preData = json_decode($jsonData, true);
            if($preData['response_data']['status']==true){
                $cd = new TradingAccount;
                $cd->stock_brocker  = $request->stock_brocker;
                $cd->trading_platform  = $request->trading_platform;
                $cd->login_id  = $request->login_id;
                $cd->password  = $request->password;
                $cd->security_ans  = $request->security_ans;
                $cd->api_key  = $request->api_key;
                $cd->name = $request->name;
                $cd->mobile = $request->mobile;
                $cd->email_id = $request->email_id;
                $cd->tpin = $request->tpin;
                $cd->token = "";
                $cd->is_active="active";
                $cd->save();
                return Redirect::back()->with('msg', 'Trading Account added successfully');
            }else{
                return Redirect::back()->with('msg', 'Invalid Try again');
            }
        }else if($request->stock_brocker=="Zerodha"){
            // https://kite.zerodha.com/connect/login?v=3&api_key=36cu3p0dwq6nyche
            // $zerodha_apikey = "36cu3p0dwq6nyche";
            // $zerodha_secret="m955ao2kqotyo3wf7nj53fa4zrw48fnj";
            // $kite = new KiteConnect($zerodha_apikey);
            // $zerodha_request_token = "mvSA37xUaXiIrwPlWy5KF1YxI6P1uxcu";//isset($_GET['request_token']) ? $_GET['request_token'] : "";
            $kite = new KiteConnect(env('KITE_KEY'));
            $kite->setAccessToken($allta->access_token);

            $cd = new TradingAccount;
            $cd->stock_brocker  = $request->stock_brocker;
            $cd->trading_platform  = $request->trading_platform;
            $cd->login_id  = $request->login_id;
            $cd->password  = $request->password;
            $cd->security_ans  = $request->security_ans;
            $cd->api_key  = "";//$request->api_key;
            $cd->name = $request->name;
            $cd->mobile = $request->mobile;
            $cd->email_id = $request->email_id;
            $cd->tpin = $request->tpin;
            $cd->token = "";
            $cd->is_active="active";
            $cd->save();
            return Redirect::back()->with('msg', 'Trading Account added successfully');
        }
        
    }
    
    public function update(Request $request){
        if($request->stock_brocker=="Angel"){
            $smart_api  = new \AngelBroking\SmartApi(
                // OPTIONAL
                //  "YOUR_ACCESS_TOKEN",
                // "YOUR_REFRESH_TOKEN"
            );
            $jsonData = $smart_api ->GenerateSession($request->login_id, $request->password);
            $preData = json_decode($jsonData, true);
            if($preData['response_data']['status']==true){
                $cd = TradingAccount::where('id', $request->id)->first();
                $cd->stock_brocker  = $request->stock_brocker;
                $cd->trading_platform  = $request->trading_platform;
                $cd->login_id  = $request->login_id;
                $cd->password  = $request->password;
                $cd->security_ans  = $request->security_ans;
                $cd->api_key  = $request->api_key;
                $cd->name = $request->name;
                $cd->mobile = $request->mobile;
                $cd->email_id = $request->email_id;
                $cd->tpin = $request->tpin;
                $cd->token = "";
                $cd->is_active="active";
                $cd->save();
                return Redirect::back()->with('msg', 'Trading Account updated successfully');
            }else{
                return Redirect::back()->with('msg', 'Invalid Try again');
            }
        }else if($request->stock_brocker=="Zerodha"){
            $cd = TradingAccount::where('id', $request->id)->first();
            $cd->stock_brocker  = $request->stock_brocker;
            $cd->trading_platform  = $request->trading_platform;
            $cd->login_id  = $request->login_id;
            $cd->password  = $request->password;
            $cd->security_ans  = $request->security_ans;
            $cd->api_key  = $request->api_key;
            $cd->name = $request->name;
            $cd->mobile = $request->mobile;
            $cd->email_id = $request->email_id;
            $cd->tpin = $request->tpin;
            $cd->token = "";
            $cd->is_active="active";
            $cd->save();
            return Redirect::back()->with('msg', 'Trading Account updated successfully');
        }
    }

    public function delete($id){
        TradingAccount::where('id', $id)->delete();
        return Redirect::back()
        ->with('msg', 'Account delete successfull');
    }


    public function getKiteUserDetail(Request $request){
        
        $kite = new KiteConnect(env('KITE_KEY'));
        try {
            $user = $kite->generateSession($request->token, env('KIET_SECRET'));
            echo "Authentication successful. \n";
            print_r($user);
            $kite->setAccessToken($user->access_token);
        } catch(Exception $e) {
            echo "Authentication failed: ".$e->getMessage();
            throw $e;
        }
    
        return $user;
    }

    public function activation(Request $request){
        $ta = TradingAccount::where('id', $_GET['id'])->first();
        if($ta){
            $ta->is_active = $_GET['status'];
            return $ta->update();
            // return 1;
        }else{
            // return 0;
        }
    }

    
}