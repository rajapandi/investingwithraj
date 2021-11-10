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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Carbon;
use File;
use AngelBroking\SmartApi;
use App\Exceptions\Handler;
use KiteConnect\KiteConnect;
use App\Models\Instruments;
use App\Http\Controllers\SchedularController;

class TradeController extends Controller{

    public function searchTradeSymbol(Request $request){
        $str = $request->key;
        $valuestr = "";
        $seachKey = '1was:symbol';
        if(unserialize(Redis::get($seachKey))!=null){
            $jsonitem = json_decode(unserialize(Redis::get($seachKey)));
        }else{
            // $json = file_get_contents("https://margincalculator.angelbroking.com/OpenAPI_File/files/OpenAPIScripMaster.json");
            $json = Instruments::all();
            Redis::set($seachKey, serialize($json));
            $jsonitem = json_decode(unserialize(Redis::get($seachKey)));
        }
        foreach ($jsonitem as $symbol) {
            if(preg_match("/$str/i", $symbol->name, $matches, PREG_OFFSET_CAPTURE)==1){
                $valuestr = $symbol->name;
                $exchange = $symbol->exchange;
                echo '<li onclick="getAddDataOnSerachBox(\''.$valuestr.'\')"  style="cursor:pointer;">'.$valuestr.' - '.$symbol->exchange.'</li>';
            }
        }
    }

    public function getIdentify(Request $request){
        if($request->Modify){
            return view('trade.showModifyOrders');
        }else if($request->Cancel){
            return $this->getCancelOrder($request);
        }
    }

    public function create(Request $request){
        $transaction_type = $request->transactiontype;
        $variety = $request->variety;
        $exchange = $request->exchange;
        $trading_symbol = $request->tradingsymbol;
        $product_type = $request->productType;
        $order_type = $request->orderType;
        $quantity = $request->quantity;
        $price = $request->price;
        $trigger_price = $request->triggerPrice;
        $disclosed_qty = $request->disclosedQty;
        $target = $request->target;
        $stoploss = $request->stoploss;
        $trailing_stoploss = $request->trailingStoploss;
        $validity = $request->validity;
        $amo = $request->amo;
        $is_active="active";
        $group_active = $request->group_active;
        $diff_qty = $request->diff_qty;
        $symboltoken="";$symbol="";$order_id=0;
        if($stoploss=="" || $stoploss==null){
            $stoploss = 0;
        }
        if($request->accounts=='' || $request->accounts==null){
            return back()->with('accountmsg', 'Select Account');
        }
        if($price=="" || $price==null){
            $price=0;
        }
        if($request->isSchedular=="Yes"){
            return SchedularController::storeSchedular($request);
        }else{
            // if($group_active=="group"){
            //     if($diff_qty=="diff_qty"){
            //         return $this->orderPlaceByGroupAndDiffQty($request);
            //     }else{
            //         return $this->orderPlaceByGroup($request);
            //     }
            // }else{
            //     if($diff_qty=="diff_qty"){
            //         return $this->orderPlaceByDiffQtyOnly($request);
            //     }else{
            //         return $this->orderPlaceWithoutGroupAndDiffQty($request);
            //     }
            // }
        }


    }

