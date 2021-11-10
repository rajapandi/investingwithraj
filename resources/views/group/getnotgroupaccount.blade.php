<?php
$smart_api  = new \AngelBroking\SmartApi(); 
$fund=0;
$cd = DB::select('select * from trading_account');
if($cd){
    foreach($cd as $allcd){
        if($allcd->stock_brocker == "Angel"){
            $smart_api ->GenerateSession($allcd->login_id, $allcd->password);    
            $jsonData = $smart_api->GetRMS();
            $preRMS = json_decode($jsonData, true);
            ?>
            <li><input type="checkbox" class="selecteditem" id="vehicle1" name="vehicle1" value="{{$allcd->id}}">
                <label for="vehicle1"><?php echo '['.$allcd->login_id.' : '.$allcd->login_id.'] (&#8377; '.number_format((float)$preRMS['response_data']['data']['availablecash'], 2, '.', '').')' ?></label>
            </li>
            <?php
        }else if($allcd->stock_brocker == "Zerodha"){
            try{
                if($allcd->access_token != null || $allcd->access_token!=""){
                    $kite = new \KiteConnect\KiteConnect(env('KITE_KEY'));
                    $kite->setAccessToken($allcd->access_token);
                    $kiteMargin = json_encode($kite->getMargins("equity"));
                    ?>
                    <li><input type="checkbox" class="selecteditem" id="vehicle1" name="vehicle1" value="{{$allcd->id}}">
                        <label for="vehicle1"><?php echo '['.$allcd->login_id.' : '.$allcd->login_id.'] (&#8377; '.number_format((float)json_decode($kiteMargin)->net, 2, '.', '') ?></label>
                    </li>
                    <?php
                }
            } catch (Handler $e) {
                \Log::debug($e->getMessage());
            } catch(\KiteConnect\Exception\TokenException $e){
                \Log::debug($e->getMessage());
            } 
        }
        ?>
        
        <?php
    }
}
?>