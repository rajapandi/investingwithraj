<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use view;
use DB;
use Session;
use App\Models\Schedular;
use App\Models\PercentageSchedular;
use App\Models\PriceShcedular;
use App\Models\SchedularAccount;
use App\Models\TimeSchedular;
use App\Models\TradingAccount;
use AngelBroking\SmartApi;
use KiteConnect\KiteConnect;


class SchedularController extends Controller
{
    //WhizzAct
    public static function storeSchedular(Request $request){
        $schedular = new Schedular();
        $schedularDetail = json_decode($_COOKIE['schedular'], false);
        $schedular->transaction_type = $request->transactiontype;
        $schedular->variety = $request->variety;
        $schedular->exchange = $request->exchange;
        $schedular->symbol = $request->tradingsymbol;
        $schedular->product_type = $request->productType;
        $schedular->order_type = $request->orderType;
        $schedular->quantity = $request->quantity;
        $schedular->price = 0;//$schedularDetail->schedular_price; //schedular price
        $schedular->validity_type = $schedularDetail->validity_type;
        $schedular->validity = $schedularDetail->schedular_validity;
        $schedular->no_of_order = $schedularDetail->no_of_order;
        $schedular->executed_order = 0;//$request->executed_order;
        $schedular->schedular_type = $request->schedular_type;
        $schedular->is_active = 'active';// $request->is_active;

        if($schedular->save()){
            if($schedular->id!=null){
                $sa = new SchedularAccount();
                foreach($request->accounts as $key=> $account_id){
                    $ta = TradingAccount::where('id', $account_id)->first();
                    if($ta){
                        if($ta->stock_brocker=="Angel"){
                            $smart_api  = new \AngelBroking\SmartApi( );
                            $jsonData = $smart_api ->GenerateSession($ta->login_id, $ta->password);
                            $jsonitem = json_decode(file_get_contents("https://margincalculator.angelbroking.com/OpenAPI_File/files/OpenAPIScripMaster.json"), true);
                            foreach ($jsonitem as $symbolName) {
                                if ($symbolName['name'] == $request->tradingsymbol){
                                    $symboltoken = $symbolName['token'];
                                    $symbol = $symbolName['symbol'];
                                    $sa->schedular_id = $schedular->id;
                                    $sa->login_id = $account_id;
                                    $sa->platform = $ta->stock_brocker;
                                    $sa->symbol = $symbolName['symbol'];
                                    $sa->symbol_token = $symbolName['token'];
                                    $sa->is_active = 'active';
                                    $sa->save();
                                }
                            }
                        } else if($ta->stock_brocker=="Zerodha"){
                            if($ta->access_token!=null || $ta->access_token!=""){
                                try{
                                    $kite = new KiteConnect(env('KITE_KEY'));
                                    $kite->setAccessToken($ta->access_token);
                                    $jsonitem = $kite->getInstruments();
                                    foreach ($jsonitem as $symbolName) {
                                        if ($symbolName['name'] == $request->tradingsymbol){

                                            $symboltoken = $symbolName['token'];
                                            $symbol = $symbolName['symbol'];
                                            $sa->schedular_id = $schedular->id;
                                            $sa->login_id = $account_id;
                                            $sa->platform = $ta->stock_brocker;
                                            $sa->symbol = $symbolName['symbol'];
                                            $sa->symbol_token = $symbolName['token'];
                                            $sa->is_active = 'active';
                                            $sa->save();
                                        }
                                    }

                                } catch (Handler $e) {
                                    \Log::debug($e->getMessage());
                                } catch(\KiteConnect\Exception\TokenException $e){
                                    \Log::debug($e->getMessage());
                                }
                            }

                        }
                        $sa = new SchedularAccount();
                        $sa->schedular_id = $schedular->id;
                        $sa->login_id = $ta->login_id;
                        $sa->platform = $ta->stock_brocker;
                        $sa->symbol = $symbol;
                        $sa->symbol_token = $symboltoken;
                        $sa->is_active = "active";
                        $sa->save();
                    }

                }
                if($request->schedular_type == $schedular->PRICE){
                    return SchedularController::priceSchedular($request, $schedular->id, $schedularDetail, $symbol, $request->exchange);
                } else if($request->schedular_type == $schedular->TIME){
                    return SchedularController::timeSchedular($request, $schedular->id, $schedularDetail, $symbol, $request->exchange);
                } else if($request->schedular_type == $schedular->PERCENTAGE){
                    return SchedularController::percentageSchedular($request, $schedular->id, $schedularDetail, $symbol, $request->exchange);
                }
            }
        }else{
            return back()->with("msg", 'Something went wrong');
        }

    }