    public function orderPlaceWithoutGroupAndDiffQty(Request $request){
        $transaction_type = $request->transactiontype;
        $variety = $request->variety;
        $exchange = $request->exchange;
        $trading_symbol = $request->tradingsymbol;
        $product_type = $request->productType;
        $order_type = $request->orderType;
        $quantity = $request->quantity;
        $price = $request->price;
        $trigger_price = $request->triggerPrice;
        $disclosed_qty = $request->disclosedQty;
        $target = $request->target;
        $stoploss = $request->stoploss;
        $trailing_stoploss = $request->trailingStoploss;
        $validity = $request->validity;
        $amo = $request->amo;
        $is_active="active";
        $group_active = $request->group_active;
        $diff_qty = $request->diff_qty;
        $symboltoken="";$symbol="";$order_id=0;
        if($stoploss=="" || $stoploss==null){
            $stoploss = 0;
        }
        if($price=="" || $price==null){
            $price=0;
        }

        foreach($request->accounts as $key=> $account_id){

            $ta = TradingAccount::where('id', $account_id)->first();
            if($ta){
                if($ta->stock_brocker=="Angel"){

                    $smart_api  = new \AngelBroking\SmartApi( );
                    $jsonData = $smart_api ->GenerateSession($ta->login_id, $ta->password);

                    $jsonitem = json_decode(file_get_contents("https://margincalculator.angelbroking.com/OpenAPI_File/files/OpenAPIScripMaster.json"), true);

                    foreach ($jsonitem as $friend) {
                        // return $trading_symbol;
                        if ($friend['symbol'] == $trading_symbol){
                            $trade = Trade::where('orderid', $order_id)->first();
                            if($trade){
                                return back()->with('msg', 'Order Placement Successfull.');
                            }else{

                                $symboltoken = $friend['token'];
                                $symbol = $friend['symbol'];

                                $order = $smart_api ->PlaceOrder(
                                     array('variety' => $variety,
                                                     'tradingsymbol'  =>  $symbol,
                                                     'symboltoken' => $symboltoken,
                                                     'exchange' => $exchange,
                                                     'transactiontype' => $transaction_type,
                                                     'ordertype' => $order_type,
                                                     'quantity' => $quantity,
                                                     'producttype' => $product_type,
                                                     'price' => $price,
                                                     'squareoff' => 0,
                                                     'stoploss' => $stoploss,
                                                     'duration' => $validity));

                                $preData = json_decode($order, true);

                                if($preData['response_data']['data']['orderid']==null || $preData['response_data']['data']['orderid']==""){
                                    return back()->with('msg', 'Order Placement Failed.');
                                }
                                if(!$preData['response_data']['data']['orderid']){
                                    return back()->with('msg', 'Order Placement Failed.');
                                }

                                $order_id=$preData['response_data']['data']['orderid'];
                                $td = new Trade;
                                $td->account_id = $account_id;
                                $td->orderid = $preData['response_data']['data']['orderid'];
                                $td->transaction_type = $transaction_type;
                                $td->variety = $variety;
                                $td->exchange = $exchange;
                                $td->trading_symbol = $trading_symbol;
                                $td->product_type = $product_type;
                                $td->order_type = $order_type;
                                $td->quantity = $quantity;
                                $td->price = $price;
                                $td->trigger_price = $trigger_price;
                                $td->disclosed_qty = $disclosed_qty;
                                $td->target = $target;
                                $td->stoploss = $stoploss;
                                $td->trailing_stoploss = $trailing_stoploss;
                                $td->validity = $validity;
                                $td->amo = $account_id;
                                $td->trade_status = "";
                                $td->is_active = $is_active;
                                $td->save();
                            }
                        }
                    }

                }

                if($ta->stock_brocker=="Zerodha"){
                    if($ta->access_token!=null || $ta->access_token!=""){
                        try{
                            $kite = new KiteConnect(env('KITE_KEY'));
                            $kite->setAccessToken($ta->access_token);
                            $product="";
                            if($product_type=="DELIVERY"){
                                $product="NRML";
                            }else if($product_type=="INTRADAY"){
                                $product="MIS";
                            }else if($product_type=="CARRYFORWARD"){
                                $product="CNC";
                            }
                            $order = $kite->placeOrder($variety, [
                                "tradingsymbol" => $trading_symbol,
                                "exchange" => $exchange,
                                "quantity" => $quantity,
                                "transaction_type" => $transaction_type,
                                "order_type" => $order_type,
                                "product" => $product,
                                "price" => $price,
                                "validity" => $validity
                            ]);
                            if($order->order_id){
                                $order_id=$order->order_id;
                                $td = new Trade;
                                $td->account_id = $account_id;
                                $td->orderid = $order->order_id;
                                $td->transaction_type = $transaction_type;
                                $td->variety = $variety;
                                $td->exchange = $exchange;
                                $td->trading_symbol = $trading_symbol;
                                $td->product_type = $product_type;
                                $td->order_type = $order_type;
                                $td->quantity = $quantity;
                                $td->price = $price;
                                $td->trigger_price = $trigger_price;
                                $td->disclosed_qty = $disclosed_qty;
                                $td->target = $target;
                                $td->stoploss = $stoploss;
                                $td->trailing_stoploss = $trailing_stoploss;
                                $td->validity = $validity;
                                $td->amo = $account_id;
                                $td->trade_status = "";
                                $td->is_active = $is_active;
                                $td->save();
                            }
                        } catch (Handler $e) {

                        } catch(\KiteConnect\Exception\TokenException $e){

                        }
                    }
                }
            }

        }

        return back()->with('msg', 'Order Placement Successfull.');
    }

