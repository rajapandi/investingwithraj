<?php
$br = DB::select('select * from brocker where name like "%'.$_GET['str'].'"');
if($br){
    foreach($br as $allbr){
        ?>
        <option value="{{$allbr->platform}}">{{$allbr->platform}}</option>
        <?php
    }
}
?>