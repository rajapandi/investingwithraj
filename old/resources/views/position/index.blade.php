@extends('layout.app')

@section('content')

<div class="page-holder bg-gray-100">
    <div class="container-fluid px-lg-4 px-xl-5">
      <section class="mb-4 mb-lg-5">
            
            <div class="row text-dark">
                <div class="col-md-12 col-xl-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2>Position</h2>
                            <table class="table table-striped table-bordered nowrap compact dataTable no-footer" id="datatable1">
				                <thead>
					                <tr role="row">
					                    <th>Trd Acc</th>
					                    <th>Symbol</th>
					                    <th>Net Qty</th>
                                        <th>Net Value</th>
					                    <th>PnL</th>
					                    <th>Buy Qty</th>
					                    <th>Sell Qty</th>
					                    <th>Buy Value</th>
					                    <th>Sell Value</th>
					                    <th>Net Value</th>
					                    <th>Day</th>
					                    <th>Platform</th>
					                    <th>Broker</th>
					               </tr>
				                </thead>
				                <tbody id="holdings-body"> 
				                 <?php
                                    if($GetPosition){
                                          foreach ($GetPosition as $key => $value) {
                                              foreach($value['data'] as $values){
                                                  ?>
                                                  <tr>
                                                      <td>{{$values['loginId']}}</td>
                                                      <td>{{$values['tradingsymbol']}}</td>
                                                      <td>{{$values['netqty']}}</td>
                                                      <td>{{$values['netvalue']}}</td>
                                                      <td></td>
                                                      <td>{{$values['buyqty']}}</td>
                                                      <td>{{$values['sellqty']}}</td>
                                                      <td>{{$values['buyamount']}}</td>
                                                      <td>{{$values['sellamount']}}</td>
                                                      <td>{{$values['netprice']}}</td>
                                                      <td>{{$values['producttype']}}</td>
                                                      <td>{{$values['platform']}}</td>
                                                      <td>{{$values['broker']}}</td>
                                                  </tr>
                                                  <?php
                                              }
                                          }
                                        // }
                                    }
                                  ?>
				                </tbody>
			                </table>
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
     <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="/assets/js/tables-datatable.714838df.js"></script>
<script>
    "use strict";

document.addEventListener("DOMContentLoaded", function () {
    // document.getElementById('account').size = '4';
});
    
    function getChangeStockBroker(str){
        $.get('/dashboard/getChangeStockBroker', {str:str}, function(result){
            $('#account').html("");
            $('#account').append(result);
        });
    }
    function getPortfolio(id){
        $.get('/dashboard/getPortfolio', {id:id}, function(result){
            $('#profileData').html("");
            $('#profileData').append(result);
        });
    }
</script>
@endsection


