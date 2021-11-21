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
use App\Models\Instruments;
use App\Models\Holding;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Carbon;
use File;
use AngelBroking\SmartApi;
use KiteConnect\KiteConnect;
use phpgangsta;
use Zxing;

class HomeController extends Controller{

    public function getChangeStockBroker(Request $request){
        if($request->str=="all"){
            $ta = TradingAccount::all();
            if($ta){
                return view('dashboard.getChangeStockBroker', compact('ta'));
            }
        }else{
            $ta = TradingAccount::where('stock_brocker', $request->str)->get();
            if($ta){
                return view('dashboard.getChangeStockBroker', compact('ta'));
            }
        }
    }

    public function getPortfolio(Request $request){
        $id = $request->id;
        $preRMS="";$jsonData="";
        $net=0;$availablecash=0;$availableintradaypayin=0;$availablelimitmargin=0;$m2munrealized=0;$m2mrealized=0;
        $utilisedturnover=0;$utiliseddebits=0;$utilisedspan=0;
        if($id!='all'){
            $allta = TradingAccount::where('id', $id)->first();
            if($allta){
                if($allta->stock_brocker=="Angel"){
                    if($allta->trading_platform=="SMART_API"){
                        $smart_api  = new \AngelBroking\SmartApi();
                        $smart_api ->GenerateSession($allta->login_id, $allta->password);
                        $jsonData = $smart_api ->GetRMS();
                        $preRMS = json_decode($jsonData, true);

                        $net += number_format((float)$preRMS['response_data']['data']['net'], 2, '.', '');
                        $availablecash += number_format((float)$preRMS['response_data']['data']['availablecash'], 2, '.', '');
                        $availableintradaypayin += number_format((float)$preRMS['response_data']['data']['availableintradaypayin'], 2, '.', '');
                        $availablelimitmargin += number_format((float)$preRMS['response_data']['data']['availablelimitmargin'], 2, '.', '');
                        $m2munrealized += number_format((float)$preRMS['response_data']['data']['m2munrealized'], 2, '.', '');
                        $m2mrealized += number_format((float)$preRMS['response_data']['data']['m2mrealized'], 2, '.', '');
                        $utilisedturnover += number_format((float)$preRMS['response_data']['data']['utilisedturnover'], 2, '.', '');
                        $utiliseddebits += number_format((float)$preRMS['response_data']['data']['utiliseddebits'], 2, '.', '');
                        $utilisedspan += number_format((float)$preRMS['response_data']['data']['utilisedspan'], 2, '.', '');
                    }
                }
            }

        }else{
            $ta = TradingAccount::all();
            if($ta){
                foreach($ta as $allta){
                    if($allta->stock_brocker=="Angel"){
                        if($allta->trading_platform=="SMART_API"){
                            $smart_api  = new \AngelBroking\SmartApi();
                            $smart_api ->GenerateSession($allta->login_id, $allta->password);
                            $jsonData = $smart_api ->GetRMS();
                            $preRMS = json_decode($jsonData, true);

                            $net += number_format((float)$preRMS['response_data']['data']['net'], 2, '.', '');
                            $availablecash += number_format((float)$preRMS['response_data']['data']['availablecash'], 2, '.', '');
                            $availableintradaypayin += number_format((float)$preRMS['response_data']['data']['availableintradaypayin'], 2, '.', '');
                            $availablelimitmargin += number_format((float)$preRMS['response_data']['data']['availablelimitmargin'], 2, '.', '');
                            $m2munrealized += number_format((float)$preRMS['response_data']['data']['m2munrealized'], 2, '.', '');
                            $m2mrealized += number_format((float)$preRMS['response_data']['data']['m2mrealized'], 2, '.', '');
                            $utilisedturnover += number_format((float)$preRMS['response_data']['data']['utilisedturnover'], 2, '.', '');
                            $utiliseddebits += number_format((float)$preRMS['response_data']['data']['utiliseddebits'], 2, '.', '');
                            $utilisedspan += number_format((float)$preRMS['response_data']['data']['utilisedspan'], 2, '.', '');
                        }
                    }
                }
            }
        }
        return view('dashboard.getPortfolio', compact('net', 'availablecash', 'availableintradaypayin', 'availablelimitmargin', 'm2munrealized', 'm2mrealized', 'utilisedturnover',
        'utiliseddebits', 'utilisedspan'));

    }

