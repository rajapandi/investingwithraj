<?php
$placeQuantity = $_GET['qty'];
$account = $_GET['account'];
?>
<table style="width:60%;" class="table-bordered">
    <tr>
        <th>Add</th>
        <th>Account</th>
        <th>Qty</th>
    </tr>
    <?php
    $fund=0;
    foreach ($account as $key => $value) {
        # code...
        $allta = App\Models\TradingAccount::where('login_id', $value)->first();
        if($allta){
            if($allta->stock_brocker == "Angel"){
                $smart_api  = new \AngelBroking\SmartApi();
                $smart_api ->GenerateSession($allta->login_id, $allta->password);
                $jsonData = $smart_api ->GetRMS();
                $preRMS = json_decode($jsonData, true);
                $fund = number_format((float)$preRMS['response_data']['data']['availablecash'], 2, '.', '');
            }else if($allta->stock_brocker == "Zerodha"){
                $kite = new \KiteConnect\KiteConnect(env('KITE_KEY'));
                $kite->setAccessToken($allta->access_token);
                
                $kiteMargin = json_encode($kite->getMargins("equity"));
                $fund =  number_format((float)json_decode($kiteMargin)->net, 2, '.', '');
            }
            ?>
            <tr>
                <td><input type="checkbox" name="checkBoxLogin[]" value="{{$allta->login_id}}"></td>
                <td>{{$allta->login_id}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-inr"></i> {{$fund}}</td>
                <td><input type="text" class="form-control" value="{{$placeQuantity}}" name="diffQty{{$allta->login_id}}"></td>
            </tr>
            <?php
        }
    }
    ?>
</table>