    public function orderPlaceByGroup(Request $request){
        $transaction_type = $request->transactiontype;
        $variety = $request->variety;
        $exchange = $request->exchange;
        $trading_symbol = $request->tradingsymbol;
        $product_type = $request->productType;
        $order_type = $request->orderType;
        $quantity = $request->quantity;
        $price = $request->price;
        $trigger_price = $request->triggerPrice;
        $disclosed_qty = $request->disclosedQty;
        $target = $request->target;
        $stoploss = $request->stoploss;
        $trailing_stoploss = $request->trailingStoploss;
        $validity = $request->validity;
        $amo = $request->amo;
        $is_active="active";
        $group_active = $request->group_active;
        $diff_qty = $request->diff_qty;
        $symboltoken="";$symbol="";$order_id=0;
        if($stoploss=="" || $stoploss==null){
            $stoploss = 0;
        }
        if($price=="" || $price==null){
            $price=0;
        }
        foreach($request->accounts as $key=> $account_id){

            $gd = DB::select('select * from group_detail where group_id = "'.$account_id.'"');
            if($gd){
                foreach($gd as $allgd){
                    $ta = TradingAccount::where('login_id', $allgd->account_id)->first();
                    if($ta){
                        if($ta->stock_brocker=="Angel"){

                            $smart_api  = new \AngelBroking\SmartApi( );
                            $jsonData = $smart_api ->GenerateSession($ta->login_id, $ta->password);
                            $jsonitem = json_decode(file_get_contents("https://margincalculator.angelbroking.com/OpenAPI_File/files/OpenAPIScripMaster.json"), true);
                            foreach ($jsonitem as $friend) {
                                // return $trading_symbol;
                                if ($friend['symbol'] == $trading_symbol){
                                    $trade = Trade::where('orderid', $order_id)->first();
                                    if($trade){
                                        return back()->with('msg', 'Order Placement Successfull.');
                                    }else{

                                        $symboltoken = $friend['token'];
                                        $symbol = $friend['symbol'];

                                        $order = $smart_api ->PlaceOrder(
                                             array('variety' => $variety,
                                                             'tradingsymbol'  =>  $symbol,
                                                             'symboltoken' => $symboltoken,
                                                             'exchange' => $exchange,
                                                             'transactiontype' => $transaction_type,
                                                             'ordertype' => $order_type,
                                                             'quantity' => $quantity,
                                                             'producttype' => $product_type,
                                                             'price' => $price,
                                                             'squareoff' => 0,
                                                             'stoploss' => $stoploss,
                                                             'duration' => $validity));

                                          $preData = json_decode($order, true);
                                        // print_r($preData)."<br><br>";

                                        if($preData['response_data']['data']['orderid']==null || $preData['response_data']['data']['orderid']==""){
                                            return back()->with('msg', 'Order Placement Failed.');
                                        }
                                        if(!$preData['response_data']['data']['orderid']){
                                            return back()->with('msg', 'Order Placement Failed.');
                                        }

                                        $order_id=$preData['response_data']['data']['orderid'];
                                        $td = new Trade;
                                        $td->account_id = $account_id;
                                        $td->orderid = $preData['response_data']['data']['orderid'];
                                        $td->transaction_type = $transaction_type;
                                        $td->variety = $variety;
                                        $td->exchange = $exchange;
                                        $td->trading_symbol = $trading_symbol;
                                        $td->product_type = $product_type;
                                        $td->order_type = $order_type;
                                        $td->quantity = $quantity;
                                        $td->price = $price;
                                        $td->trigger_price = $trigger_price;
                                        $td->disclosed_qty = $disclosed_qty;
                                        $td->target = $target;
                                        $td->stoploss = $stoploss;
                                        $td->trailing_stoploss = $trailing_stoploss;
                                        $td->validity = $validity;
                                        $td->amo = $account_id;
                                        $td->trade_status = "";
                                        $td->is_active = $is_active;
                                        $td->save();
                                    }
                                }
                            }

                        }

                        if($ta->stock_brocker=="Zerodha"){
                            if($ta->access_token!=null || $ta->access_token!=""){
                                try{
                                    $kite = new KiteConnect(env('KITE_KEY'));
                                    $kite->setAccessToken($ta->access_token);
                                    if($product_type=="DELIVERY"){
                                        $product="NRML";
                                    }else if($product_type=="INTRADAY"){
                                        $product="MIS";
                                    }else if($product_type=="CARRYFORWARD"){
                                        $product="CNC";
                                    }
                                    $order = $kite->placeOrder($variety, [
                                        "tradingsymbol" => $trading_symbol,
                                        "exchange" => $exchange,
                                        "quantity" => $quantity,
                                        "transaction_type" => $transaction_type,
                                        "order_type" => $order_type,
                                        "product" => $product,
                                        "price" => $price,
                                        "validity" => $validity,
                                        'squareoff' => 0,
                                        'stoploss' => $stoploss,
                                    ]);
                                    if($order->order_id){
                                        $order_id=$order->order_id;
                                        $td = new Trade;
                                        $td->account_id = $account_id;
                                        $td->orderid = $order->order_id;
                                        $td->transaction_type = $transaction_type;
                                        $td->variety = $variety;
                                        $td->exchange = $exchange;
                                        $td->trading_symbol = $trading_symbol;
                                        $td->product_type = $product;
                                        $td->order_type = $order_type;
                                        $td->quantity = $quantity;
                                        $td->price = $price;
                                        $td->trigger_price = $trigger_price;
                                        $td->disclosed_qty = $disclosed_qty;
                                        $td->target = $target;
                                        $td->stoploss = $stoploss;
                                        $td->trailing_stoploss = $trailing_stoploss;
                                        $td->validity = $validity;
                                        $td->amo = $account_id;
                                        $td->trade_status = "";
                                        $td->is_active = $is_active;
                                        $td->save();
                                    }
                                } catch (Handler $e) {

                                } catch(\KiteConnect\Exception\TokenException $e){

                                }
                            }
                        }
                    }
                }
            }

        }

        // return back()->with('msg', 'Order Placement Successfull.');
    }