    public function index(Request $request){
        $preRMS="";$jsonData="";$GetHolding="";$preHoldings="";$orders="";
        $net=0;$availablecash=0;$availableintradaypayin=0;$availablelimitmargin=0;$m2munrealized=0;$m2mrealized=0;
        $utilisedturnover=0;$utiliseddebits=0;$utilisedspan=0;
        $totalinvestment=0;$currentvalue=0;
        $data=[];
        $GetHolding = array();
        $order = array();
        $rejectedOrder=0;
        $cancelOrder=0;
        $openOrder=0;$last_price=0;
        $excutOrder=0;$dayPl=0;$close_price=0;
        $kiteHolding="";$kiteArray=[];
        $kiteOrder="";$kiteOrderArray=array();$kiteMargin="";

        if(isset($_GET['brocker']) && $_GET['brocker']!="all" && !isset($_GET['account'])){
             $ta = TradingAccount::where('stock_brocker', $_GET['brocker'])->get();
        }else  if(isset($_GET['brocker']) && isset($_GET['account']) && $_GET['brocker']!="all"){
            if($_GET['account'] != null && $_GET['account'] != "all"){
                $ta = TradingAccount::where('stock_brocker', $request->brocker)->where('login_id', $request->account)->get();
            }else{
                $ta = TradingAccount::where('stock_brocker', $request->brocker)->get();
            }
        }else  if(isset($_GET['account']) && !isset($_GET['brocker'])){
            if($_GET['account'] != null && $_GET['account'] != "all"){
                $ta = TradingAccount::where('login_id', $request->account)->get();
            }else{
                $ta = TradingAccount::all();
            }
        }else if(!isset($_GET['account']) && !isset($_GET['brocker'])){
            $ta = TradingAccount::all();
        }else {
            $ta = TradingAccount::all();
        }
        if($ta){
            foreach($ta as $allta){
                // echo $allta->login_id;
                if($allta->stock_brocker=="Angel"){
                    if($allta->trading_platform=="SMART_API"){
                        $smart_api  = new \AngelBroking\SmartApi();
                        $smart_api ->GenerateSession($allta->login_id, $allta->password);
                        // RMS
                        $jsonData = $smart_api->GetRMS();
                        $preRMS = json_decode($jsonData, true);
                        $availablecash += number_format((float)$preRMS['response_data']['data']['availablecash'], 2, '.', '');;

                        // Holding
                        $preHoldings =  json_decode($smart_api ->GetHoldings(), true);
                        if($preHoldings['response_data']['data']!=null){
                            for($i=0;$i<count($preHoldings['response_data']['data']);$i++){
                                $totalinvestment = $totalinvestment+number_format((float)$preHoldings['response_data']['data'][$i]['quantity']*$preHoldings['response_data']['data'][$i]['averageprice'], 2, '.', '');
                                $currentvalue = $currentvalue+number_format((float)$preHoldings['response_data']['data'][$i]['quantity']*$preHoldings['response_data']['data'][$i]['ltp'], 2, '.', '');
                                $dayPl = $dayPl+number_format((float)$preHoldings['response_data']['data'][$i]['quantity']*($preHoldings['response_data']['data'][$i]['ltp']-$preHoldings['response_data']['data'][$i]['close']), 2, '.', '');
                                $close_price = $close_price+($preHoldings['response_data']['data'][$i]['close']*$preHoldings['response_data']['data'][$i]['quantity']);
                                $last_price = $last_price+($preHoldings['response_data']['data'][$i]['ltp']*$preHoldings['response_data']['data'][$i]['quantity']);
                            }
                        }

                        // Orders
                         $orders = json_decode($smart_api ->GetOrderBook(), true);
                        if(isset($orders['response_data']['data'])){
                            if($orders['response_data']['data']!=null){
                                for($i=0;$i<count($orders['response_data']['data']);$i++){
                                    if($orders['response_data']['data'][$i]['orderstatus']=="rejected"){
                                        $rejectedOrder = $rejectedOrder+1;
                                    }else if($orders['response_data']['data'][$i]['orderstatus']=="AMO CANCELLED"){
                                        $cancelOrder = $cancelOrder+1;
                                    }else if($orders['response_data']['data'][$i]['orderstatus']=="AMO SUBMITTED"){
                                        $excutOrder = $excutOrder+1;
                                    }else {
                                        $openOrder = $openOrder+1;
                                    }
                                }
                            }
                        }
                    }
                }else if($allta->stock_brocker=="Zerodha"){
                    if($allta->access_token!="" || $allta->access_token!=null){
                        try {
                            $kite = new KiteConnect(env('KITE_KEY'));
                            $kite->setAccessToken($allta->access_token);

                            $kiteMargin = json_encode($kite->getMargins("equity"));
                            $availablecash = $availablecash+json_decode($kiteMargin)->net;
                            $kiteHolding = $kite->getHoldings();
                            for($i=0;$i<count($kiteHolding);$i++){
                                $totalinvestment = $totalinvestment+number_format((float)$kiteHolding[$i]->quantity*$kiteHolding[$i]->average_price, 2, '.', '');
                                $currentvalue = $currentvalue+number_format((float)$kiteHolding[$i]->quantity*$kiteHolding[$i]->last_price, 2, '.', '');
                                $dayPl = $dayPl+number_format((float)$kiteHolding[$i]->quantity*($kiteHolding[$i]->last_price-$kiteHolding[$i]->close_price), 2, '.', '');
                                $close_price = $close_price+($kiteHolding[$i]->close_price*$kiteHolding[$i]->quantity);
                                $last_price = $last_price+($kiteHolding[$i]->last_price*$kiteHolding[$i]->quantity);
                            }
                            $kiteOrder = $kite->getOrders();
                            for($i=0;$i<count($kiteOrder);$i++){
                                if($kiteOrder[$i]->status=="rejected"){
                                    $rejectedOrder = $rejectedOrder+1;
                                }else if($kiteOrder[$i]->status=="AMO CANCELLED"){
                                    $cancelOrder = $cancelOrder+1;
                                }else if($kiteOrder[$i]->status=="AMO SUBMITTED"){
                                    $excutOrder = $excutOrder+1;
                                }else {
                                    $openOrder = $openOrder+1;
                                }
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

        // return json_decode($kiteMargin)->net;
        return view('dashboard.index', compact('net', 'totalinvestment', 'availablecash', 'currentvalue', 'availableintradaypayin', 'availablelimitmargin', 'last_price', 'close_price', 'dayPl',
        'utiliseddebits', 'utilisedspan', 'GetHolding', 'rejectedOrder', 'cancelOrder', 'excutOrder', 'openOrder'));

    }

    public function getQrCodeData(){
        require_once 'GoogleAuthenticator-master/PHPGangsta/GoogleAuthenticator.php';

        $qrcode = new Zxing\QrReader('http://trade.whizzact.com/public/img/download.png');
        $text = $qrcode->text();

            echo $text;

        // $ga = new PHPGangsta_GoogleAuthenticator();
        // $secret = $ga->createSecret();
        // echo "Secret is: ".$secret."\n\n";

        // $qrCodeUrl = $ga->getQRCodeGoogleUrl('Blog', $secret);
        // echo "Google Charts URL for the QR-Code: ".$qrCodeUrl."\n\n";

        // $oneCode = $ga->getCode($secret);
        // echo "Checking Code '$oneCode' and Secret '$secret':\n";

        // $checkResult = $ga->verifyCode($secret, $oneCode, 1);    // 2 = 2*30sec clock tolerance
        // if ($checkResult) {
        //     echo 'OK';
        // } else {
        //     echo 'FAILED';
        // }

    }

    public function getAllPosition(Request $request){
        $preRMS="";$jsonData="";$GetPosition=array();$prePositions="";
        $net=0;$availablecash=0;$availableintradaypayin=0;$availablelimitmargin=0;$m2munrealized=0;$m2mrealized=0;
        $utilisedturnover=0;$utiliseddebits=0;$utilisedspan=0;
        $data=[];$kiteArray=[];
        $GetHolding = array();
        // $ta = TradingAccount::all();
        $ta = TradingAccount::where('login_id', 'D229903')->get();
        if($ta){
            foreach($ta as $allta){
                if($allta->stock_brocker=="Angel"){
                    if($allta->trading_platform=="SMART_API"){
                        $smart_api  = new \AngelBroking\SmartApi( );
                        $smart_api ->GenerateSession($allta->login_id, $allta->password);

                        $prePositions =  json_decode($smart_api ->GetPosition(), true);

                        if($prePositions['response_data']['data']!=null){

                            for($i=0;$i<count($prePositions['response_data']['data']);$i++){
                                $prePositions['response_data']['data'][$i]['accountId']=$allta->id;
                                $prePositions['response_data']['data'][$i]['loginId']=$allta->login_id;
                                $prePositions['response_data']['data'][$i]['platform']=$allta->trading_platform;
                                $prePositions['response_data']['data'][$i]['broker']=$allta->stock_brocker;
                            }

                        }

                         if(isset($prePositions['response_data']['data'])){
                            if($prePositions['response_data']['data']!=null){
                                $GetPosition[] = array('data'=>$prePositions['response_data']['data'],
                                );
                            }
                        }
                    }
                    // return $GetPosition;
                }else if($allta->stock_brocker == "Zerodha"){
                    if($allta->access_token!="" || $allta->access_token!=null){
                        try{
                            $kite = new KiteConnect(env('KITE_KEY'));
                            $kite->setAccessToken($allta->access_token);
                            $kite->getPositions();
                            for($i=0;$i<count($kiteHolding);$i++){
                                // $kiteArray[] = array(
                                //     "symbolname"=>$kiteHolding[$i]->tradingsymbol,"exchange"=>$kiteHolding[$i]->exchange,"instrumenttype"=>$kiteHolding[$i]->isin,"t1quantity"=>$kiteHolding[$i]->t1_quantity,"realisedquantity"=>$kiteHolding[$i]->realised_quantity,"quantity"=>$kiteHolding[$i]->quantity,"authorisedquantity"=>$kiteHolding[$i]->authorised_quantity,"profitandloss"=>$kiteHolding[$i]->pnl,"product"=>$kiteHolding[$i]->product,"collateralquantity"=>$kiteHolding[$i]->collateral_quantity,"collateraltype"=>$kiteHolding[$i]->collateral_type,"haircut"=>"","averageprice"=>$kiteHolding[$i]->average_price,"ltp"=>$kiteHolding[$i]->last_price,"symboltoken"=>$kiteHolding[$i]->instrument_token,

                                //     "close"=>$kiteHolding[$i]->close_price,"accountId"=>$allta->id,"loginId"=>$allta->login_id,"platform"=>$allta->trading_platform,"broker"=>$allta->stock_brocker
                                // );
                            }
                            $GetPosition[] = array('data'=>$kiteArray);
                        } catch (Handler $e) {
                            \Log::debug($e->getMessage());
                        } catch(\KiteConnect\Exception\TokenException $e){
                            \Log::debug($e->getMessage());
                        }
                    }
                }
            }
        }

        // return $prePositions ;
        return view('position.index', compact('GetPosition'));


    }

    public function holding(Request $request){
        $holding = 'whizz:holding';
        $data=[];$GetHolding = array();$kiteHolding="";$kiteArray=[];
        if(unserialize(Redis::get($holding))!=null){
            $json = json_decode(unserialize(Redis::get($holding)));
        }else{
            $ta = TradingAccount::all();
            if($ta){
                foreach($ta as $allta){
                    if($allta->stock_brocker=="Angel"){
                        if($allta->trading_platform=="SMART_API"){
                            $smart_api  = new \AngelBroking\SmartApi();
                            $smart_api ->GenerateSession($allta->login_id, $allta->password);
                            $preHoldings =  json_decode($smart_api ->GetHoldings(), true);
                            if($preHoldings['response_data']['data']!=null){
                                for($i=0;$i<count($preHoldings['response_data']['data']);$i++){
                                    $preHoldings['response_data']['data'][$i]['accountId']=$allta->id;
                                    $preHoldings['response_data']['data'][$i]['loginId']=$allta->login_id;
                                    $preHoldings['response_data']['data'][$i]['platform']=$allta->trading_platform;
                                    $preHoldings['response_data']['data'][$i]['broker']=$allta->stock_brocker;
                                }
                            }
                            if(isset($preHoldings['response_data']['data'])){
                                if($preHoldings['response_data']['data']!=null){
                                    $GetHolding[] = array('data'=>$preHoldings['response_data']['data'],
                                    );
                                }
                            }
                        }
                    }else if($allta->stock_brocker=="Zerodha"){
                        if($allta->access_token!="" || $allta->access_token!=null){
                            try{
                                $kite = new KiteConnect(env('KITE_KEY'));
                                $kite->setAccessToken($allta->access_token);
                                $kiteHolding = $kite->getHoldings();
                                for($i=0;$i<count($kiteHolding);$i++){
                                    $kiteArray[] = array(
                                        "tradingsymbol"=>$kiteHolding[$i]->tradingsymbol,"exchange"=>$kiteHolding[$i]->exchange,"isin"=>$kiteHolding[$i]->isin,"t1quantity"=>$kiteHolding[$i]->t1_quantity,"realisedquantity"=>$kiteHolding[$i]->realised_quantity,"quantity"=>$kiteHolding[$i]->quantity,"authorisedquantity"=>$kiteHolding[$i]->authorised_quantity,"profitandloss"=>$kiteHolding[$i]->pnl,"product"=>$kiteHolding[$i]->product,"collateralquantity"=>$kiteHolding[$i]->collateral_quantity,"collateraltype"=>$kiteHolding[$i]->collateral_type,"haircut"=>"","averageprice"=>$kiteHolding[$i]->average_price,"ltp"=>$kiteHolding[$i]->last_price,"symboltoken"=>$kiteHolding[$i]->tradingsymbol,"close"=>$kiteHolding[$i]->close_price,"accountId"=>$allta->id,"loginId"=>$allta->login_id,"platform"=>$allta->trading_platform,"broker"=>$allta->stock_brocker
                                    );
                                }
                                $GetHolding[] = array('data'=>$kiteArray);
                            } catch (Handler $e) {
                                \Log::debug($e->getMessage());
                            } catch(\KiteConnect\Exception\TokenException $e){
                                \Log::debug($e->getMessage());
                            }
                        }
                    }
                }
            }
            $json = $this->buildHolding($GetHolding, $holding);
        }
        // return $json;
        if(isset($_GET['search']) && $_GET['search']!=""){
            $search = $_GET['search'];
            $json = array_filter($json, function ($result) use ($search) {
                return ($result->tradingsymbol == strtoupper($search));
            });
        }
        if(isset($_GET['search']) && !isset($_GET['sortby']) && $_GET['search']!=""){
            $search = strtoupper($_GET['search']);
            $property = 'tradingsymbol';
            $json = array_filter($json, function($country) use ($property, $search) {
                return strpos($country->tradingsymbol, $_GET['search']) !== false;
                if(strpos($country->tradingsymbol, $_GET['search'])!=false){
                    return strpos($country->tradingsymbol, $_GET['search']) !== false;
                }else if(strpos($country->quantity, $_GET['search'])!=false){
                    return strpos($country->quantity, $_GET['search']) !== false;
                }else if(strpos($country->totalInvestment, $_GET['search'])!=false){
                    return strpos($country->totalInvestment, $_GET['search']) !== false;
                }
            });

        }else if(isset($_GET['search']) && isset($_GET['sortby'])  && $_GET['search']!=""  && $_GET['sortby']!=""){
            $search = strtoupper($_GET['search']);
            $symbol = 'tradingsymbol';
            // $json = array_filter($json, function ($key) use ($search) { return in_array($key, $search); }, ARRAY_FILTER_USE_KEY );
            $json = array_filter($json, function($result) use ($symbol, $search) {
                return strnatcasecmp($result->tradingsymbol, $search) !== false;
            });
            if($_GET['sortby']=="asc"){
                if($_GET['sortByColumn']=="tradingsymbol"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->tradingsymbol,$b->tradingsymbol);});
                }if($_GET['sortByColumn']=="ltp"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->ltp,$b->ltp);});
                }if($_GET['sortByColumn']=="quantity"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->quantity,$b->quantity);});
                }if($_GET['sortByColumn']=="totalInvestment"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->totalInvestment,$b->totalInvestment);});
                }if($_GET['sortByColumn']=="totalReturn"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->totalReturn,$b->totalReturn);});
                }
            }else{
                if($_GET['sortByColumn']=="tradingsymbol"){
                    usort($json,function($a,$b) {return $a->tradingsymbol > $b->tradingsymbol ? -1 : 1;});
                }if($_GET['sortByColumn']=="ltp"){
                    usort($json,function($a,$b) {return $a->ltp > $b->ltp ? -1 : 1;});
                }if($_GET['sortByColumn']=="quantity"){
                    usort($json,function($a,$b) {return $a->quantity > $b->quantity ? -1 : 1;});
                }if($_GET['sortByColumn']=="totalInvestment"){
                    usort($json,function($a,$b) {return $a->totalInvestment > $b->totalInvestment ? -1 : 1;});
                }if($_GET['sortByColumn']=="totalReturn"){
                    usort($json,function($a,$b) {return $a->totalReturn > $b->totalReturn ? -1 : 1;});
                }
            }
        }else if(!isset($_GET['search']) && isset($_GET['sortby']) && $_GET['sortby']!=""){
            if($_GET['sortby']=="asc"){
                if($_GET['sortByColumn']=="tradingsymbol"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->tradingsymbol,$b->tradingsymbol);});
                }if($_GET['sortByColumn']=="ltp"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->ltp,$b->ltp);});
                }if($_GET['sortByColumn']=="quantity"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->quantity,$b->quantity);});
                }if($_GET['sortByColumn']=="totalInvestment"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->totalInvestment,$b->totalInvestment);});
                }if($_GET['sortByColumn']=="totalReturn"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->totalReturn,$b->totalReturn);});
                }
            }else{
                if($_GET['sortByColumn']=="tradingsymbol"){
                    usort($json,function($a,$b) {return $a->tradingsymbol > $b->tradingsymbol ? -1 : 1;});
                }if($_GET['sortByColumn']=="ltp"){
                    usort($json,function($a,$b) {return $a->ltp > $b->ltp ? -1 : 1;});
                }if($_GET['sortByColumn']=="quantity"){
                    usort($json,function($a,$b) {return $a->quantity > $b->quantity ? -1 : 1;});
                }if($_GET['sortByColumn']=="totalInvestment"){
                    usort($json,function($a,$b) {return $a->totalInvestment > $b->totalInvestment ? -1 : 1;});
                }if($_GET['sortByColumn']=="totalReturn"){
                    usort($json,function($a,$b) {return $a->totalReturn > $b->totalReturn ? -1 : 1;});
                }
            }
        }else{
            usort($json,function($a,$b) {return strnatcasecmp($a->tradingsymbol,$b->tradingsymbol);});
        }
        return view('holding.index', compact('json'));
    }

    public static function buildHolding($GetHolding, $holding){
        $grouped = array();
        $tradingsymbol="";
        foreach($GetHolding as $value) {
            $qty=0;
            $totalQty=0;
            foreach($value['data'] as $object){
                if(!array_key_exists($object['tradingsymbol'], $grouped)) {
                    $newObject = new \stdClass();
                    if($object['broker']=="Angel"){
                        $instruments = Instruments::where('exchange', $object['exchange'])->where('angel_symbol', $object['tradingsymbol'])->first();
                        if($instruments){
                            $tradingsymbol = $instruments->zerodha_symbol;
                        }else{
                            $tradingsymbol = $object['tradingsymbol'];
                        }

                    }else{
                        $tradingsymbol = $object['tradingsymbol'];
                    }
                    $newObject->tradingsymbol = $tradingsymbol;
                    $newObject->ltp = $object['ltp'];
                    $newObject->exchange = $object['exchange'];
                    $newObject->quantity = $object['quantity'];//array();
                    $newObject->averageprice = $object['averageprice'];
                    $newObject->close = $object['close'];
                    $newObject->totalInvestment = number_format((float)$object['quantity']*$object['averageprice'], 2, '.', '');
                    $newObject->totalReturn = number_format((float)$object['quantity']*$object['ltp'], 2, '.', '');
                    $newObject->daysReturn = number_format((float)$object['quantity']*$object['close'], 2, '.', '');
                    $newObject->ITEMS = array();
                    $grouped[$object['tradingsymbol']] = $newObject;
                }

                $taskObject = new \stdClass();
                $taskObject->tradingsymbol = $object['tradingsymbol'];
                $taskObject->exchange = $object['exchange'];
                $taskObject->isin = $object['isin'];
                $taskObject->t1quantity = $object['t1quantity'];
                $taskObject->realisedquantity = $object['realisedquantity'];
                $taskObject->quantity = $object['quantity'];
                $taskObject->authorisedquantity = $object['authorisedquantity'];
                $taskObject->profitandloss = $object['profitandloss'];
                $taskObject->product = $object['product'];
                $taskObject->collateralquantity = $object['collateralquantity'];
                $taskObject->collateraltype = $object['collateraltype'];
                $taskObject->haircut = $object['haircut'];
                $taskObject->averageprice = number_format((float)$object['averageprice'], 2, '.', '');
                $taskObject->ltp = number_format((float)$object['ltp'], 2, '.', '');
                $taskObject->symboltoken = $object['symboltoken'];
                $taskObject->close = number_format((float)$object['close'], 2, '.', '');
                $taskObject->accountId = $object['accountId'];
                $taskObject->loginId = $object['loginId'];
                $taskObject->platform = $object['platform'];
                $taskObject->broker = $object['broker'];
                $taskObject->totalInvestment = number_format((float)$object['quantity'], 2, '.', '')*number_format((float)$object['averageprice'], 2, '.', '');
                $taskObject->totalReturn = number_format((float)$object['quantity']*$object['ltp'], 2, '.', '');
                $taskObject->daysReturn = number_format((float)$object['quantity']*$object['close'], 2, '.', '');
                $grouped[$object['tradingsymbol']]->ITEMS[] = $taskObject;

            }
        }
        $grouped = array_values($grouped);
        $jd = json_encode($grouped);
        $json = json_decode($jd, true);
        Redis::set($holding, serialize($jd));
        $json = json_decode(unserialize(Redis::get($holding)));
        Redis::expire($holding, 1000);
        return $json;
    }

    public function portfolio(Request $request){
        $net=0;$availablecash=0;$availableintradaypayin=0;$availablelimitmargin=0;$m2munrealized=0;$m2mrealized=0;
        $utilisedturnover=0;$utiliseddebits=0;$utilisedspan=0;
        $totalinvestment=0;$currentvalue=0;
        $data=[];
        $GetPrtfolio = array();
        $kiteHolding="";$kiteArray=[];
        $portfolio = 'was:portfolio';

        if(unserialize(Redis::get($portfolio))!=null){
            $json = json_decode(unserialize(Redis::get($portfolio)));
        }else{
            $ta = TradingAccount::all();
            if($ta){
                foreach($ta as $allta){
                    if($allta->stock_brocker=="Angel"){
                        if($allta->trading_platform=="SMART_API"){
                            $smart_api  = new \AngelBroking\SmartApi();
                            $smart_api ->GenerateSession($allta->login_id, $allta->password);
                            $preHoldings =  json_decode($smart_api ->GetHoldings(), true);
                            if($preHoldings['response_data']['data']!=null){
                                for($i=0;$i<count($preHoldings['response_data']['data']);$i++){
                                    $totalinvestment += number_format((float)$preHoldings['response_data']['data'][$i]['quantity']*$preHoldings['response_data']['data'][$i]['averageprice'], 2, '.', '');
                                    $currentvalue += number_format((float)$preHoldings['response_data']['data'][$i]['quantity']*$preHoldings['response_data']['data'][$i]['ltp'], 2, '.', '');
                                    $preHoldings['response_data']['data'][$i]['accountId']=$allta->id;
                                    $preHoldings['response_data']['data'][$i]['loginId']=$allta->login_id;
                                    $preHoldings['response_data']['data'][$i]['platform']=$allta->trading_platform;
                                    $preHoldings['response_data']['data'][$i]['broker']=$allta->stock_brocker;
                                    $preHoldings['response_data']['data'][$i]['password']=$allta->password;
                                }
                            }
                            if(isset($preHoldings['response_data']['data'])){
                                if($preHoldings['response_data']['data']!=null){
                                    $GetHolding[] = array('data'=>$preHoldings['response_data']['data'],
                                    );
                                }
                            }
                        }
                    }else if($allta->stock_brocker=="Zerodha"){
                        if($allta->access_token!="" || $allta->access_token!=null){
                            try{
                                $kite = new KiteConnect(env('KITE_KEY'));
                                $kite->setAccessToken($allta->access_token);
                                $kiteHolding = $kite->getHoldings();
                                for($i=0;$i<count($kiteHolding);$i++){
                                    $kiteArray[] = array(
                                        "tradingsymbol"=>$kiteHolding[$i]->tradingsymbol,"exchange"=>$kiteHolding[$i]->exchange,"isin"=>$kiteHolding[$i]->isin,"t1quantity"=>$kiteHolding[$i]->t1_quantity,"realisedquantity"=>$kiteHolding[$i]->realised_quantity,"quantity"=>$kiteHolding[$i]->quantity,"authorisedquantity"=>$kiteHolding[$i]->authorised_quantity,"profitandloss"=>$kiteHolding[$i]->pnl,"product"=>$kiteHolding[$i]->product,"collateralquantity"=>$kiteHolding[$i]->collateral_quantity,"collateraltype"=>$kiteHolding[$i]->collateral_type,"haircut"=>"","averageprice"=>$kiteHolding[$i]->average_price,"ltp"=>$kiteHolding[$i]->last_price,"symboltoken"=>$kiteHolding[$i]->tradingsymbol,"close"=>$kiteHolding[$i]->close_price,"accountId"=>$allta->id,"loginId"=>$allta->login_id,"platform"=>$allta->trading_platform,"broker"=>$allta->stock_brocker, "password"=>$allta->password
                                    );
                                }
                                $GetHolding[] = array('data'=>$kiteArray);
                            } catch (Handler $e) {
                                \Log::debug($e->getMessage());
                            } catch(\KiteConnect\Exception\TokenException $e){
                                \Log::debug($e->getMessage());
                            }
                        }
                    }
                }
            }
            $json = $this->buildPortfolio($GetHolding, $portfolio);
        }
        if(isset($_GET['account']) && $_GET['account']!="" && $_GET['account']!=null){
            $search = strtoupper($_GET['account']);
            $searchByLoginId = 'loginId';
            $json = array_filter($json, function($result) use ($searchByLoginId, $search) {
                return strpos($result->loginId, $search) !== false;
            });
        }

        if(isset($_GET['search']) && !isset($_GET['sortby']) && $_GET['search']!=""){
            $search = strtoupper($_GET['search']);
            $searchByLoginId = 'loginId';
            $json = array_filter($json, function($result) use ($searchByLoginId, $search) {
                return strpos($result->loginId, $search) !== false;
            });
        }else if(isset($_GET['search']) && isset($_GET['sortby'])  && $_GET['search']!=""  && $_GET['sortby']!=""){
            $search = strtoupper($_GET['search']);
            $searchByLoginId = 'loginId';
            $json = array_filter($json, function($result) use ($searchByLoginId, $search) {
                return strpos($result->loginId, $search) !== false;
            });
            if($_GET['sortby']=="asc"){
                if($_GET['sortByColumn']=="broker"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->broker, $b->broker);});
                }if($_GET['sortByColumn']=="quantity"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->quantity, $b->quantity);});
                }if($_GET['sortByColumn']=="loginId"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->loginId, $b->loginId);});
                }if($_GET['sortByColumn']=="totalReturn"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->totalReturn, $b->totalReturn);});
                }if($_GET['sortByColumn']=="totalInvestment"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->totalInvestment, $b->totalInvestment);});
                }

            }else{
                if($_GET['sortByColumn']=="broker"){
                    usort($json,function($a,$b) {return $a->broker > $b->broker ? -1 : 1;});
                }if($_GET['sortByColumn']=="quantity"){
                    usort($json,function($a,$b) {return $a->quantity > $b->quantity ? -1 : 1;});
                }if($_GET['sortByColumn']=="loginId"){
                    usort($json,function($a,$b) {return $a->loginId > $b->loginId ? -1 : 1;});
                }if($_GET['sortByColumn']=="totalReturn"){
                    usort($json,function($a,$b) {return $a->totalReturn > $b->totalReturn ? -1 : 1;});
                }if($_GET['sortByColumn']=="totalInvestment"){
                    usort($json,function($a,$b) {return $a->totalInvestment > $b->totalInvestment ? -1 : 1;});
                }

            }
        }else if(!isset($_GET['search']) && isset($_GET['sortby']) && $_GET['sortby']!=""){
            if($_GET['sortby']=="asc"){
                if($_GET['sortByColumn']=="broker"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->broker, $b->broker);});
                }if($_GET['sortByColumn']=="quantity"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->quantity, $b->quantity);});
                }if($_GET['sortByColumn']=="loginId"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->loginId, $b->loginId);});
                }if($_GET['sortByColumn']=="totalReturn"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->totalReturn, $b->totalReturn);});
                }if($_GET['sortByColumn']=="totalInvestment"){
                    usort($json,function($a,$b) {return strnatcasecmp($a->totalInvestment, $b->totalInvestment);});
                }
            }else{
                if($_GET['sortByColumn']=="broker"){
                    usort($json,function($a,$b) {return $a->broker > $b->broker ? -1 : 1;});
                }if($_GET['sortByColumn']=="quantity"){
                    usort($json,function($a,$b) {return $a->quantity > $b->quantity ? -1 : 1;});
                }if($_GET['sortByColumn']=="loginId"){
                    usort($json,function($a,$b) {return $a->loginId > $b->loginId ? -1 : 1;});
                }if($_GET['sortByColumn']=="totalReturn"){
                    usort($json,function($a,$b) {return $a->totalReturn > $b->totalReturn ? -1 : 1;});
                }if($_GET['sortByColumn']=="totalInvestment"){
                    usort($json,function($a,$b) {return $a->totalInvestment > $b->totalInvestment ? -1 : 1;});
                }
            }
        }else{
            usort($json,function($a,$b) {return strnatcasecmp($a->broker, $b->broker);});
        }

        return view('portfolio.index', compact('json'));
    }

    public static function buildPortfolio($GetHolding, $portfolio){
        $grouped = array();
        $tradingsymbol="";
        $totalinvestment=0;$currentvalue=0;
        foreach($GetHolding as $value) {
            foreach($value['data'] as $object){
                if(!array_key_exists($object['loginId'], $grouped)) {

                    $newObject = new \stdClass();
                    $newObject->broker = $object['broker'];
                    $newObject->loginId = $object['loginId'];
                    $newObject->password = $object['password'];
                    $newObject->totalInvestment = number_format((float)$object['quantity'], 2, '.', '')*number_format((float)$object['averageprice'], 2, '.', '');
                    $newObject->totalReturn = number_format((float)$object['quantity']*$object['ltp'], 2, '.', '');
                    $newObject->close = $object['close'];
                    $newObject->quantity = $object['quantity'];
                    $newObject->ltp = $object['ltp'];
                    $newObject->averageprice = $object['averageprice'];
                    $newObject->ITEMS = array();
                    $grouped[$object['loginId']] = $newObject;
                }
            $taskObject = new \stdClass();
            $taskObject->totalInvestment = number_format((float)$object['quantity'], 2, '.', '')*number_format((float)$object['averageprice'], 2, '.', '');
            if($object['broker']=="Angel"){
                $instruments = Instruments::where('exchange', $object['exchange'])->where('angel_symbol', $object['tradingsymbol'])->first();
                if($instruments){
                    $tradingsymbol = $instruments->zerodha_symbol;
                }else{
                    $tradingsymbol = $object['tradingsymbol'];
                }

            }else{
                $tradingsymbol = $object['tradingsymbol'];
            }
            $taskObject->tradingsymbol = $tradingsymbol;
            // $taskObject->tradingsymbol = $object['tradingsymbol'];
            $taskObject->exchange = $object['exchange'];
            $taskObject->isin = $object['isin'];
            $taskObject->t1quantity = $object['t1quantity'];
            $taskObject->realisedquantity = $object['realisedquantity'];
            $taskObject->quantity = $object['quantity'];
            $taskObject->authorisedquantity = $object['authorisedquantity'];
            $taskObject->profitandloss = $object['profitandloss'];
            $taskObject->product = $object['product'];
            $taskObject->collateralquantity = $object['collateralquantity'];
            $taskObject->collateraltype = $object['collateraltype'];
            $taskObject->haircut = $object['haircut'];
            $taskObject->averageprice = number_format((float)$object['averageprice'], 2, '.', '');
            $taskObject->ltp = number_format((float)$object['ltp'], 2, '.', '');
            $taskObject->symboltoken = $object['symboltoken'];
            $taskObject->close = number_format((float)$object['close'], 2, '.', '');
            $taskObject->accountId = $object['accountId'];
            $taskObject->loginId = $object['loginId'];
            $taskObject->platform = $object['platform'];
            $taskObject->broker = $object['broker'];
            $taskObject->totalReturn = number_format((float)$object['quantity']*$object['ltp'], 2, '.', '');
            $taskObject->daysReturn = number_format((float)$object['ltp']-$object['close'], 2, '.', '');

            $grouped[$object['loginId']]->ITEMS[] = $taskObject;

            }
        }

        $grouped = array_values($grouped);
        $jd = json_encode($grouped);
        $json = json_decode($jd, true);
        Redis::set($portfolio, serialize($jd));
        Redis::expire($portfolio, 1800);
        $json = json_decode(unserialize(Redis::get($portfolio)));
        return $json;
    }

    function portfolioByAjax(Request $request){
        $net=0;$availablecash=0;$availableintradaypayin=0;$availablelimitmargin=0;$m2munrealized=0;$m2mrealized=0;
        $utilisedturnover=0;$utiliseddebits=0;$utilisedspan=0;
        $totalinvestment=0;$currentvalue=0;
        $data=[];
        $GetPrtfolio = array();
        $kiteHolding="";$kiteArray=[];

        $ta = TradingAccount::all();
        if($ta){
            foreach($ta as $allta){
                // echo $allta->login_id;
                if($allta->stock_brocker=="Angel"){
                    if($allta->trading_platform=="SMART_API"){
                        $smart_api  = new \AngelBroking\SmartApi(
                            // OPTIONAL
                            //  "YOUR_ACCESS_TOKEN",
                            // "YOUR_REFRESH_TOKEN"
                        );
                        $smart_api ->GenerateSession($allta->login_id, $allta->password);
                        $preHoldings =  json_decode($smart_api ->GetHoldings(), true);

                        if($preHoldings['response_data']['data']!=null){

                            for($i=0;$i<count($preHoldings['response_data']['data']);$i++){

                                $totalinvestment += number_format((float)$preHoldings['response_data']['data'][$i]['quantity']*$preHoldings['response_data']['data'][$i]['averageprice'], 2, '.', '');
                                $currentvalue += number_format((float)$preHoldings['response_data']['data'][$i]['quantity']*$preHoldings['response_data']['data'][$i]['ltp'], 2, '.', '');

                                $preHoldings['response_data']['data'][$i]['accountId']=$allta->id;
                                $preHoldings['response_data']['data'][$i]['loginId']=$allta->login_id;
                                $preHoldings['response_data']['data'][$i]['platform']=$allta->trading_platform;
                                $preHoldings['response_data']['data'][$i]['broker']=$allta->stock_brocker;
                                $preHoldings['response_data']['data'][$i]['password']=$allta->password;

                            }

                        }

                        if(isset($preHoldings['response_data']['data'])){
                            if($preHoldings['response_data']['data']!=null){
                                $GetHolding[] = array('data'=>$preHoldings['response_data']['data'],
                                );
                            }
                        }
                    }
                }else if($allta->stock_brocker=="Zerodha"){
                    if($allta->access_token!="" || $allta->access_token!=null){
                        try{
                            $kite = new KiteConnect(env('KITE_KEY'));
                            $kite->setAccessToken($allta->access_token);
                            $kiteHolding = $kite->getHoldings();
                            for($i=0;$i<count($kiteHolding);$i++){
                                $kiteArray[] = array(
                                    "tradingsymbol"=>$kiteHolding[$i]->tradingsymbol,"exchange"=>$kiteHolding[$i]->exchange,"isin"=>$kiteHolding[$i]->isin,"t1quantity"=>$kiteHolding[$i]->t1_quantity,"realisedquantity"=>$kiteHolding[$i]->realised_quantity,"quantity"=>$kiteHolding[$i]->quantity,"authorisedquantity"=>$kiteHolding[$i]->authorised_quantity,"profitandloss"=>$kiteHolding[$i]->pnl,"product"=>$kiteHolding[$i]->product,"collateralquantity"=>$kiteHolding[$i]->collateral_quantity,"collateraltype"=>$kiteHolding[$i]->collateral_type,"haircut"=>"","averageprice"=>$kiteHolding[$i]->average_price,"ltp"=>$kiteHolding[$i]->last_price,"symboltoken"=>$kiteHolding[$i]->tradingsymbol,"close"=>$kiteHolding[$i]->close_price,"accountId"=>$allta->id,"loginId"=>$allta->login_id,"platform"=>$allta->trading_platform,"broker"=>$allta->stock_brocker, "password"=>$allta->password
                                );
                            }
                            $GetHolding[] = array('data'=>$kiteArray);
                        } catch (Handler $e) {
                            \Log::debug($e->getMessage());
                        } catch(\KiteConnect\Exception\TokenException $e){
                            \Log::debug($e->getMessage());
                        }
                    }
                }
            }
        }

        // return $GetHolding;

        $grouped = array();
        foreach($GetHolding as $value) {
            foreach($value['data'] as $object){
                if(!array_key_exists($object['loginId'], $grouped)) {
                    $totalinvestment += number_format((float)$object['quantity']*$object['averageprice'], 2, '.', '');
                    $currentvalue += number_format((float)$object['quantity']*$object['ltp'], 2, '.', '');
                    $newObject = new \stdClass();
                    $newObject->broker = $object['broker'];
                    $newObject->loginId = $object['loginId'];
                    $newObject->password = $object['password'];
                    $newObject->totalInvestment = number_format((float)$object['quantity']*$object['averageprice'], 2, '.', '');
                    $newObject->totalReturn = number_format((float)$object['quantity']*$object['ltp'], 2, '.', '');
                    $newObject->close = $object['close'];
                    $newObject->quantity = $object['quantity'];
                    $newObject->ltp = $object['ltp'];
                    $newObject->averageprice = $object['averageprice'];
                    $newObject->ITEMS = array();
                    $grouped[$object['loginId']] = $newObject;
                }

            $taskObject = new \stdClass();
            $taskObject->tradingsymbol = $object['tradingsymbol'];
            $taskObject->exchange = $object['exchange'];
            $taskObject->isin = $object['isin'];
            $taskObject->t1quantity = $object['t1quantity'];
            $taskObject->realisedquantity = $object['realisedquantity'];
            $taskObject->quantity = $object['quantity'];
            $taskObject->authorisedquantity = $object['authorisedquantity'];
            $taskObject->profitandloss = $object['profitandloss'];
            $taskObject->product = $object['product'];
            $taskObject->collateralquantity = $object['collateralquantity'];
            $taskObject->collateraltype = $object['collateraltype'];
            $taskObject->haircut = $object['haircut'];
            $taskObject->averageprice = $object['averageprice'];
            $taskObject->ltp = $object['ltp'];
            $taskObject->symboltoken = $object['symboltoken'];
            $taskObject->close = $object['close'];
            $taskObject->accountId = $object['accountId'];
            $taskObject->loginId = $object['loginId'];
            $taskObject->platform = $object['platform'];
            $taskObject->broker = $object['broker'];
            $taskObject->totalReturn = number_format((float)$object['quantity']*$object['close'], 2, '.', '');

            $grouped[$object['loginId']]->ITEMS[] = $taskObject;

            }
        }

        $grouped = array_values($grouped);
        $jd = json_encode($grouped);
        $json = json_decode($jd, true);

        return view('portfolio.ajax.index', compact('json'));
    }

    public function getLTP(Request $request){
        $symbol = $request->symbol;
        $exchange = $request->exchange;
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


    public function groupBy($key, $data) {
        $result = array();

        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
        }

        return $result;
    }



}


