@extends('layout.app')

@section('content')
<style>
.my-custom-scrollbar {
position: relative;
height: 200px;
overflow: auto;
}
.table-wrapper-scroll-y {
display: block;
}

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
            <div class="holding-page">
				<div class="row text-dark">
					<div class="col-md-12 col-xl-12 mb-4">
						<div class="card">
							<div class="card-body">
                                @php
                                if($json){
                                    $x=0;
                                    foreach ($json as $values) {
                                        $x=$x+1;
                                    }
                                }
                                @endphp
								<h2 class="section-heading">Holding ({{$x}})</h2>
								 <div class="top-short-search-sec">
                                    <select name="" id="sortByColumn" class="form-control" style="width: 200px;" >
                                        <option value="">Column Name</option>
                                        <option value="tradingsymbol">Symbol</option>
                                        <option value="ltp">LTP</option>
                                        <option value="quantity">Quantity</option>
                                        <option value="totalInvestment">Total Investment</option>
                                        <option value="totalReturn">Current Value</option>
                                      </select>

                                      <select name="" id="sort" class="form-control" onchange="changeSort(this.value)" style="width: 100px;" >
                                        <option value="">Sort</option>
                                        <option value="asc">ASC</option>
                                        <option value="desc">DESC</option>
                                      </select>
										<div class="input-group" style="width: 300px;" >
										  <input type="text" name="filter" id="filter" class="form-control" placeholder="Search">
										  <div class="input-group-append">
											<button class="btn btn-secondary" type="button" onclick="searchBy()">
											  <i class="fa fa-search"></i>
											</button>
										  </div>
										</div>
                                        <button type="reset" class="btn btn-warning btn-sm " onclick="getClearHolding()">Clear</button>

								    </div>


								<div class="fix-table-thead mt-2">
								  <table class="table table-scroll table-striped table-bordered nowrap compact dataTable no-footer" id=""  data-page-length='25'>
									  <thead class="alert alert-info" class="th" >
										<tr role="row">
										  <th>SR</th>
										  <th>Stocks</th>
										  <th>LTP</th>
										  <th>Quantity</th>
										  <th>Total Investment</th>
										  <th>Current Value</th>
										  <th>Days Return</th>
										  <th>Action</th>
									  </tr>
									</thead>
									<tbody id="holdings-body">
									<?php
											$x=0;

												if($json){
													foreach ($json as $values) {
														$x=$x+1;
                                                        $totalReturn=0;$totalReturnPer=0;
                                                        $totalInvestment=0;$qty=0;$ltp=0;
                                                        $averageprice=0;$daysReturn=0;$close=0;
                                                        foreach($values->ITEMS as $items){
                                                            $qty = $qty+$items->quantity;
                                                            $totalInvestment = $totalInvestment+$items->totalInvestment;
                                                            $totalReturn = $totalReturn+number_format((float)($items->totalReturn), 2, '.', '');
                                                            $ltp = $ltp+$items->ltp;
                                                            $averageprice = $averageprice+number_format((float)($items->averageprice), 2, '.', '');
                                                            $daysReturn = $daysReturn+number_format((float)($items->daysReturn), 2, '.', '');
                                                            $close = $close+number_format((float)($items->close), 2, '.', '');

                                                        }

                                                        ?>
                                                        <tr class="header">
                                                            <td class="expand-button" >{{$x}}</td>
                                                            <td><a href="#" onclick="shopMarketDepth('{{$values->tradingsymbol}}', '{{$values->exchange}}')">{{$values->tradingsymbol}}</a></td>
                                                            <td><i class="fa fa-inr" aria-hidden="true"></i> {{ $values->ltp }}</td>
                                                            <td>{{$qty}} </td>
                                                            <td><i class="fa fa-inr" aria-hidden="true"></i>{{ number_format($qty*$averageprice,2) }}</td>
                                                            <td><i class="fa fa-inr" aria-hidden="true"></i>{{ number_format($totalReturn ,2) }}<br>
                                                            @if(str_contains(($ltp-$averageprice), '-'))
                                                                <span class="text-danger"> <i class="fa fa-arrow-down" aria-hidden="true"></i> {{ number_format((float)((($ltp-$averageprice)/$averageprice)*100), 2, '.', '') }}%</span><br>
                                                                <span class="text-danger"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(number_format((float)($ltp-$averageprice), 2, '.', ''), 2) }}</span>
                                                            @else
                                                                <span class="text-green"><i class="fa fa-arrow-up" aria-hidden="true"></i> {{ number_format((float)((($ltp-$averageprice)/$averageprice)*100), 2, '.', '') }}%</span><br>
                                                                <span class="text-green"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format((float)($ltp-$averageprice), 2, '.', '') }}</span>

                                                            @endif
                                                            </td>
                                                            <td><i class="fa fa-inr" aria-hidden="true"></i> {{number_format(($ltp-$close)*$qty, 2)}} <br>
                                                                @if(str_contains(($ltp-$close), '-'))
                                                                <span class="text-danger"><i class="fa fa-arrow-down" aria-hidden="true"></i> {{ number_format((float)((($ltp-$close)/$close)*100), 2, '.', '') }}%</span><br>
                                                                <span class="text-danger"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format((float)($ltp-$close), 2, '.', '') }}</span>
                                                            @else
                                                                <span class="text-green"><i class="fa fa-arrow-up" aria-hidden="true"></i> {{ number_format((float)((($ltp-$close)/$close)*100), 2, '.', '') }}%</span><br>
                                                                <span  class="text-green"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format((float)($ltp-$close), 2, '.', '') }}</span>
                                                            @endif
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <?php
                                                                    $loginArr = array();
                                                                    foreach($values->ITEMS as $item){
                                                                    array_push($loginArr, $item->loginId);
                                                                    }
                                                                    ?>
                                                                    <a href="/trade/order-create?group=true&login={{json_encode($loginArr)}}&symbol={{$values->tradingsymbol}}&exchange={{$values->exchange}}&type=buy"><button class="btn btn-primary btn-sm mt-2">BUY</button></a>
                                                                    <a href="/trade/order-create?group=true&login={{json_encode($loginArr)}}&symbol={{$values->tradingsymbol}}&exchange={{$values->exchange}}&type=sell"><button class="btn btn-danger btn-sm mt-2">SELL</button></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $y=0;
                                                        foreach($values->ITEMS as $items){
                                                            $y=$y+1;
                                                            ?>
                                                            <tr class="hidetr">
                                                                <td>{{$y}}</td>
                                                                <td>
                                                                    <a href="/portfolio?account={{$items->loginId}}">{{$items->loginId}}</a>
                                                                </td>
                                                                <td><i class="fa fa-inr" aria-hidden="true"></i> {{$items->ltp}}</td>
                                                                <td>{{$items->quantity}}</td>
                                                                <td><i class="fa fa-inr" aria-hidden="true"></i> {{number_format((float)$items->quantity, 2, '.', '')*number_format((float)$items->averageprice, 2, '.', '')}}<br>
                                                                (BAP {{ number_format(number_format((float)$items->averageprice, 2, '.', ''), 2)}})
                                                                </td>

                                                                <td><i class="fa fa-inr" aria-hidden="true"></i> {{number_format($items->totalReturn,2)}} <br>
                                                                    @if(str_contains(($items->ltp-$items->averageprice), '-'))


                                                                    <span class="text-danger"> <i class="fa fa-arrow-down" aria-hidden="true"></i> {{ number_format(((($items->ltp-$items->averageprice)/$items->averageprice)*100), 2) }}%</span><br>
                                                                    <span class="text-danger"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format((($items->ltp-$items->averageprice)*$items->quantity), 2) }}</span>

                                                                @else

                                                                    <span class="text-green"><i class="fa fa-arrow-up" aria-hidden="true"></i> {{ number_format((float)((($items->ltp-$items->averageprice)/$items->averageprice)*100), 2, '.', '') }}%</span><br>
                                                                    <span class="text-green"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format((($items->ltp-$items->averageprice)*$items->quantity),2) }}</span>

                                                                @endif
                                                                </td>
                                                                <td><i class="fa fa-inr" aria-hidden="true"></i> {{number_format(($items->ltp-$items->close)*$items->quantity, 2)}} <br>
                                                                    @if(str_contains(($items->ltp-$items->close), '-'))

                                                                    <span class="text-danger"><i class="fa fa-arrow-down" aria-hidden="true"></i> {{ number_format((float)((($items->ltp-$items->close)/$items->close)*100), 2, '.', '') }}%</span><br>
                                                                    <span class="text-danger"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(number_format((float)(($items->ltp-$items->close)), 2, '.', ''),2) }}</span>

                                                                @else

                                                                    <span class="text-green"><i class="fa fa-arrow-up" aria-hidden="true"></i> {{ number_format((float)((($items->ltp-$items->close)/$items->close)*100), 2, '.', '') }}%</span><br>
                                                                    <span  class="text-green"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(number_format((float)(($items->ltp-$items->close)), 2, '.', ''), 2) }}</span>

                                                                @endif
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <a href="/trade/order-create?login={{$items->loginId}}&symbol={{$values->tradingsymbol}}&exchange={{$values->exchange}}&type=buy"><button class="btn btn-primary btn-sm mt-2">BUY</button></a>
                                                                        <a href="/trade/order-create?login={{$items->loginId}}&symbol={{$values->tradingsymbol}}&exchange={{$values->exchange}}&type=sell"><button class="btn btn-danger btn-sm mt-2">SELL</button></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
												}
											  ?>
									</tbody>
								  </table>
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

        $(document).ready(function() {
  //Fixing jQuery Click Events for the iPad
  var ua = navigator.userAgent,
    event = (ua.match(/iPad/i)) ? "touchstart" : "click";
  if ($('.table').length > 0) {
    $('.table .header').on(event, function() {
      $(this).toggleClass("active", "").nextUntil('.header').css('display', function(i, v) {
        return this.style.display === 'table-row' ? 'none' : 'table-row';
      });
    });
  }
})

