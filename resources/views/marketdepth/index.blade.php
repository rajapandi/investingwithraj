@extends('layout.app')

@section('content')
<style>

.container {
  max-width: 960px;
}

.table>tbody>tr.active>td,
.table>tbody>tr.active>th,
.table>tbody>tr>td.active,
.table>tbody>tr>th.active,
.table>tfoot>tr.active>td,
.table>tfoot>tr.active>th,
.table>tfoot>tr>td.active,
.table>tfoot>tr>th.active,
.table>thead>tr.active>td,
.table>thead>tr.active>th,
.table>thead>tr>td.active,
.table>thead>tr>th.active {
  background-color: #fff;
}

.table-bordered > tbody > tr > td,
.table-bordered > tbody > tr > th,
.table-bordered > tfoot > tr > td,
.table-bordered > tfoot > tr > th,
.table-bordered > thead > tr > td,
.table-bordered > thead > tr > th {
  border-color: #e4e5e7;
}

.table tr.header {
  font-weight: bold;
  background-color: #fff;
  cursor: pointer;
  -webkit-user-select: none;
  /* Chrome all / Safari all */
  -moz-user-select: none;
  /* Firefox all */
  -ms-user-select: none;
  /* IE 10+ */
  user-select: none;
  /* Likely future */
}

.hidetr {
  display: none;
}

.table .header td:after {

  position: relative;
  top: 1px;
  display: inline-block;
  font-family: 'Glyphicons Halflings';
  font-style: normal;
  font-weight: 400;
  line-height: 1;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  float: right;
  color: #999;
  text-align: center;
  padding: 3px;
  transition: transform .25s linear;
  -webkit-transition: -webkit-transform .25s linear;
}


