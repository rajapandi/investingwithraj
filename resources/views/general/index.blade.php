@extends('layout.app')

@section('content')
<style>

</style>
    <div class="page-holder bg-gray-100">
        <div class="container-fluid px-lg-4 px-xl-5">
          <?php
          $gs = App\Models\GeneralSetting::where('id', 1)->first();
          ?>
          <section class="mb-5">
            <div class="card">
              <div class="card-body">
                  <div class="row">
                      <div class="col-md-12">
                        <h4 class="text-primary">Trading</h4>
                          <span style="color:red;">{{Session::get('msg')}}</span>
                        <form action="/setting/general" method="post"> 
                            @csrf
                              <input type="hidden" name="id" id="id" value="{{$gs->id}}">
                            <div class="card-block">
                              <div class="row mb-2 py-2 border border-warning rounded">
                                  <div class="col font-weight-bold text-dark">
                                    <input type="hidden" id="Trading-id-0" value="">
                                    <input type="hidden" id="Trading-key-0" value="DEFAULT_TRADE_TYPE">
                                    Default Trade Type
                                  </div>
                                  <div class="col text-dark">
                                      <select class="form-control" name="trade_type" id="trade_type">
                                        @if($gs->trade_type=="BUY")
                                          <option value="BUY" selected="selected">BUY</option>
                                          <option value="SELL">SELL</option>
                                        @else  
                                          <option value="SELL"selected="selected">SELL</option>
                                          <option value="BUY" >BUY</option>
                                        @endif
                                      </select>
                                  </div>
                                  <div class="col-sm-6 text-muted">
                                    The default trade type to use on order entry screen
                                  </div>
                              </div>
                              <div class="row mb-2 py-2 border border-warning rounded">
                                  <div class="col font-weight-bold text-dark">
                                    <input type="hidden" id="Trading-id-1" value="">
                                    <input type="hidden" id="Trading-key-1" value="DEFAULT_VARIETY">
                                    Default Variety
                                  </div>
                                  <div class="col text-dark">
                                      <select class="form-control" name="variety" id="variety">
                                        @if($gs->variety=="NORMAL")
                                          <option value="NORMAL" selected="selected">NORMAL</option>
                                          <option value="STOPLOSS">STOPLOSS</option>
                                          <option value="AMO">AMO</option>
                                          <option value="ROBO">ROBO</option>
                                        @endif
                                        @if($gs->variety=="STOPLOSS")
                                          <option value="STOPLOSS" selected="selected">STOPLOSS</option>
                                          <option value="NORMAL">NORMAL</option>
                                          <option value="AMO">AMO</option>
                                          <option value="ROBO">ROBO</option>
                                        @endif
                                        @if($gs->variety=="AMO")
                                        <option value="AMO" selected="selected">AMO</option>
                                          <option value="STOPLOSS">STOPLOSS</option>
                                          <option value="NORMAL">NORMAL</option>
                                          <option value="ROBO">ROBO</option>
                                        @endif
                                        @if($gs->variety=="ROBO")
                                        <option value="ROBO" selected="selected">ROBO</option>
                                          <option value="STOPLOSS" >STOPLOSS</option>
                                          <option value="NORMAL">NORMAL</option>
                                          <option value="AMO">AMO</option>
                                        @endif
                                      </select>
                                  </div>
                                  <div class="col-sm-6 text-muted">
                                    The default variety to use on order entry screen
                                  </div>
                              </div>
                              <div class="row mb-2 py-2 border border-warning rounded">
                                  <div class="col font-weight-bold text-dark">
                                    <input type="hidden" id="Trading-id-2" value="">
                                    <input type="hidden" id="Trading-key-2" value="DEFAULT_PRODUCT_TYPE">
                                    Default Product Type
                                  </div>
                                  <div class="col text-dark">
                                      <select class="form-control" name="product_type" id="product_type">
                                        @if($gs->product_type=="INTRADAY")
                                          <option value="INTRADAY" selected="selected">INTRADAY</option>
                                          <option value="DELIVERY">DELIVERY</option>
                                          <option value="MARGIN">MARGIN</option>
                                          <option value="BO">BO</option>
                                          <option value="CARRYFORWARD">CARRYFORWARD</option>
                                        @endif
                                        @if($gs->product_type=="DELIVERY")
                                          <option value="INTRADAY">INTRADAY</option>
                                          <option value="DELIVERY"  selected="selected">DELIVERY</option>
                                          <option value="MARGIN">MARGIN</option>
                                          <option value="BO">BO</option>
                                          <option value="CARRYFORWARD">CARRYFORWARD</option>
                                        @endif
                                        @if($gs->product_type=="MARGIN")
                                          <option value="INTRADAY">INTRADAY</option>
                                          <option value="DELIVERY" >DELIVERY</option>
                                          <option value="MARGIN"  selected="selected">MARGIN</option>
                                          <option value="BO">BO</option>
                                          <option value="CARRYFORWARD">CARRYFORWARD</option>
                                        @endif
                                        @if($gs->product_type=="BO")
                                          <option value="INTRADAY">INTRADAY</option>
                                          <option value="DELIVERY" >DELIVERY</option>
                                          <option value="MARGIN" >MARGIN</option>
                                          <option value="BO" selected="selected">BO</option>
                                          <option value="CARRYFORWARD">CARRYFORWARD</option>
                                        @endif
                                        @if($gs->product_type=="CARRYFORWARD")
                                          <option value="INTRADAY">INTRADAY</option>
                                          <option value="DELIVERY" >DELIVERY</option>
                                          <option value="MARGIN">MARGIN</option>
                                          <option value="BO">BO</option>
                                          <option value="CARRYFORWARD" selected="selected">CARRYFORWARD</option>
                                        @endif
                                      </select>
                                  </div>
                                  <div class="col-sm-6 text-muted">
                                    The default product type to use on order entry screen
                                  </div>
                              </div>
                              <div class="row mb-2 py-2 border border-warning rounded">
                                  <div class="col font-weight-bold text-dark">
                                    <input type="hidden" id="Trading-id-3" value="">
                                    <input type="hidden" id="Trading-key-3" value="DEFAULT_QUANTITY">
                                    Default Quantity
                                  </div>
                                  <div class="col text-dark">
                                      <input type="number" class="form-control" name="quantity" id="quantity" value="{{$gs->quantity}}" min="1" max="100000" step="1">
                                  </div>
                                  <div class="col-sm-6 text-muted">
                                    The default quantity to use on order entry screen
                                  </div>
                              </div>
                              
                              <div class="row justify-content-center">
                                <button type="submit" class="btn btn-primary" id="Trading-save" style="justify-content: center!important;width: 15%;">Save</button>
                              </div>
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

@endsection