    public function orderPlaceByGroupAndDiffQty(Request $request){
        $transaction_type = $request->transactiontype;
        $variety = $request->variety;
        $exchange = $request->exchange;
        $trading_symbol = $request->tradingsymbol;
        $product_type = $request->productType;
        $order_type = $request->orderType;
        $price = $request->price;
        $trigger_price = $request->triggerPrice;
        $disclosed_qty = $request->disclosedQty;
        $target = $request->target;
        $stoploss = $request->stoploss;
        $trailing_stoploss = $request->trailingStoploss;
        $validity = $request->validity;
        $amo = $request->amo;
        $is_active="active";
        $group_active = $request->group_active;
        $diff_qty = $request->diff_qty;
        $symboltoken="";$symbol="";$order_id=0;
        if($stoploss=="" || $stoploss==null){
            $stoploss = 0;
        }
        if($price=="" || $price==null){
            $price=0;
        }
        foreach($request->checkBoxLogin as $key=> $account_id){

            $gd = DB::select('select * from group_detail where group_id = "'.$account_id.'"');
            if($gd){
                foreach($gd as $allgd){
                    $ta = TradingAccount::where('login_id', $allgd->account_id)->first();
                    if($ta){
                        if($ta->stock_brocker=="Angel"){

                            $smart_api  = new \AngelBroking\SmartApi( );
                            $jsonData = $smart_api ->GenerateSession($ta->login_id, $ta->password);
                            $jsonitem = json_decode(file_get_contents("https://margincalculator.angelbroking.com/OpenAPI_File/files/OpenAPIScripMaster.json"), true);
                            foreach ($jsonitem as $friend) {
                                // return $trading_symbol;
                                if ($friend['symbol'] == $trading_symbol){
                                    $trade = Trade::where('orderid', $order_id)->first();
                                    if($trade){
                                        return back()->with('msg', 'Order Placement Successfull.');
                                    }else{

                                        $symboltoken = $friend['token'];
                                        $symbol = $friend['symbol'];

                                        $order = $smart_api ->PlaceOrder(
                                             array('variety' => $variety,
                                                             'tradingsymbol'  =>  $symbol,
                                                             'symboltoken' => $symboltoken,
                                                             'exchange' => $exchange,
                                                             'transactiontype' => $transaction_type,
                                                             'ordertype' => $order_type,
                                                             'quantity' => $request->diffQty.$account_id,
                                                             'producttype' => $product_type,
                                                             'price' => $price,
                                                             'squareoff' => 0,
                                                             'stoploss' => $stoploss,
                                                             'duration' => $validity));

                                        $preData = json_decode($order, true);

                                        if($preData['response_data']['data']['orderid']==null || $preData['response_data']['data']['orderid']==""){
                                            return back()->with('msg', 'Order Placement Failed.');
                                        }
                                        if(!$preData['response_data']['data']['orderid']){
                                            return back()->with('msg', 'Order Placement Failed.');
                                        }

                                        $order_id=$preData['response_data']['data']['orderid'];
                                        $td = new Trade;
                                        $td->account_id = $account_id;
                                        $td->orderid = $preData['response_data']['data']['orderid'];
                                        $td->transaction_type = $transaction_type;
                                        $td->variety = $variety;
                                        $td->exchange = $exchange;
                                        $td->trading_symbol = $trading_symbol;
                                        $td->product_type = $product_type;
                                        $td->order_type = $order_type;
                                        $td->quantity = $request->diffQty.$account_id;
                                        $td->price = $price;
                                        $td->trigger_price = $trigger_price;
                                        $td->disclosed_qty = $disclosed_qty;
                                        $td->target = $target;
                                        $td->stoploss = $stoploss;
                                        $td->trailing_stoploss = $trailing_stoploss;
                                        $td->validity = $validity;
                                        $td->amo = $account_id;
                                        $td->trade_status = "";
                                        $td->is_active = $is_active;
                                        $td->save();
                                    }
                                }
                            }

                        }

                        if($ta->stock_brocker=="Zerodha"){
                            if($ta->access_token!=null || $ta->access_token!=""){
                                try{
                                    $kite = new KiteConnect(env('KITE_KEY'));
                                    $kite->setAccessToken($ta->access_token);
                                    if($product_type=="DELIVERY"){
                                        $product="NRML";
                                    }else if($product_type=="INTRADAY"){
                                        $product="MIS";
                                    }else if($product_type=="CARRYFORWARD"){
                                        $product="CNC";
                                    }
                                    $order = $kite->placeOrder($variety, [
                                        "tradingsymbol" => $trading_symbol,
                                        "exchange" => $exchange,
                                        "quantity" => $quantity,
                                        "transaction_type" => $transaction_type,
                                        "order_type" => $order_type,
                                        "product" => $product,
                                        "price" => $price,
                                        "validity" => $validity,
                                        'squareoff' => 0,
                                        'stoploss' => $stoploss,
                                    ]);
                                    if($order->order_id){
                                        $order_id=$order->order_id;
                                        $td = new Trade;
                                        $td->account_id = $account_id;
                                        $td->orderid = $order->order_id;
                                        $td->transaction_type = $transaction_type;
                                        $td->variety = $variety;
                                        $td->exchange = $exchange;
                                        $td->trading_symbol = $trading_symbol;
                                        $td->product_type = $product;
                                        $td->order_type = $order_type;
                                        $td->quantity = $quantity;
                                        $td->price = $price;
                                        $td->trigger_price = $trigger_price;
                                        $td->disclosed_qty = $disclosed_qty;
                                        $td->target = $target;
                                        $td->stoploss = $stoploss;
                                        $td->trailing_stoploss = $trailing_stoploss;
                                        $td->validity = $validity;
                                        $td->amo = $account_id;
                                        $td->trade_status = "";
                                        $td->is_active = $is_active;
                                        $td->save();
                                    }
                                } catch (Handler $e) {
                                    \Log::debug($e->getMessage());
                                } catch(\KiteConnect\Exception\TokenException $e){
                                    \Log::debug($e->getMessage());
                                }
                            }
                        }
                    }
                }
            }
        }
        return back()->with('msg', 'Order Placement Successfull.');

    }

