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
                  
                  <form action="/trade/showModifyOrders" id="OrderUpdateForm" method="post">
                      @csrf
                    
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
                            <th>Symbol</th>
                            <th>Transaction Type</th>
                            <th>Variety</th>
                            <th>Exchange</th>
                            <th>Quantity</th>
                            <th>price</th>
                            <th>Validity Type</th>
                            <th>Validity</th>
                            <th>No Of Order</th>
                            <th>Executed Order</th>
                            <th>Schedular Type</th>
                            <th>is Active</th>
                            <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php
                          $sd = App\Models\Schedular::all();  
                          if($sd){
                            foreach ($sd as $allsd) {
                              ?>
                              <tr>
                                <td>{{$allsd->symbol}}</td>
                                <td>{{$allsd->transaction_type}}</td>
                                <td>{{$allsd->variety}}</td>
                                <td>{{$allsd->exchange}}</td>
                                <td>{{$allsd->quantity}}</td>
                                <td>{{$allsd->price}}</td>
                                <td>{{$allsd->validity_type}}</td>
                                <td>{{$allsd->validity}}</td>
                                <td>{{$allsd->no_of_order}}</td>
                                <td>{{$allsd->executed_order}}</td>
                                <td>{{$allsd->schedular_type}}</td>
                                <td>{{$allsd->is_active}}</td>
                                <td></td>
                              </tr>
                              <?php
                            }
                          }
                          ?>
                      </tbody>
                    </table>
                    
                </form>
                
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

