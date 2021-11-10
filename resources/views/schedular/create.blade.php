@extends('layout.app')

@section('content')
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}
.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}


.search-field, .term-list {
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
}

.search-field {
  display: block;
  width: 100%;
  margin: 1em auto 0;
  /*padding: 0.5em 10px;*/
  border: 1px solid #999;
  font-size: 100%;
  font-family: "Arvo", "Helvetica Neue", Helvetica, arial, sans-serif;
  font-weight: 400;
  color: #3e8ce0;
}

.term-list {
    position:absolute;
  list-style: none inside;
  width: 30%;
  height:350px;
  margin: 0 auto 2em;
  padding: 5px 10px 0;
  text-align: left;
  color: #777;
  background: #fff;
  border: 1px solid;
  font-family: "Arvo", "Helvetica Neue", Helvetica, arial, sans-serif;
  font-weight: 400;
  overflow-y:scroll;
  z-index: 99999;
}
.term-list li {
  padding: 0.5em 0;
  border-bottom: 1px solid #eee;
}
.term-list strong {
  color: #444;
  font-weight: 700;
}

.hidden {
  display: none;
}

</style>
    <div class="page-holder bg-gray-100">
        <div class="container-fluid px-lg-4 px-xl-5">
            <?php
            $smart_api  = new \AngelBroking\SmartApi(
                                 // OPTIONAL
                                 //  "YOUR_ACCESS_TOKEN",
                                 // "YOUR_REFRESH_TOKEN"
                             ); 
          $gs = App\Models\GeneralSetting::where('id', 1)->first();
           
           ?>
          <section class="mb-5">
            <div class="card">
              <div class="card-body">
                  <div class="row">
                      <div class="col-md-12">
                          <span style="color:red;">{{Session::get('msg')}}</span>
                        <form action="/trade/create" method="post"> 
                            @csrf
                            <table style="width:100%;">
                                <tr colspan="5">
                                    <td>
                                        <input class="form-check-input" type="radio" name="transactiontype" id="transactiontype1" value="BUY"  @if($gs->trade_type=="BUY") checked @endif>
                                          <label class="form-check-label" for="transactiontype1">BUY</label>
                                    </td>
                                    <td><input class="form-check-input" type="radio" name="transactiontype" id="transactiontype2" value="SELL" @if($gs->trade_type=="SELL") checked @endif>
                                          <label class="form-check-label" for="transactiontype2">SELL</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
        								<div class="radio radio-inline">
        									<label data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Regular Order"> 
        									<input type="radio" id="varietyRegular" name="variety" checked="checked" value="NORMAL" @if($gs->variety=="NORMAL") checked @endif> NORMAL
        									</label>
        								</div>
                                    </td>
                                    <td><div class="radio radio-inline">
        									<label data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Bracket Order">
        									     <input type="radio" id="varietyBO" name="variety" value="STOPLOSS" @if($gs->variety=="STOPLOSS") checked @endif> STOPLOSS
        									</label>
        								</div>
        							</td>
                                    <td><div class="radio radio-inline">
        									<label data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Cover Order"> 
        									<input type="radio" id="varietyCO" name="variety" value="AMO" @if($gs->variety=="AMO") checked @endif> AMO
        									</label>
								        </div>
								    </td>
                                    <td><div class="radio radio-inline">
        									<label data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Cover Order"> 
        									<input type="radio" id="varietyCO" name="variety" value="ROBO" @if($gs->variety=="ROBO") checked @endif> ROBO
        									</label>
								        </div>
								    </td>
                                    <td>
                                        <select name="exchange" id="placeExchange" class="form-control">
                                    		<option value="NSE" selected="">NSE</option>
                                    		<option value="BSE">BSE</option>
                                    		<option value="MCX">MCX</option>
                                    	</select>
                                        
								    </td>
                                    <td>
                                        <input type="text" class="form-control"  id="searchBox" name="tradingsymbol" onkeyup="getSearch(this.value)" autocomplete="off" required>
                                        <ul id="searchResults" class="term-list hidden"></ul>
								    </td>
                                </tr>
                                </table><br>
                            <table style="width:100%;">
                                <tr>
                                    <td>
                                        <label> <input type="radio" name="productType" id="productTypeIntraday" @if($gs->product_type=="INTRADAY") checked @endif value="INTRADAY">INTRADAY</label>
                                    </td>
                                    <td>
                                        <label> <input type="radio" name="productType" id="productTypeDelivery" value="DELIVERY" @if($gs->product_type=="DELIVERY") checked @endif> DELIVERY</label>
                                    </td>
                                    <td>
                                        <label> <input type="radio" name="productType" id="productTypeDelivery" value="MARGIN" @if($gs->product_type=="MARGIN") checked @endif> MARGIN</label>
                                    </td>
                                    <td>
                                        <label> <input type="radio" name="productType" id="productTypeDelivery" value="BO" @if($gs->product_type=="BO") checked @endif> BO</label>
                                    </td>
                                    <td>
                                        <label> <input type="radio" name="productType" id="productTypeNormal" value="CARRYFORWARD" @if($gs->product_type=="CARRYFORWARD") checked @endif> CARRYFORWARD</label>&nbsp;&nbsp;
                                    </td>
                                    
                                    <td>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label> <input type="radio" name="orderType" id="orderTypeLimit" checked="checked" value="LIMIT">LIMIT</label>
                                    </td>
                                    <td>
                                        <label> <input type="radio" name="orderType" value="MARKET"> MARKET</label>
                                    </td>
                                    <td>
                                        <label> <input type="radio" name="orderType" id="orderTypeStopLoss" value="STOP_LOSS"> STOP_LOSS</label>
                                    </td>
                                    <td>
                                        <label> <input type="radio" name="orderType" id="orderTypeSLMarket" value="SL_MARKET"> SL_MARKET</label>
                                    </td>
                                </tr>
                            </table><br>
                            <table style="width:100%;">
                                <tr>
                                    <td>
                                        <label class="j-label float-left" for="placeQuantity">Qty</label> 
    									<div class="j-input">
    										<input type="text" class="form-control qty" name="quantity" id="placeQuantity" value="{{$gs->quantity}}" onkeyup="checkQtyAndMargin(this.value)">
    									</div>
                                    </td>
                                    <td>
                                        <label class="j-label" for="placePrice">Price</label>
									    <input type="text" class="form-control price" name="price" id="placePrice" value="0">
                                    </td>
                                    <td>
                                        <label class="j-label" for="placeTriggerPrice">Trig. Price</label> <input type="text" class="form-control price" name="triggerPrice" id="placeTriggerPrice" value="0"  disabled="">
                                    </td>
                                    <td>
                                        <label class="j-label" for="placeDisclosedQty">Disclosed Qty.</label> <input type="text" class="form-control qty" name="disclosedQty" id="placeDisclosedQty" value="0">
                                    </td>
                                </tr>
                            </table><br>
                            <table style="width:100%;">
                                <tr>
                                    <td>
                                        <label class="j-label" for="placeTarget">Target</label>
									    <input type="text" class="form-control price" name="target" id="placeTarget" value="0" disabled="">
                                    </td>
                                    <td>
                                        <label class="j-label" for="placeStoploss">Stoploss</label> <input type="text" class="form-control price" name="stoploss" id="placeStoploss" value="0" disabled="">
                                    </td>
                                    <td>
                                        <label class="j-label" for="placeTrailingStoploss">Trail. Stoploss</label> <input type="text" class="form-control price" name="trailingStoploss" id="placeTrailingStoploss" value="0" disabled="">
                                    </td>
                                    <td>
                                        <label> <input type="radio" name="validity" checked="checked" value="DAY"> DAY</label>
                                    </td>
                                    <td>
                                        <label> <input type="radio" name="validity" value="IOC"> IOC</label>
                                    </td>
                                </tr>
                            </table><br>
                          
                            <table style="width:100%;">
                                <tr>
                                    <td>
                                        <div style="height: 150px;overflow-y: scroll;">
                                            <input type="checkbox" id="selectAll" name="selectAll" value="">
                                                <label for="selectAll">Select All</label>
                                            <ul>
                                                <?php
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
                                            </ul>
                                        </div>

                                        <span style="color:red;">{{Session::get('accountmsg')}}</span>
                                    </td>
                                    <td>Group Accounts&nbsp;&nbsp;&nbsp;<label class="switch"><input type="checkbox" name="group_account" id="group_account" onclick="setGroupAccount()"><span class="slider round"></span></label></td>
                                    <td>Different Qty.&nbsp;&nbsp;&nbsp;<label class="switch"><input type="checkbox" name="diff_account" id="diff_account" onclick="setDiffQty()"> <span class="slider round"></span></label></td>
                                </tr>
                            </table>
                            <div  id="tableDiffAccount" style="display:none;"></div>
                            
                          <div class="mb-3">       
                          <input type="hidden" id="group_active" name="group_active">
                          <input type="hidden" id="diff_qty" name="diff_qty">
                            <button class="btn btn-primary" type="submit" style="float:right;" id="saveButon">BUY</button>
                          </div>
                        </form>
                    </div>
                </div>
              </div>
            </div>
          </section>
        </div>
        
      </div>
    </div>

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-typeahead/2.11.0/jquery.typeahead.min.js" integrity="sha512-Rc24PGD2NTEGNYG/EMB+jcFpAltU9svgPcG/73l1/5M6is6gu3Vo1uVqyaNWf/sXfKyI0l240iwX9wpm6HE/Tg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/js/typeahead.min.js"></script>
<script type="text/javascript">


