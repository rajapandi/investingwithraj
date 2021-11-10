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
            
            <div class="row text-dark">
                <div class="col-md-12 col-xl-12 mb-4">
                    <div class="card">
                        <div class="card-body" id="card-body">
                            <h2 class="section-heading section-heading-ms">Portfolio</h2>
                            <span style="float: right">
                                <div style="display: flex;padding: 10px;">
                                  <select name="" id="sort" class="form-control" onchange="changePlatform(this.value)" style="width: 120px;" > 
                                    <option value="All"  @if(isset($_GET['platform'])) @if($_GET['platform']=="All") selected @endif @endif>Platform</option>
                                    <option value="Angel" @if(isset($_GET['platform'])) @if($_GET['platform']=="Angel") selected @endif @endif>Angel</option>
                                    <option value="Zerodha"  @if(isset($_GET['platform'])) @if($_GET['platform']=="Zerodha") selected @endif @endif>Zerodha</option>
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
                                </div>
                              </span>
                            <table class="table table-bordered nowrap compact dataTable no-footer" id="">
				                <thead>
					                <tr role="row" class="alert alert-info">
					                    <th>Platform</th>
					                    <th>Acc ID</th>
					                    <th>Fund</th>
                                        <th>Total Investment</th>
					                    <th>Total Return</th>
					                    <th>Days Gain</th>
                                        <th>Action</th>
					               </tr>
				                </thead>
				                <tbody id="holdings-body"> 
                                    <?php
                                   if($json){
                                        foreach ($json as $key => $values) {
                                            ?>
                                            <tr  class="header">
                                                <td>{{$values['broker']}}</td>
                                                <td>{{$values['loginId']}}</td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i> 
                                                    @if($values['broker']=="Angel")
                                                    <?php
                                                    $smart_api  = new \AngelBroking\SmartApi();
                                                    $smart_api ->GenerateSession($values['loginId'], $values['password']);    
                                                    $jsonData = $smart_api->GetRMS();
                                                    $preRMS = json_decode($jsonData, true);
                                                    echo number_format(number_format((float)$preRMS['response_data']['data']['availablecash'], 2, '.', ''),2);
                                                   ?>
                                                   @endif
                                                   @if($values['broker']=="Zerodha")
                                                   <?php
                                                   $ta = App\Models\TradingAccount::where('login_id', $values['loginId'])->first();
                                                    $kite = new \KiteConnect\KiteConnect(env('KITE_KEY'));
                                                    $kite->setAccessToken($ta->access_token);
                                                    $kiteMargin = json_encode($kite->getMargins("equity"));
                                                    
                                                    echo number_format(number_format((float)json_decode($kiteMargin)->net, 2, '.', ''),2);
                                                    ?>
                                                   @endif
                                                </td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i> {{number_format($values['totalInvestment'],2)}}</td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i> {{number_format($values['totalReturn'], 2)}} <br>
                                                    @if(str_contains(($values['ltp']-$values['averageprice']), '-'))

                                                        <span class="text-danger"><i class="fa fa-arrow-down" aria-hidden="true"></i> {{ number_format((float)((($values['ltp']-$values['averageprice'])/$values['averageprice'])*100), 2, '.', '') }}%</span><br>
                                                        <span class="text-danger"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(number_format((float)(($values['ltp']-$values['averageprice'])*$values['quantity']), 2, '.', ''),2) }}</span>

                                                    @else

                                                        <span style="color:#459e85"><i class="fa fa-arrow-up" aria-hidden="true"></i> {{ number_format((float)((($values['ltp']-$values['averageprice'])/$values['averageprice'])*100), 2, '.', '') }}%</span><br>
                                                        <span style="color:#459e85"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(number_format((float)(($values['ltp']-$values['averageprice'])*$values['quantity']), 2, '.', ''), 2) }}</span>

                                                    @endif
                                                </td>
                                                <td>{{number_format($values['close']*$values['quantity'], 2)}}<br>
                                                    @if(str_contains(($values['close']-$values['averageprice']), '-'))

                                                        <span class="text-danger"><i class="fa fa-arrow-down" aria-hidden="true"></i> {{ number_format((float)((($values['close']-$values['averageprice'])/$values['averageprice'])*100), 2, '.', '') }}%</span><br>
                                                        <span class="text-danger"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format((float)(($values['close']-$values['averageprice'])*$values['quantity']), 2, '.', '') }}</span>

                                                    @else

                                                        <span style="color:#459e85"><i class="fa fa-arrow-up" aria-hidden="true"></i> {{ number_format((float)((($values['close']-$values['averageprice'])/$values['averageprice'])*100), 2, '.', '') }}%</span><br>
                                                        <span style="color:#459e85"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format((float)($values['close']-$values['averageprice']), 2, '.', '') }}</span>

                                                    @endif</td>
                                                <td>
                                                    <div>
                                                        <a href="/trade/order-create?login={{$values['loginId']}}&type=buy"><button class="btn btn-primary btn-sm mt-2">BUY</button></a>
                                                        <a href="/trade/order-create?login={{$values['loginId']}}&type=sell"><button class="btn btn-success btn-sm mt-2">SELL</button></a>
                                                        <button class="btn btn-danger btn-sm mt-2">KILL</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            $y=0;
                                            foreach($values['ITEMS'] as $items){
                                                $y=$y+1;
                                                ?>
                                                <tr class="hidetr">
                                                    <td>{{$items['exchange']}}</td>
                                                    <td>
                                                      <a href="/holding?symbol={{$items['tradingsymbol']}}">{{$items['tradingsymbol']}}</a><br>(Qty {{$items['quantity']}})</td>
                                                    <td>{{ number_format(number_format((float)$items['averageprice'], 2, '.', ''), 2)}}</td>
                                                    <td>{{$items['ltp']}}</td>
                                                    <td><i class="fa fa-inr" aria-hidden="true"></i> {{number_format($items['totalReturn'], 2)}}<br>
                                                        @if(str_contains(($items['ltp']-$items['averageprice']), '-'))
    
                                                            <span class="text-danger"><i class="fa fa-arrow-down" aria-hidden="true"></i> {{ number_format((float)((($items['ltp']-$items['averageprice'])/$items['averageprice'])*100), 2, '.', '') }}%</span><br>
                                                            <span class="text-danger"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(number_format((float)(($items['ltp']-$items['averageprice'])*$items['quantity']), 2, '.', ''),2) }}</span>
    
                                                        @else
    
                                                            <span style="color:#459e85"><i class="fa fa-arrow-up" aria-hidden="true"></i> {{ number_format((float)((($items['ltp']-$items['averageprice'])/$items['averageprice'])*100), 2, '.', '') }}%</span><br>
                                                            <span style="color:#459e85"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(number_format((float)(($items['ltp']-$items['averageprice'])*$items['quantity']), 2, '.', ''),2) }}</span>
    
                                                        @endif
                                                    </td>
                                                    <td><i class="fa fa-inr" aria-hidden="true"></i> {{number_format($items['close']*$items['quantity'], 2)}}
                                                        <br>
                                                        @if(str_contains(($items['close']-$items['averageprice']), '-'))
    
                                                            <span class="text-danger"><i class="fa fa-arrow-down" aria-hidden="true"></i> {{ number_format((float)((($items['close']-$items['averageprice'])/$items['averageprice'])*100), 2, '.', '') }}%</span><br>
                                                            <span class="text-danger"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(number_format((float)(($items['close']-$items['averageprice'])*$items['quantity']), 2, '.', ''),2) }}</span>
    
                                                        @else
    
                                                            <span style="color:#459e85"><i class="fa fa-arrow-up" aria-hidden="true"></i> {{ number_format((float)((($items['close']-$items['averageprice'])/$items['averageprice'])*100), 2, '.', '') }}%</span><br>
                                                            <span style="color:#459e85"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(number_format((float)(($items['close']-$items['averageprice'])*$items['quantity']), 2, '.', ''), 2) }}</span>
    
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <a href="/trade/order-create?login={{$values['loginId']}}&symbol={{$items['tradingsymbol']}}&type=buy"><button class="btn btn-primary btn-sm mt-2">BUY</button></a>
                                                            <a href="/trade/order-create?login={{$values['loginId']}}&symbol={{$items['tradingsymbol']}}&type=sell"><button class="btn btn-success btn-sm mt-2">SELL</button></a>
                                                            <button class="btn btn-danger btn-sm mt-2">KILL</button> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                # code...
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

