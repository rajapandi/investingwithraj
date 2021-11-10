<?php
$cd = DB::select('select * from groups');
if($cd){
    foreach($cd as $allcd){
        echo '<li><input type="checkbox" class="selecteditem" id="vehicle1" name="vehicle1" value="'.$allcd->id.'">
                <label for="vehicle1">'.$allcd->name.'</label></li>';
    }
}
?>