function getClearHolding(){
    location.href =  "/holding";
}
function changeSort(str){
    var sortByColumn = $('#sortByColumn').val();
    if(sortByColumn!=null || sortByColumn!=""){
        if(GetURLParameter("search")){
            location.href =  "/holding?sortby="+str+"&sortByColumn="+sortByColumn+"&search="+GetURLParameter("search");
        }else{
            location.href =  "/holding?sortby="+str+"&sortByColumn="+sortByColumn;
        }
    }else{
        alert("Please select column first");
    }
}

function searchBy(){
  var filter = $('#filter').val();
  if(GetURLParameter("sortby")){
    // var sortByColumn = $('#sortByColumn').val();
    location.href =  "/holding?search="+filter+"&sortby="+GetURLParameter("sortby")+"&sortByColumn="+GetURLParameter("sortByColumn");
  }else{
    location.href =  "/holding?search="+filter;
  }
}

function GetURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }
};

function shopMarketDepth(symbol, exchange){
    $('#modal_content').html("");
  var myModal = new bootstrap.Modal(document.getElementById("exampleModal"), {});
  $.get('/jqajqx/marketdepth?symbol='+symbol+'&exchange='+exchange, {}, function(result){
    $('#modal_content').html("");
    $('#modal_content').html(result);
  });
  myModal.show();
}

    </script>

@endsection


