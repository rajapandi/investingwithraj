@extends('layout.app')

@section('content')

<div class="page-holder bg-gray-100">
    <div class="container-fluid px-lg-4 px-xl-5">
      <section class="mb-4 mb-lg-5">
	  <div class="dashboard-page">
		
		<div class="page-top-sec">
				<div class="row">
						<div class="col-md-10">
							<div class="page-top-left">
								<table>
									  <tr> 
										  <td>
											  <select class="form-control" onchange="getChangeStockBroker(this.value)">
												  @if(isset($_GET['brocker']) && $_GET['brocker']!="all")
													@if($_GET['brocker']=="Angel")
													<option value="all">All Stock Brocker</option>
													  <option value="Angel" selected>Angel</option>
													  <option value="Zerodha">Zerodha</option>
													@endif
													@if($_GET['brocker']=="Zerodha")
													<option value="all">All Stock Brocker</option>
													  <option value="Angel">Angel</option>
													  <option value="Zerodha" selected>Zerodha</option>
													@endif
													@if($_GET['brocker']=="Upstrox")
													<option value="all">All Stock Brocker</option>
													  <option value="Angel">Angel</option>
													  <option value="Zerodha">Zerodha</option>
													  <option value="Upstrox" selected>Upstrox</option>
													@endif
												  @else
												  <option value="all">All Stock Brocker</option>
												  <option value="Angel">Angel</option>
												  <option value="Zerodha">Zerodha</option>
												  @endif
											  </select>
										  </td>
										  <td>
											  <select class="form-control" id="account" name="account" onchange="getPortfolioByAc(this.value)">
												  
												<?php
												if(isset($_GET['brocker'])){
												  echo '<option value="all">All Trade Account</option>';
												  if($_GET['brocker']=="Angel"){
													$ta = App\Models\TradingAccount::where('stock_brocker', $_GET['brocker'])->get();
												  }else if($_GET['brocker']=="Zerodha"){
													$ta = App\Models\TradingAccount::where('stock_brocker', $_GET['brocker'])->get();
												  }else{
													$ta = App\Models\TradingAccount::all();
												  }
												}else{
													echo '<option value="all">All Trade Account</option>';
													$ta = App\Models\TradingAccount::all();
												  }
												if($ta){
													foreach($ta as $allta){
													  if(isset($_GET['account']) && $_GET['account']!="all"){
														if(isset($_GET['account']) && $_GET['account']== $allta->login_id){
														  echo '<option value="'.$allta->login_id.'" selected>'.$allta->login_id.'</option>';
														}else{
														  echo '<option value="'.$allta->login_id.'">'.$allta->login_id.'</option>';
														}
													  }else{
														echo '<option value="'.$allta->login_id.'">'.$allta->login_id.'</option>';
													  }
														
													}
												}
												?>
											  </select>
										  </td>
										  <td>
											<a href="/trading/create"><button class="btn btn-info btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> New Account</button></a>
										  </td>
									  </tr>
								  </table>
							</div>
						</div>
						
						<div class="col-md-2">
							<div class="page-top-right">
								 <a href="/dashboard"><button class="btn btn-info btn-sm">Refresh</button></a>
							</div>
						</div>
						
				</div>
		</div>
		
			
		
            <div class="row text-dark">
              <div class="col-md-12 col-xl-12 mb-4">
                <div class="card card-body">
                    <div class="row">
                        
						<div class="col-md-4 col-xl-4">
							<div class="dashboard-list">
								<p><strong>Total Investment</strong></p>
								<h5 class="mb-1"><i class="fa fa-inr"></i> {{number_format($totalinvestment, 2)}}</h5>
							</div>
                        </div>
						
                        <div class="col-md-4 col-xl-4">
							<div class="dashboard-list">
								<p><strong>Current Value</strong></p>
								<h5 class="mb-1"><i class="fa fa-inr"></i> {{number_format($currentvalue,2)}}</h5>
							</div>
							
                        </div>
					
						
						<div class="col-md-4 col-xl-4">
							<div class="dashboard-list">
								<p><strong>Total P&amp;L</strong></p>
								
								<div class="dash-ammount mb-1">
									<div class="dash-updown"> 
										@if($totalinvestment!=0 && $currentvalue!=0)
											@if(str_contains(($currentvalue-$totalinvestment), '-'))
												<div class="updown-inner text-danger">
													<h5>
														<span><i class="fa fa-inr"></i> {{number_format(number_format((float)$currentvalue-$totalinvestment, 2, '.', ''), 2)}}</span>
														<span>
														<i class="fa fa-arrow-down" aria-hidden="true"></i> {{ number_format((float)(($currentvalue-$totalinvestment)/$totalinvestment)*100, 2, '.', '') }} %
														</span>
													</h5>
												</div>
												
											@else
												<div class="updown-inner text-green">
													<h5>
														<span ><i class="fa fa-inr"></i> {{number_format(number_format((float)$currentvalue-$totalinvestment, 2, '.', ''),2)}} </span>
														
														<span>
															<i class="fa fa-arrow-up    "></i> {{ number_format((float)(($currentvalue-$totalinvestment)/$totalinvestment)*100, 2, '.', '') }} %
														</span>
												  </h5>
											  </div>
											  
											@endif
										  @else
										  <br>
											<i class="fa fa-inr"></i> 0
										  @endif	
									</div>
								</div>
							 </div>
						</div>
						
						
                    </div>
                </div>
              </div>
            </div>

            
            <div class="row text-dark">
                <div class="col-md-12 col-xl-12 mb-3">
                  <div class="card card-body">
                      <div class="row">
                        
						<div class="col-md-4 col-xl-4">
							<div class="dashboard-list">
								<p><strong>Day P&amp;L</strong></p>
								
								<div class="dash-ammount mb-1">
									<div class="dash-updown"> 
										  @if($totalinvestment!=0 && $currentvalue!=0)
												@if(str_contains(($last_price-$close_price), '-'))
												<div class="updown-inner text-danger">
													<h5>
														<span><i class="fa fa-inr"></i> {{number_format(number_format((float)$last_price-$close_price, 2, '.', ''),2)}}</span>
														<span>
														<i class="fa fa-arrow-down" aria-hidden="true"></i> {{ number_format((float)(($last_price-$close_price)/$last_price)*100, 2, '.', '') }} %
														</span>
													</h5>
												</div>
												
											@else
												<div class="updown-inner text-green">
													<h5>
														<span ><i class="fa fa-inr"></i> {{number_format(number_format((float)$last_price-$close_price, 2, '.', ''),1)}}</span>
														
														<span>
															<i class="fa fa-arrow-up    "></i> {{ number_format((float)(($last_price-$close_price)/$last_price)*100, 2, '.', '') }} %
														</span>
												  </h5>
											  </div>
											  
											@endif
										  @else
										  <br>
											<i class="fa fa-inr"></i> 0
										  @endif	
									</div>
								</div>
							 </div>
                        </div>
						
                          <div class="col-md-4 col-xl-4">
							<div class="dashboard-list">
								<p><strong>Active Client</strong></p>
								<h5 class="mb-1">
									 <?php
                                    $countClinet=0;
                                     $clients = DB::select('select * from trading_account where is_active = ?', ["active"])   ;
                                     if($clients){
                                         foreach ($clients as $client) {
                                            $countClinet = $countClinet+1;
                                         }
                                         echo $countClinet;
                                     }
                                    ?>
								</h5>
							</div>
                          </div>
						  
                          <div class="col-md-4 col-xl-4">
                            <div class="dashboard-list">
                             <p><strong>Avalable Funds</strong></p>
                              <h5 class="mb-1"><i class="fa fa-inr"></i> {{number_format($availablecash, 2)}}</h5>
                            </div>
                          </div>
                          
                      </div>
                  </div>
                </div>
            </div>

            <div class="row text-dark">
                <div class="col-md-12 col-xl-12 mb-3">
                  <div class="card card-body">
                      <div class="row">
					  
                        <div class="col-md-3 col-xl-3">
                         <div class="dashboard-list">
                            <p><strong>Open Order</strong></p>
                            <h5 class="mb-1"> {{$openOrder}}</h5>
                          </div>
                        </div>
						
                          <div class="col-md-3 col-xl-3">
                            <div class="dashboard-list">
                              <p><strong>Cancel Order</strong><p>
                              <h5 class="mb-1"> {{$cancelOrder}}</h5>
                            </div>
                          </div>
						  
                          <div class="col-md-3 col-xl-3">
                           <div class="dashboard-list">
                              <p><strong>Executed Order</strong></p>
                              <h5 class="mb-1"> {{$excutOrder}}</h5>
                            </div>
                          </div>
						  
                          <div class="col-md-3 col-xl-3">
                           <div class="dashboard-list">
                              <p><strong>Rejected Order</strong></p>
                              <h5 class="mb-1"> {{$rejectedOrder}}</h5>
                            </div>
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

@endsection

@section('script')

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
     <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="/assets/js/tables-datatable.714838df.js"></script>
<script>
     
function getChangeStockBroker(str){
    location.href =  "/dashboard?brocker="+str;
}
function getPortfolioByAc(login_id){
  if(GetURLParameter("brocker")){
    location.href =  "/dashboard?brocker="+GetURLParameter("brocker")+"&account="+login_id;
  }else{
    location.href =  "/dashboard?account="+login_id;
  }
  // alert(login_id);
}

    "use strict";

document.addEventListener("DOMContentLoaded", function () {
    // document.getElementById('account').size = '4';
});

function GetURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
};
</script>
@endsection


