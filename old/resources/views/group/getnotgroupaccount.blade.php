<?php
$cd = DB::select('select * from trading_account');
if($cd){
    foreach($cd as $allcd){
        echo '<option value="'.$allcd->id.'">['.$allcd->login_id.' : '.$allcd->login_id.']</option>';
    }
}
?>