function setDiffQty(){
    var checkBox = document.getElementById("diff_account");  
    var group_active = $('#group_active').val();  
    var placeQuantity = $('#placeQuantity').val();
    
    if(checkBox.checked == true){
        
        $('#diff_qty').val("diff_qty");
        $('#accounts').css('display', 'none');
        $('#tableDiffAccount').css('display', 'block');
        $('#placeQuantity').prop('disabled', true);
        
        $.get('/diffrent-qty', {qty:placeQuantity, group:group_active}, function(res){
            $('#tableDiffAccount').html("");
            $('#tableDiffAccount').append(res);
        });
        
    }else{
        $('#diff_qty').val("");
        $('#placeQuantity').prop('disabled', false);
        $('#accounts').css('display', 'block');
        $('#tableDiffAccount').css('display', 'none');
    }

}

function setGroupAccount(){
  var checkBox = document.getElementById("group_account");
  var diff_qty = $("#diff_qty"); 
  if (checkBox.checked == true){
     $('#group_active').val('group');
     
        $.get('/group/getgroupaccount',{}, function(result){
            $('#accounts').html("");
            $('#accounts').append(result);
        });
     
  } else {
      $('#group_active').val('account');
    $.get('/group/getnotgroupaccount',{}, function(result){
        $('#accounts').html("");
        $('#accounts').append(result);
    });
  }

}