    public function orderPlaceByDiffQtyOnly(Request $request){
        $transaction_type = $request->transactiontype;
        $variety = $request->variety;
        $exchange = $request->exchange;
        $trading_symbol = $request->tradingsymbol;
        $product_type = $request->productType;
        $order_type = $request->orderType;
        $quantity = $request->quantity;
        $price = $request->price;
        $trigger_price = $request->triggerPrice;
        $disclosed_qty = $request->disclosedQty;
        $target = $request->target;
        $stoploss = $request->stoploss;
        $trailing_stoploss = $request->trailingStoploss;
        $validity = $request->validity;
        $amo = $request->amo;
        $is_active="active";
        $group_active = $request->group_active;
        $diff_qty = $request->diff_qty;
        $symboltoken="";$symbol="";$order_id=0;
        if($stoploss=="" || $stoploss==null){
            $stoploss = 0;
        }
        if($price=="" || $price==null){
            $price=0;
        }
        foreach($request->checkBoxLogin as $key=> $account_id){

            $ta = TradingAccount::where('login_id', $account_id)->first();
            if($ta){
                if($ta->stock_brocker=="Angel"){

                    $smart_api  = new \AngelBroking\SmartApi( );
                    $jsonData = $smart_api ->GenerateSession($ta->login_id, $ta->password);
                    $jsonitem = json_decode(file_get_contents("https://margincalculator.angelbroking.com/OpenAPI_File/files/OpenAPIScripMaster.json"), true);
                    foreach ($jsonitem as $friend) {
                        // return $trading_symbol;
                        if ($friend['symbol'] == $trading_symbol){
                            $trade = Trade::where('orderid', $order_id)->first();
                            if($trade){
                                return back()->with('msg', 'Order Placement Successfull.');
                            }else{

                                $symboltoken = $friend['token'];
                                $symbol = $friend['symbol'];

                                $order = $smart_api ->PlaceOrder(
                                     array('variety' => $variety,
                                                     'tradingsymbol'  =>  $symbol,
                                                     'symboltoken' => $symboltoken,
                                                     'exchange' => $exchange,
                                                     'transactiontype' => $transaction_type,
                                                     'ordertype' => $order_type,
                                                     'quantity' => $request->diffQty.$account_id,
                                                     'producttype' => $product_type,
                                                     'price' => $price,
                                                     'squareoff' => 0,
                                                     'stoploss' => $stoploss,
                                                     'duration' => $validity));

                                $preData = json_decode($order, true);

                                if($preData['response_data']['data']['orderid']==null || $preData['response_data']['data']['orderid']==""){
                                    return back()->with('msg', 'Order Placement Failed.');
                                }
                                if(!$preData['response_data']['data']['orderid']){
                                    return back()->with('msg', 'Order Placement Failed.');
                                }

                                $order_id=$preData['response_data']['data']['orderid'];
                                $td = new Trade;
                                $td->account_id = $account_id;
                                $td->orderid = $preData['response_data']['data']['orderid'];
                                $td->transaction_type = $transaction_type;
                                $td->variety = $variety;
                                $td->exchange = $exchange;
                                $td->trading_symbol = $trading_symbol;
                                $td->product_type = $product_type;
                                $td->order_type = $order_type;
                                $td->quantity = $request->diffQty.$account_id;
                                $td->price = $price;
                                $td->trigger_price = $trigger_price;
                                $td->disclosed_qty = $disclosed_qty;
                                $td->target = $target;
                                $td->stoploss = $stoploss;
                                $td->trailing_stoploss = $trailing_stoploss;
                                $td->validity = $validity;
                                $td->amo = $account_id;
                                $td->trade_status = "";
                                $td->is_active = $is_active;
                                $td->save();
                            }
                        }
                    }

                }

                if($ta->stock_brocker=="Zerodha"){
                    if($ta->access_token!=null || $ta->access_token!=""){
                        try{
                            $kite = new KiteConnect(env('KITE_KEY'));
                            $kite->setAccessToken($ta->access_token);
                            if($product_type=="DELIVERY"){
                                $product="NRML";
                            }else if($product_type=="INTRADAY"){
                                $product="MIS";
                            }else if($product_type=="CARRYFORWARD"){
                                $product="CNC";
                            }
                            $order = $kite->placeOrder($variety, [
                                "tradingsymbol" => $trading_symbol,
                                "exchange" => $exchange,
                                "quantity" => $quantity,
                                "transaction_type" => $transaction_type,
                                "order_type" => $order_type,
                                "product" => $product,
                                "price" => $price,
                                "validity" => $validity,
                                'squareoff' => 0,
                                'stoploss' => $stoploss,
                            ]);
                            if($order->order_id){
                                $order_id=$order->order_id;
                                $td = new Trade;
                                $td->account_id = $account_id;
                                $td->orderid = $order->order_id;
                                $td->transaction_type = $transaction_type;
                                $td->variety = $variety;
                                $td->exchange = $exchange;
                                $td->trading_symbol = $trading_symbol;
                                $td->product_type = $product;
                                $td->order_type = $order_type;
                                $td->quantity = $quantity;
                                $td->price = $price;
                                $td->trigger_price = $trigger_price;
                                $td->disclosed_qty = $disclosed_qty;
                                $td->target = $target;
                                $td->stoploss = $stoploss;
                                $td->trailing_stoploss = $trailing_stoploss;
                                $td->validity = $validity;
                                $td->amo = $account_id;
                                $td->trade_status = "";
                                $td->is_active = $is_active;
                                $td->save();
                            }
                        } catch (Handler $e) {
                            \Log::debug($e->getMessage());
                        } catch(\KiteConnect\Exception\TokenException $e){
                            \Log::debug($e->getMessage());
                        }
                    }
                }
            }

        }

        return back()->with('msg', 'Order Placement Successfull.');
    }

