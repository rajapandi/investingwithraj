@extends('layout.app')

@section('content')
<style>
    .table td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }.table th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        font-weight: bold 900;
        
    }
    .selected {
    background: lightBlue
}
</style>

    <div class="page-holder bg-gray-100">
        <div class="container-fluid px-lg-4 px-xl-5">
          <!-- Page Header-->
          <div class="page-header d-flex justify-content-between align-items-center">
              <table style="width:40%;">
                  <tr>
                      <td>
                          <select class="form-control" style="">
                          <?php
                          $ta = App\Models\TradingAccount::all();
                          if($ta){
                              foreach($ta as $allta){
                                  echo '<option value="'.$allta->id.'">'.$allta->login_id.'</option>';
                              }
                          }
                          ?>
                          </select>
                      </td>
                      <td>
                        <a href="/orders/list"><button class="btn btn-success">Refresh</button></a>
                      </td>
                  </tr>
              </table>
            <!--<h1 class="page-heading">Trading Accounts</h1>-->
            <!--<div><a class="btn btn-primary text-uppercase" href="/trading/create"> <i class="fa fa-plus me-2"> </i>Create</a></div>-->
          </div>
          <section class="mb-5">
            <div class="card">
              <div class="card-body">
                  <span style="color:red;">{{Session::get('msg')}}</span>
                  <!--<div style="overflow-x:scroll;">-->
                  @if($GetOrderData)
                  <form action="/trade/showModifyOrders" id="OrderUpdateForm" method="post">
                      @csrf
                    @endif
                    <table style="width:40%;">
                      <tr>
                          <td>
                              <select class="form-control" id="posStateSelect">
                                <option value="ALL" selected="selected">ALL</option>
                                <option value="OPEN">OPEN</option>
                                <option value="COMPLETE">COMPLETE</option>
                                <option value="CANCELLED">CANCELLED</option>
                                <option value="REJECTED">REJECTED</option>
                                <option value="TRIGGER_PENDING">TRIGGER_PENDING</option>
                                <option value="UNKNOWN">UNKNOWN</option>
                              </select>
                          </td>
                          <td>
                            <button type="button" class="btn btn-success" onclick="clearAll()">Reset</button>
                          </td>
                          <td>
                            <button class="btn btn-info" type="submit" name="Modify"  value="Modify">Modify</button>
                          </td>
                          <td>
                            <button type="submit" class="btn btn-danger" value="Cancel" name="Cancel">Cancel</button>
                          </td>
                          
                      </tr>
                    </table>
                    <table class="table table-hover align-middle table-bordered" id="datatable1">
                      <thead>
                        <tr>
                            <th></th>
                            <th>Exch</th>
                            <th>Symbol</th>
                            <th>Trd Acc</th>
                            <th>Status</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Trade</th>
                            <th>Order</th>
                            <th>Pend Qty</th>
                            <th>Fill Qty</th>
                            <th>Avg Prc</th>
                            <th>Broker</th>
                            <th>Product</th>
                            <th>Trig Prc</th>
                            <th>Validity</th>
                            <th>Exch Time</th>
                            <th>Status Msg</th>
                            <th>Br Status</th>
                            {{-- <th>Br Exch</th> --}}
                            <th>Br Symbol</th>
                            <th>Day</th>
                            <th>Variety</th>
                            <th>Id</th>
                            <th>Plat Time</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php
                          if($GetOrderData){
                              foreach ($GetOrderData as $key => $values) {
                                  foreach($values['data'] as $value){
                                    ?>
                                    <tr>
                                        <td>
                                            @if($value["orderstatus"]=="rejected")
                                                <input type="checkbox" id="chkAccountId" name="chkAccountId[]" value="{{$value['orderid']}}" disabled>
                                            @else
                                                @if($value["orderstatus"]=="OPEN")
                                                    <input type="checkbox" id="chkAccountId" name="chkAccountId[]" value="{{$value['orderid']}}" >
                                                @endif
                                                @if($value["orderstatus"]=="AMO SUBMITTED")
                                                    <input type="checkbox" id="chkAccountId" name="chkAccountId[]" value="{{$value['orderid']}}" disabled>
                                                @else
                                                    @if($value["orderstatus"]=="OPEN")
                                                        <input type="checkbox" id="chkAccountId" name="chkAccountId[]" value="{{$value['orderid']}}" >
                                                    @endif
                                                    @if($value["orderstatus"]=="AMO CANCELLED")
                                                        <input type="checkbox" id="chkAccountId" name="chkAccountId[]" value="{{$value['orderid']}}"  >
                                                    @else
                                                        <input type="checkbox" id="chkAccountId" name="chkAccountId[]" value="{{$value['orderid']}}"  disabled>
                                                    @endif
                                                @endif
                                            @endif
                                            </td>
                                        <td>{{$value["exchange"]}}</td>
                                        <td>{{$value["tradingsymbol"]}}</td>
                                        <td>{{$value['loginId']}}</td>
                                        <td>
                                            @if($value["orderstatus"]=="rejected")
                                                <span class="alert alert-danger">Rejected</span>
                                            @else
                                                @if($value["orderstatus"]=="AMO SUBMITTED")
                                                    <span class="alert alert-success">Submitted</span>
                                                @else
                                                    @if($value["orderstatus"]=="AMO CANCELLED")
                                                        <span class="alert alert-danger">Cancelled</span>
                                                    @else
                                                        {{$value["orderstatus"]}}
                                                    @endif
                                                @endif
                                            @endif
                                            </td>
                                        <td>{{$value["quantity"]}}</td>
                                        <td>{{$value["price"]}}</td>
                                        <td>{{$value["transactiontype"]}}</td>
                                        <td>{{$value["ordertype"]}}</td>
                                        <td>{{$value["unfilledshares"]}}</td>
                                        <td>{{$value["filledshares"]}}</td>
                                        <td>{{$value["averageprice"]}}</td>
                                        <td>{{$value['broker']}}</td>
                                        <td>{{$value["producttype"]}}</td>
                                        <td>{{$value["triggerprice"]}}</td>
                                        <td>{{$value["duration"]}}</td>
                                        <td>{{$value["exchtime"]}}</td>
                                        <td>{{$value["text"]}}</td>
                                        <td>{{$value["status"]}}</td>
                                        <td>{{$value["tradingsymbol"]}}</td>
                                        <td>{{date("d-M-Y", strtotime($value["updatetime"]))}}</td>
                                        <td>{{$value["variety"]}}</td>
                                        <td>{{$value["orderid"]}}</td>
                                        <td>{{date("h:s:i A", strtotime($value["updatetime"]))}}</td>
                                    </tr>
                                    <?php
                                  }
                                }
                          }
                          ?>
                      </tbody>
                    </table>
                    @if($GetOrderData)
                </form>
                @endif
                <!--</div>-->
                
              </div>
            </div>
          </section>
        </div>
        
      </div>
    </div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="model_content">
      
    </div>
  </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
     <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="/assets/js/tables-datatable.714838df.js"></script>
  <script type="text/javascript">
  
  function getSubmitOrderUpdateForm(){
      $("#OrderUpdateForm").submit();
  }
    
    function getCancelOrder(){
        $('#OrderCancelForm').submit();
    }
  
    function clearAll() {
        for (var i = 0; i < trs.length; i++) {
            trs[i].className = '';
        }
        $('input[name=chkAccountId]').attr('checked', false);
    }
      Prism.plugins.NormalizeWhitespace.setDefaults({
      'remove-trailing': true,
      'remove-indent': true,
      'left-trim': true,
      'right-trim': true,
      });
          
    </script>
@endsection

