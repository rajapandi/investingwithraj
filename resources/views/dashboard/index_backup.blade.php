@extends('layout.app')

@section('content')

<div class="page-holder bg-gray-100">
    <div class="container-fluid px-lg-4 px-xl-5">
      <section class="mb-4 mb-lg-5">
        <h2 class="section-heading section-heading-ms mb-4 mb-lg-5">
            <table>
                <tr>
                    <td>
                        <select class="form-control" onchange="getChangeStockBroker(this.value)">
                            <option value="all">All Stock Brocker</option>
                            <option value="Angel">Angel</option>
                            <option value="Kite">Kite</option>
                            <option value="Upstrox">Upstrox</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" id="account" onchange="getPortfolio(this.value)">
                            <option value="all">All Trade Account</option>
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
                </tr>
            </table>
        </h2>
        <div id="profileData">
            <div class="row text-dark">
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card credit-card bg-hover-gradient-indigo">
                  <div class="credict-card-content">
                    <div class="fw-bold">NET</div>
                    <div class="credict-card-bottom">
                      <h4 class="mb-1"><i class="fa fa-inr"></i> {{$net}}</h4>
                    </div>
                  </div><a class="stretched-link" href="#"></a>
                </div>
              </div>
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card credit-card bg-hover-gradient-blue">
                  <div class="credict-card-content">
                    <div class="fw-bold">Available Funds</div>
                    <div class="credict-card-bottom">
                      <h4 class="mb-1"><i class="fa fa-inr"></i> {{$availablecash}}</h4>
                    </div>
                  </div><a class="stretched-link" href="#"></a>
                </div>
              </div>
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card credit-card bg-hover-gradient-green">
                  <div class="credict-card-content">
                    <div class="fw-bold">Available Day Payin</div>
                    <div class="credict-card-bottom">
                      <h4 class="mb-1"><i class="fa fa-inr"></i> {{$availableintradaypayin}}</h4>
                    </div>
                  </div><a class="stretched-link" href="#"></a>
                </div>
              </div>
            </div>
            
            <div class="row text-dark">
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card credit-card bg-hover-gradient-green">
                  <div class="credict-card-content">
                    <div class="fw-bold">Available available Limit Margin</div>
                    <div class="credict-card-bottom">
                      <h4 class="mb-1"><i class="fa fa-inr"></i> {{$availablelimitmargin}}</h4>
                    </div>
                  </div><a class="stretched-link" href="#"></a>
                </div>
              </div>
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card credit-card bg-hover-gradient-indigo">
                  <div class="credict-card-content">
                    <div class="fw-bold">M2M Unrealized</div>
                    <div class="credict-card-bottom">
                      <h4 class="mb-1"><i class="fa fa-inr"></i> {{$m2munrealized}}</h4>
                    </div>
                  </div><a class="stretched-link" href="#"></a>
                </div>
              </div>
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card credit-card bg-hover-gradient-blue">
                  <div class="credict-card-content">
                    <div class="fw-bold">M2M Realized</div>
                    <div class="credict-card-bottom">
                      <h4 class="mb-1"><i class="fa fa-inr"></i> {{$m2mrealized}}</h4>
                    </div>
                  </div><a class="stretched-link" href="#"></a>
                </div>
              </div>
            </div>
            
            <div class="row text-dark">
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card credit-card bg-hover-gradient-blue">
                  <div class="credict-card-content">
                    <div class="fw-bold">Utilized Turnover</div>
                    <div class="credict-card-bottom">
                      <h4 class="mb-1"><i class="fa fa-inr"></i> {{$utilisedturnover}}</h4>
                    </div>
                  </div><a class="stretched-link" href="#"></a>
                </div>
              </div>
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card credit-card bg-hover-gradient-green">
                  <div class="credict-card-content">
                    <div class="fw-bold">Utilized Debits</div>
                    <div class="credict-card-bottom">
                      <h4 class="mb-1"><i class="fa fa-inr"></i> {{$utiliseddebits}}</h4>
                    </div>
                  </div><a class="stretched-link" href="#"></a>
                </div>
              </div>
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card credit-card bg-hover-gradient-indigo">
                  <div class="credict-card-content">
                    <div class="fw-bold">Utilised Span</div>
                    <div class="credict-card-bottom">
                      <h4 class="mb-1"><i class="fa fa-inr"></i> {{$utilisedspan}}</h4>
                    </div>
                  </div><a class="stretched-link" href="#"></a>
                </div>
              </div>
            </div>
        </div>
        
            <div class="row text-dark">
                <div class="col-md-12 col-xl-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-bordered nowrap compact dataTable no-footer" id="datatable1">
				                <thead>
					                <tr role="row">
					                    <th>Trd Acc</th>
					                    <th>Symbol</th>
					                    <th>Quantity</th>
					                    <th>Exchange</th>
					                    <th>T1 Qty.</th>
					                    <th>PnL</th>
					                    <th>Product</th>
					                    <th>ISIN</th>
					                    <!--<th>Collateral Qty.</th>-->
					                    <!--<th>Collateral Type</th>-->
					                    <th>Haircut</th>
					                    <th>Avg. Price</th>
					                    <th>Platform</th>
					                    <th>Broker</th>
					               </tr>
				                </thead>
				                <tbody id="holdings-body"> 
				                 <?php
                                    if($GetHolding){
                                    //   $preData = json_decode($GetHolding, true);
                                    //   $GetHolding['data'];
                                        // if($GetHolding['data']!=null){
                                          foreach ($GetHolding as $key => $value) {
                                              foreach($value['data'] as $values){
                                                  ?>
                                                  <tr>
                                                      <td>{{$values['loginId']}}</td>
                                                      <td>{{$values['tradingsymbol']}}</td>
                                                      <td>{{$values['quantity']}}</td>
                                                      <td>{{$values['exchange']}}</td>
                                                      <td>{{$values['t1quantity']}}</td>
                                                      <td>{{$values['profitandloss']}}</td>
                                                      <td>{{$values['product']}}</td>
                                                      <td>{{$values['isin']}}</td>
                                                      <!--<td>{{$values['collateralquantity']}}</td>-->
                                                      <!--<td>{{$values['collateraltype']}}</td>-->
                                                      <td>{{$values['haircut']}}</td>
                                                      <td>{{number_format((float)$values['averageprice'], 2, '.', '')}}</td>
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