    public static function priceSchedular(Request $request, $schedular_id, $schedularDetail, $symbol, $exchange){

        if($schedularDetail->price_type=="LTP"){
            $price = $this->getLTP($symbol, $exchange);
        }
        $ps = new PriceShcedular();
        $ps->schedular_id = $schedular_id;
        $ps->price = $price;
        $ps->price_type = $schedularDetail->price_type;
        $ps->below_above = $schedularDetail->below_above;
        $ps->is_active = 'active';//$request->is_active;
        if($ps->save()){
            return back()->with("msg", 'Shcedular Created Successfull');
        }else{
            return back()->with("msg", 'Something went wrong');
        }

    }

    public static function timeSchedular(Request $request, $schedular_id, $schedularDetail, $symbol, $exchange){
        $time = new TimeSchedular();
        $time->schedular_id = $schedular_id;
        $time->price = $this->getLTP($symbol, $exchange);
        $time->price_type = 'LTP';
        $time->frequency_diff = $schedularDetail->frequency_diff;
        $time->is_active = 'active';
        if($time->save()){
            return back()->with("msg", 'Shcedular Created Successfull');
        }else{
            return back()->with("msg", 'Something went wrong');
        }

    }

    public static function percentageSchedular(Request $request, $schedular_id, $schedularDetail, $symbol, $exchange){
        $percentage = new PercentageSchedular();
        $percentage->schedular_id = $schedular_id;
        $percentage->price = $this->getLTP($symbol, $exchange);
        $percentage->price_type = "LTP";
        $percentage->set_price = $schedularDetail->set_price;
        $percentage->percentage = $schedularDetail->percentage;
        $percentage->percentage_type = $schedularDetail->below_above;
        $percentage->is_active = 'active';
        if($percentage->save()){
            return back()->with("msg", 'Shcedular Created Successfull');
        }else{
            return back()->with("msg", 'Something went wrong');
        }

    }

    public function index(Request $request){
        $sd = Schedular::all();
        return view('schedular.index', compact('sd'));
    }

    public function schedularOrder(Request $request){
        return view('trade.create');
    }

    public function getInstruments(Request $request){
        $jsonData="";
        $ta = TradingAccount::where('id', 13)->first();
        if($ta){
            if($ta->access_token!=null || $ta->access_token!=""){
                try{
                    $kite = new KiteConnect(env('KITE_KEY'));
                    $kite->setAccessToken($ta->access_token);
                    $jsonData = $kite->getInstruments();

                    return json_decode($jsonData, false);
                }catch (Handler $e) {
                    \Log::debug($e->getMessage());
                } catch(\KiteConnect\Exception\TokenException $e){
                    \Log::debug($e->getMessage());
                }
            }
        }
    }

    public static function getLTP(Request $request, $symbol, $exchange){
        $ltp = array();
        $smart_api  = new \AngelBroking\SmartApi();
        $ta = TradingAccount::where('login_id', "CS0523")->first();
        $kite = new KiteConnect(env('KITE_KEY'));
        $kite->setAccessToken($ta->access_token);
        $ltp = array($exchange.':'.$symbol);
        if(json_encode($kite->getLTP($ltp))){
            $result = json_encode($kite->getLTP($ltp));
            return 0;//$result;//->$ltp->last_price;
        }else{
            return 0;
        }
    }

}
