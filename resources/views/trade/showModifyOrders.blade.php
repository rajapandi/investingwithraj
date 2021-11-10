<?php
if(isset($_POST['chkAccountId'])){
}else{
    echo "<script>window.location.href='/orders/list';</script>";
    exit;
}
?>

@extends('layout.app')

@section('content')

    <div class="page-holder bg-gray-100">
        <div class="container-fluid px-lg-4 px-xl-5">
          <div class="page-header d-flex justify-content-between align-items-center">
            <h1 class="page-heading">Modify Order</h1>
          </div>
          <section class="mb-5">
            <div class="card">
              <div class="card-body">
                  <div class="row">
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                          <h4 style="color:#01A9AC;">Keep default values & change only what you need.</h4>
                          <span style="color:red;">{{Session::get('msg')}}</span>
                        <form action="/trade/modify/update" method="post"> 
                            @csrf
                                <?php
                                $chkAccountId = $_POST['chkAccountId'];
                                
                                foreach($chkAccountId as $chkAccountIds){
                                    echo '<input type="hidden" value="'.$chkAccountIds.'" name="chkAccountId[]">';
                                }
                                ?>

                            
                            <div class="mb-3">
                                <label class="form-label text-uppercase">Order Type</label>
                                <select name="orderType" id="modifyOrderType" class="form-control form-control-primary" required>
									<option value="" selected="">NO_CHANGE</option>
									<option value="LIMIT">LIMIT</option>
									<option value="MARKET">MARKET</option>
									<option value="STOP_LOSS">STOP_LOSS</option>
									<option value="SL_MARKET">SL_MARKET</option>
								</select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-uppercase">Quantity</label>
                                <input type="number" class="form-control" value="0" name="quantity" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-uppercase">Price</label>
                                <input type="number" class="form-control" value="0" name="price" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-uppercase">Trigger Price</label>
                                <input type="number" class="form-control" value="0" name="triggerPrice" required>
                            </div>
                           
                          </div>
                          <div class="mb-3">  
                          <label class="form-label text-uppercase">
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="/orders/list"><button class="btn btn-danger" type="button" style="float:center;">CANCEL</button></a>
                            <button class="btn btn-primary" type="submit" style="float:center;" id="saveButon">MODIFY</button>
                            </label>
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

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
 <script type="text/javascript">
$('input:radio[name="transactiontype"]').change(function(){
    if ($(this).is(':checked') && $(this).val() == 'BUY') {
        $("#saveButon").html("BUY");
    }else if ($(this).is(':checked') && $(this).val() == 'SELL') {
        $("#saveButon").html("SELL");
    }
});
 $('input:radio[name="variety"]').change(
    function(){
        if ($(this).is(':checked') && $(this).val() == 'REGULAR') {
            $('#placeTriggerPrice').prop('disabled', true);
            $('#placeTarget').prop('disabled', true);
            $('#placeStoploss').prop('disabled', true);
            $('#placeTrailingStoploss').prop('disabled', true);
            $('#productTypeDelivery').prop("disabled",false);
            $('#productTypeNormal').prop("disabled",false);
            $('#orderTypeStopLoss').prop("disabled",false);
            $('#orderTypeSLMarket').prop("disabled",false);
        }else if ($(this).is(':checked') && $(this).val() == 'BO') {
            $('#placeTriggerPrice').prop('disabled', true);
            $('#placeTarget').prop('disabled', false);
            $('#placeStoploss').prop('disabled', false);
            $('#placeTrailingStoploss').prop('disabled', false);
            $('#productTypeDelivery').prop("disabled",true);
            $('#productTypeNormal').prop("disabled",true);
            $('#orderTypeStopLoss').prop("disabled",false);
            $('#orderTypeSLMarket').prop("disabled",false);
        }else if ($(this).is(':checked') && $(this).val() == 'CO') {
            $('#placeTriggerPrice').prop('disabled', false);
            $('#placeTarget').prop('disabled', true);
            $('#placeStoploss').prop('disabled', true);
            $('#placeTrailingStoploss').prop('disabled', true);
            $('#productTypeDelivery').prop("disabled",true);
            $('#productTypeNormal').prop("disabled",true);
            $('#orderTypeStopLoss').prop("disabled",true);
            $('#orderTypeSLMarket').prop("disabled",true);
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
          
    </script>
@endsection

