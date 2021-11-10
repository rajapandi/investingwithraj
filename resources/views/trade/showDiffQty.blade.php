<?php
$placeQuantity = $_GET['qty'];
$group = $_GET['group'];
?>
<table style="width:80%;" class="table-bordered">
    <tr>
        <th>Add</th>
        <th>Account</th>
        <th>Qty</th>
    </tr>
    <?php
    if($group!=null){
        $gd = DB::select('select * from groups');
        if($gd){
            foreach($gd as $allgd){
            
                ?>
                <tr>
                    <td><input type="checkbox" name="checkBoxLogin[]" value="{{$allgd->id}}"></td>
                    <td>{{$allgd->name}}</td>
                    <td><input type="text" value="{{$placeQuantity}}" name="diffQty{{$allgd->id}}"></td>
                </tr>
                <?php
            }
        }
    }else{
        $ta = App\Models\TradingAccount::all();
        if($ta){
            foreach($ta as $allta){
                $smart_api  = new \AngelBroking\SmartApi(
                                // OPTIONAL
                    //  "YOUR_ACCESS_TOKEN",
                    // "YOUR_REFRESH_TOKEN"
                );
                $smart_api ->GenerateSession($allta->login_id, $allta->password);
                $jsonData = $smart_api ->GetRMS();
                $preRMS = json_decode($jsonData, true);
                ?>
                <tr>
                    <td><input type="checkbox" name="checkBoxLogin[]" value="{{$allta->login_id}}"></td>
                    <td>{{$allta->login_id}} - <i class="fa fa-inr"></i> {{number_format((float)$preRMS['response_data']['data']['availablecash'], 2, '.', '')}}</td>
                    <td><input type="text" value="{{$placeQuantity}}" name="diffQty{{$allta->login_id}}"></td>
                </tr>
                <?php
            }
        }
    }
    ?>
</table>