    public function update(Request $request){

        $order_type = $request->orderType;
        $quantity = $request->quantity;
        $price = $request->price;
        $trigger_price = $request->triggerPrice;
        $is_active="active";
        $token=0;
        $symbol="";
        if($request->chkAccountId=='' || $request->chkAccountId==null){
            return back()->with('accountmsg', 'Select Account');
        }
        if($price=="" || $price==null){
            $price=0;
        }
        foreach($request->chkAccountId as $key=> $order_id){
            $trade = Trade::where('orderid', $order_id)->first();
            if($trade){
                $ta = TradingAccount::where('id', $trade->account_id)->first();
                if($ta){
                    $jsonitem = json_decode(file_get_contents("https://margincalculator.angelbroking.com/OpenAPI_File/files/OpenAPIScripMaster.json"), true);
                    foreach ($jsonitem as $friend) {
                        if ($friend['symbol'] == $trade->trading_symbol){
                            $token = $friend['token'];
                            $symbol = $friend['symbol'];
                        }
                    }
                    if($ta->stock_brocker=="Angel"){
                        if($ta->trading_platform=="SMART_API"){
                            $smart_api  = new \AngelBroking\SmartApi( );
                            $jsonData = $smart_api ->GenerateSession($ta->login_id, $ta->password);
                            $order = $smart_api ->ModifyOrder(array('variety' => $trade->variety,
                                                 'tradingsymbol'  =>  $symbol,
                                                 'symboltoken' => $token,
                                                 'exchange' => $trade->exchange,
                                                 'transactiontype' => $trade->transaction_type,
                                                 'ordertype' => $order_type,
                                                 'quantity' => $quantity,
                                                 'producttype' => $trade->product_type,
                                                 'price' => $price,
                                                 'squareoff' => 0,
                                                 'stoploss' => $trade->stoploss,
                                                 'duration' => $trade->validity,
                                                 'orderid' =>$order_id));

                            $preData = json_decode($order, true);
                            $trade->account_id = $trade->account_id;
                            $trade->orderid = $order_id;
                            $trade->transaction_type = $trade->transaction_type;
                            $trade->product_type = $trade->product_type;
                            $trade->order_type = $order_type;
                            $trade->quantity = $quantity;
                            $trade->price = $price;
                            $trade->trigger_price = $trigger_price;
                            $trade->amo = $trade->account_id;
                            // $trade->trade_status = $trade->account_id;
                            $trade->is_active = $is_active;
                            $trade->save();
                        }
                    }else if($ta->stock_brocker=="Zerodha"){
                        if($ta->access_token!=null || $ta->access_token!=""){
                            try{
                                $kite = new KiteConnect(env('KITE_KEY'));
                                $kite->setAccessToken($ta->access_token);
                                $kite->modifyOrder($trade->variety, $order_id, [
                                    "tradingsymbol" => $trade->trading_symbol,
                                    "exchange" => $trade->exchange,
                                    "quantity" => $quantity,
                                    "transaction_type" => $trade->transaction_type,
                                    "order_type" => $order_type,
                                    "product" => $trade->product_type,
                                    "price" => $price,
                                    "validity" => $trade->validity,
                                    'squareoff' => 0,
                                    'stoploss' => $trade->stoploss,
                                ]);
                            } catch (Handler $e) {
                                \Log::debug($e->getMessage());
                            } catch(\KiteConnect\Exception\TokenException $e){
                                \Log::debug($e->getMessage());
                            }
                        }
                    }


                }
            }

        }

        return Redirect::to('/orders/list')->with('msg', 'Order Updated Successfull.');

    }