</style>
<div class="page-holder bg-gray-100">
    <div class="container-fluid px-lg-4 px-xl-5">
      <section class="mb-4 mb-lg-5">
            <div class="marketdepth-page">
				<div class="row text-dark">
					<div class="col-md-12 col-xl-12 mb-4">
						<div class="card">
							<div class="card-body" id="card-body">
									<h2 class="section-heading">Market Depth</h2>

									<button class="btn btn-primary btn-sm pull-right" onclick="createMarketDepth()"><i class="fa fa-plus" aria-hidden="true"></i> Create New</button>
									<div class="clearfix"></div>
										<div class="idmarket-content">
										<div class="row" id="idmarketDepth">
										<?php
										$ta = App\Models\TradingAccount::where('login_id', 'CS0523')->first();
										$md = App\Models\MarketDepth::where('is_active', 'active')->orderBY('id', 'desc')->limit(8)->get();
										if($md){
											foreach ($md as $allmd) {
												$exchange = $allmd->exchange;
												$sysmbol = $allmd->symbol;

													$curl=curl_init();
													curl_setopt($curl,CURLOPT_URL,'https://api.kite.trade/quote?i='.$allmd->exchange.':'.$allmd->symbol);
													curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,2);
													curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
													curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'X-Kite-Version: 3', 'Authorization:token '.ENV('KITE_KEY').':'.$ta->access_token));
													$result = curl_exec($curl);
													curl_close($curl);
													if (empty($result)){
														print "Nothing returned from url.<p>";

													}
													else{
														$finalData = json_decode($result)->data;
														$f = $allmd->exchange.':'.$allmd->symbol;
													?>
													<div class="col-sm-6">
													  <div class="modal-header">
														<h6 class="modal-title" id="exampleModalLabel">{{$sysmbol}} <span style="font-size: 10px;">{{$exchange}}</span>
														</h6>
														<span style="float: right!important">
														  <i class="fa fa-inr" aria-hidden="true"></i> {{$finalData->$f->last_price}}
															@if(str_contains(($finalData->$f->average_price-$finalData->$f->last_price), '-'))
															  <span class="text-danger">{{ number_format($finalData->$f->average_price-$finalData->$f->last_price, 2)}}
															</span>
															@else
															  <span class="text-green">+{{number_format($finalData->$f->average_price-$finalData->$f->last_price, 2)}} </span>
															@endif
														</span>
														{{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
													  </div>
													  <div class="modal-body">
														<div class="row depth-table">
														  <div class="col-sm-6">
															<p>Bid</p>
															<table style="width: 100%">
															  <thead>
																<tr style="font-size: 11px;">
																  <th>Price</th>
																  <th>Orders</th>
																  <th>Qty</th>
																</tr>
															  </thead>
															<?php
															foreach ($finalData->$f->depth->buy as  $buy) {
															  ?>
															  <tbody>
																<tr style="font-size: 11px;">
																  <td class="text-green"><a href="/trade/order-create?symbol={{$sysmbol}}&price={{$buy->price}}&type=buy">{{$buy->price}}</a></td>
																  <td class="text-green">{{$buy->orders}}</td>
																  <td class="text-green">{{$buy->quantity}}</td>
																</tr>
															  </tbody>
															  <?php
															}
															?>
															<tfoot>
															  <tr>
																<td>Total</td>
																<td colspan="2"><span class="text-right text-green" style="float: right!important;">{{$finalData->$f->buy_quantity}}</span></td>
															  </tr>
															</tfoot>
															</table>
														  </div>
														  <div class="col-sm-6">
															<p>Ask Price</p>
															<table style="width: 100%">
															  <tr style="font-size: 11px;">
																<th>Price</th>
																<th>Orders</th>
																<th>Qty</th>
															  </tr>
															<?php
															foreach ($finalData->$f->depth->sell as $sell) {
															  ?>
															  <tr style="font-size: 11px;">
																<td class="text-danger"><a href="/trade/order-create?symbol={{$sysmbol}}&price={{$sell->price}}&type=sell">{{$sell->price}}</a></td>
																<td class="text-danger">{{$sell->orders}}</td>
																<td class="text-danger">{{$sell->quantity}}</td>
															  </tr>
															  <?php
															}
															?>
															<tfoot>
															  <tr>
																<td>Total</td>
																<td colspan="2"><span class="text-right text-danger" style="float: right">{{$finalData->$f->sell_quantity}}</span></td>
															  </tr>
															</tfoot>
															</table>
														  </div>
														  <hr>
														  <div class="final-data">
															  <div class="col-sm-12">
																<div class="row">
																  <div class="col-sm-3" style="font-size: 11px;">{{$finalData->$f->ohlc->open}}<br>O</div>
																  <div class="col-sm-3" style="font-size: 11px;">{{$finalData->$f->ohlc->high}}<br>H</div>
																  <div class="col-sm-3" style="font-size: 11px;">{{$finalData->$f->ohlc->low}}<br>L</div>
																  <div class="col-sm-3" style="font-size: 11px;">{{$finalData->$f->ohlc->close}}<br>C</div>
																</div>
															  </div>
														  </div>
														  <hr>
														  <div class="depth-table-btns">
															  <div class="col-sm-12">
																  <a href="/trade/order-create?symbol={{$sysmbol}}&price={{$finalData->$f->last_price}}&type=buy"><button type="button" class="btn btn-primary btn-sm">Buy</button></a>
																  <a href="/trade/order-create?symbol={{$sysmbol}}&price={{$finalData->$f->last_price}}&type=sell"><button type="button" class="btn btn-danger btn-sm">Sell</button></a>
																  <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal" onclick="deleteDepth({{$allmd->id}})">Delete</button>
																</div>
															</div>

														</div>

													  </div>
													</div>
													<?php
													}
											}
										}
										?>
										</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </section>
    </div>

  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" id="modal_content">

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
    $(document).ready(function() {
    $('#datatable1').DataTable( {
        "lengthMenu": [[100, 250, 500, -1], [100, 250, 500, "All"]]
    } );
} );
document.addEventListener("DOMContentLoaded", function () {
    // document.getElementById('account').size = '4';
    lengthMenu: [[50, 100, 500, -1], [50, 100, 500, "All"]],
});
</script>
<script>
    function createMarketDepth(){
        $('#modal_content').html("");
        var myModal = new bootstrap.Modal(document.getElementById("exampleModal"), {});
        $.get('/create/marketdepth', {}, function(result){
            $('#modal_content').html("");
            $('#modal_content').html(result);
        });
        myModal.show();
    }
    function deleteDepth(id){
      $.get('/delete/depth/'+id, {}, function(res){
        alert("Market Depth Delete successfull");
        location.reload();
      });
    }
//idmarketDepth
$(document).ready(function() {
  setInterval(function() {
      $.get('/jqajax/mrket-depth', {}, function(res){
        $('#idmarketDepth').html('');
        $('#idmarketDepth').html(res);
      });
    }, 1000);
});
</script>

@endsection


