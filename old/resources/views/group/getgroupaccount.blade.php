<?php
$cd = DB::select('select * from groups');
if($cd){
    foreach($cd as $allcd){
        echo '<option value="'.$allcd->id.'">'.$allcd->name.'</option>';
    }
}
?>