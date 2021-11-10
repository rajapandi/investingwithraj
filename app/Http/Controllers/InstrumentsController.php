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
use Illuminate\Support\Facades\Redis;
use Carbon;
use File;
use AngelBroking\SmartApi;
use KiteConnect\KiteConnect;
use App\Models\Instruments;
use App\Models\TradingAccount;


class InstrumentsController extends Controller
{

    public function getLtp(Request $request){
        $symbol = $request->symbol;
        $valuestr = "";
        $seachKey = 'was:symbol';
        if(unserialize(Redis::get($seachKey))!=null){
            $jsonitem = json_decode(unserialize(Redis::get($seachKey)));
        }else{
            $json = file_get_contents("https://margincalculator.angelbroking.com/OpenAPI_File/files/OpenAPIScripMaster.json");
            Redis::set($seachKey, serialize($json));
            $jsonitem = json_decode(unserialize(Redis::get($seachKey)));
        }
        foreach ($jsonitem as $symbols) {
            if(preg_match("/$symbol/i", $symbols->symbol, $matches, PREG_OFFSET_CAPTURE)==1){
                $valuestr = $symbols->symbol;
                $exchange = $symbols->exch_seg;
                $symboltoken = $symbols->token;
            }
        }
        $ta = TradingAccount::where('login_id', 'D229903')->first();
        if($ta){
            $smart_api  = new \AngelBroking\SmartApi();
            $smart_api ->GenerateSession($ta->login_id, $ta->password);
            $data = array("exchange"=>$exchange,"tradingsymbol"=>$valuestr, "symboltoken"=>$symboltoken);
            $preHoldings =  json_decode($smart_api->GetLtpData($data));
            return $preHoldings->response_data->data->ltp;
        }
    }

    public function storeInstruments(Request $request){
        $rowData = json_decode(file_get_contents('C:\xampp\htdocs\Trade Website\instrument.json'), false);
        foreach ($rowData as $key => $value) {
            if(Instruments::where('name', $value->name)->where('exchange', $value->exchange)->where('zerodha_token', $value->instrument_token)->first()){

            }else{
                $inst = Instruments::where('name', $value->name)->where('exchange', $value->exchange)->get();
                if($inst){
                    Instruments::where('name', $value->name)->where('exchange', $value->exchange)->update(array(
                        "zerodha_symbol"=>$value->tradingsymbol,
                        'zerodha_token'=>$value->instrument_token,
                        "zerodha_instrumenttype"=>$value->instrument_type
                    ));
                }else{
                    $it = new Instruments();
                    $it->name = $value->name;
                    $it->exchange = $value->exchange;
                    $it->zerodha_symbol = $value->tradingsymbol;
                    $it->zerodha_token = $value->instrument_token;
                    $it->zerodha_instrumenttype = $value->instrument_type;
                    $it->save();
                }
            }
        }
    }

    public function getInstrument(Request $request){
        $seachKey = 'was:symbol';
        if(unserialize(Redis::get($seachKey))!=null){
            $jsonitem = json_decode(unserialize(Redis::get($seachKey)));
        }else{
            Redis::set($seachKey, serialize($json));
            $jsonitem = json_decode(unserialize(Redis::get($seachKey)));
        }
        foreach ($jsonitem as $symbol) {
            if(preg_match("/$str/i", $symbol->name, $matches, PREG_OFFSET_CAPTURE)==1){
                $valuestr = $symbol->name;
                $exchange = $symbol->exch_seg;
                echo '<li onclick="getAddDataOnSerachBox(\''.$valuestr.'\')"  style="cursor:pointer;">'.$symbol->symbol.' - '.$symbol->exch_seg.'</li>';
            }
        }

    }

}
