<option value="all">All Trade Account</option>
<?php
foreach($ta as $allta){
    echo '<option value="'.$allta->id.'">'.$allta->login_id.'</option>';
}
?>