function getAddDataOnSerachBox(str){
    $('#searchBox').val("");
    $('#searchBox').val(str);
    $("#searchResults").css({'display':'none'});  
    $('#searchResults').html("");
    getSearch().stop();
}
 function getSearch(str){
     if(str.length<2){
        $("#searchResults").css({'display':'none'});  
        $('#searchResults').html("");
     }else{
         $.get('/trade/searchTradeSymbol', {key:str}, function(result){
             $("#searchResults").css({'display':'block'});  
             $('#searchResults').html("");
             $('#searchResults').append(result);
         });
     }
 }
 
$('input:radio[name="transactiontype"]').change(function(){
    if ($(this).is(':checked') && $(this).val() == 'BUY') {
        $("#saveButon").html("BUY");
    }else if ($(this).is(':checked') && $(this).val() == 'SELL') {
        $("#saveButon").html("SELL");
    }
});
 $('input:radio[name="variety"]').change(
    function(){
        if ($(this).is(':checked') && $(this).val() == 'NORMAL') {
            $('#placeTriggerPrice').prop('disabled', true);
            $('#placeTarget').prop('disabled', true);
            $('#placeStoploss').prop('disabled', true);
            $('#placeTrailingStoploss').prop('disabled', true);
        }else if ($(this).is(':checked') && $(this).val() == 'ROBO') {
            $('#placeTriggerPrice').prop('disabled', true);
            $('#placeTarget').prop('disabled', false);
            $('#placeStoploss').prop('disabled', false);
            $('#placeTrailingStoploss').prop('disabled', false);
        }else if ($(this).is(':checked') && $(this).val() == 'STOPLOSS') {
            $('#placeTriggerPrice').prop('disabled', false);
            $('#placeTarget').prop('disabled', true);
            $('#placeStoploss').prop('disabled', true);
            $('#placeTrailingStoploss').prop('disabled', true);
        }else{
        
           $('#placeTriggerPrice').prop('disabled', true);
            $('#placeTarget').prop('disabled', true);
            $('#placeStoploss').prop('disabled', true);
            $('#placeTrailingStoploss').prop('disabled', true);
        }
    });
 
    $('input:radio[name="orderType"]').change(function(){
        if ($(this).is(':checked') && $(this).val() == 'LIMIT') {
            $('#placePrice').prop('disabled', false);
            $('#placeTriggerPrice').prop('disabled', true);
        }else if ($(this).is(':checked') && $(this).val() == 'MARKET') {
            $('#placePrice').prop('disabled', true);
            $('#placeTriggerPrice').prop('disabled', true);
        }else if ($(this).is(':checked') && $(this).val() == 'STOP_LOSS') {
            $('#placePrice').prop('disabled', false);
            $('#placeTriggerPrice').prop('disabled', false);
        }else if ($(this).is(':checked') && $(this).val() == 'SL_MARKET') {
            $('#placePrice').prop('disabled', true);
        }
    });
 
      // Optional
      Prism.plugins.NormalizeWhitespace.setDefaults({
      'remove-trailing': true,
      'remove-indent': true,
      'left-trim': true,
      'right-trim': true,
      });

      function checkQtyAndMargin(str){
          
      }
      var clicked = false;
      
        $("#selectAll").on("click", function() {
        $(".selecteditem").prop("checked", !clicked);
        clicked = !clicked;
        this.innerHTML = clicked ? 'Deselect' : 'Select';
        }); 
    </script>
@endsection

