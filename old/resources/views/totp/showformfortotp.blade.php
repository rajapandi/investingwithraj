<?php
$loginId = $_GET['loginId'];
$cd = App\Models\TradingAccount::where('login_id', $loginId)->first();
?>
<div class="modal-content" id="model_content">
    <div class="modal-header">
      <h4 class="modal-title">Enter Key for {{$loginId}}</h4>                                                             
      <button type="button" class="close" data-dismiss="modal" onclick="closeModel()">×</button> 
    </div> 
    <div class="modal-body">
      <form action="">
            <div class="form-group">
                <label for="kitetotp">TOTP KEY</label>
                <input type="email" class="form-control" id="kitetotp" aria-describedby="emailHelp" placeholder="Enter the key">
            </div><br>
            <div class="form-group">
                <input type="button" onclick="generateTOTP('{{$loginId}}')" class="btn btn-success byn-sm" aria-describedby="emailHelp" value="Generate TOTP">
            </div>
      </form>
    </div>   
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeModel()">Close</button>                               
    </div>
</div>