    public function getCancelOrder(Request $request){
        $preOrder="";
        $is_active="inactive";
        if($request->chkAccountId=='' || $request->chkAccountId==null){
            return back()->with('accountmsg', 'Select Account');
        }
        foreach($request->chkAccountId as $key=> $order_id){
            $trade = Trade::where('orderid', $order_id)->first();
            if($trade){
                $ta = TradingAccount::where('id', $trade->account_id)->first();
                if($ta){
                    $jsonitem = json_decode(file_get_contents("https://margincalculator.angelbroking.com/OpenAPI_File/files/OpenAPIScripMaster.json"), true);
                    foreach ($jsonitem as $friend) {
                        if ($friend['symbol'] == $trade->trading_symbol){
                            $token = $friend['token'];
                            $symbol = $friend['symbol'];
                        }
                    }
                    if($ta->stock_brocker=="Angel"){
                        if($ta->trading_platform=="SMART_API"){
                            $smart_api  = new \AngelBroking\SmartApi( );
                            $jsonData = $smart_api ->GenerateSession($ta->login_id, $ta->password);
                            $cancelOrder = $smart_api ->CancelOrder(array('variety' => $trade->variety, 'orderid' => $order_id));
                            $preOrder = json_decode($cancelOrder, true);
                            if($preOrder['response_data']['status']==true){
                                if($preOrder['response_data']['message']=="SUCCESS"){
                                    if($preOrder['response_data']['data']!=null){
                                        $trade->trade_status = "Cancel";
                                        $trade->is_active = $is_active;
                                        $trade->save();
                                    }
                                }
                            }
                        }
                    }else if($ta->stock_brocker=="Zerodha"){
                        if($ta->access_token!=null || $ta->access_token!=""){
                            try{
                                $kite = new KiteConnect(env('KITE_KEY'));
                                $kite->setAccessToken($ta->access_token);
                                $kite->cancelOrder($trade->variety, $order_id, []);
                                $trade->trade_status = "Cancel";
                                $trade->is_active = $is_active;
                                $trade->save();
                            } catch (Handler $e) {
                                \Log::debug($e->getMessage());
                            } catch(\KiteConnect\Exception\TokenException $e){
                                \Log::debug($e->getMessage());
                            }
                        }
                    }

                }
            }
        }
        return Redirect::to('/orders/list')->with('msg', 'Order Cancelled Successfull.');
    }

}