function changePlatform(str){
  if(GetURLParameter("search")){
    if(GetURLParameter("sortby")){
      location.href =  "/portfolio?platform="+str+"&sortby="+GetURLParameter("sortby")+"&search="+GetURLParameter("search");
    }else{
      location.href =  "/portfolio?platform="+str+"&search="+GetURLParameter("search");
    }
  }else{
    if(GetURLParameter("sortby")){
      location.href =  "/portfolio?platform="+str+"&sortby="+GetURLParameter("sortby");
    }else{
      location.href =  "/portfolio?platform="+str;
    }
  }
}

function changeSort(str){
  if(GetURLParameter("search")){
    if(GetURLParameter("platform")){
      location.href =  "/portfolio?platform="+GetURLParameter("platform")+"&sortby="+str+"&search="+GetURLParameter("search");
    }else{
      location.href =  "/portfolio?sortby="+str+"&search="+GetURLParameter("search");
    }
  }else{
    if(GetURLParameter("platform")){
      location.href =  "/portfolio?platform="+GetURLParameter("platform")+"&sortby="+str;
    }else{
      location.href =  "/portfolio?sortby="+str;
    }
  }
}

function searchBy(){
  var filter = $('#filter').val();
  if(GetURLParameter("sortby")){
    if(GetURLParameter("platform")){
      location.href =  "/portfolio?platform="+GetURLParameter("platform")+"&sortby="+GetURLParameter("sortby")+"&search="+filter;
    }else{
      location.href =  "/portfolio?sortby="+GetURLParameter("sortby")+"&search="+filter;
    }
  }else{
    if(GetURLParameter("platform")){
      location.href =  "/portfolio?platform="+GetURLParameter("platform")+"&search="+filter;
    }else{
      location.href =  "/portfolio?search="+filter;
    }
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

</script>
@endsection


