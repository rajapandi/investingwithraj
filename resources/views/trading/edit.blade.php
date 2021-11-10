@extends('layout.app')

@section('content')

    <div class="page-holder bg-gray-100">
        <div class="container-fluid px-lg-4 px-xl-5">
          <div class="page-header d-flex justify-content-between align-items-center">
            <h1 class="page-heading">Trading Accounts</h1>
          </div>
          <section class="mb-5">
            <div class="card">
              <div class="card-body">
                  <div class="row">
                      <?php
                      $td = App\Models\TradingAccount::where('id', Request::segment(3))->first();
                      ?>
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                          <span style="color:red;">{{Session::get('msg')}}</span>
                        <form action="/trading/update" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{Request::segment(3)}}">
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Stock Broker</label>
                            <select class="form-control" name="stock_brocker" id="stock_brocker" onchange="getFindPlatform(this.value)"  required>
                                @if($td->stock_brocker=="Angel")
                                    <option value="Angel">Angel</option>
                                    <option value="Zerodha">Zerodha</option>
                                @else
                                    <option value="Zerodha">Zerodha</option>
                                    <option value="Angel">Angel</option>
                                @endif


                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Trading Platform</label>
                            <select class="form-control" name="trading_platform" id="trading_platform" required>
                                @if($td->trading_platform=="SMART_API")
                                    <option value="SMART_API">SMART_API</option>
                                    <option value="KITE">KITE</option>
                                @else
                                    <option value="KITE">KITE</option>
                                    <option value="SMART_API">SMART_API</option>
                                @endif
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Name</label>
                            <input class="form-control" value="{{$td->name}}" name="name" type="text" placeholder="Name" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Mobile</label>
                            <input class="form-control" value="{{$td->mobile}}" name="mobile" type="number" placeholder="Mobile" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Email Id</label>
                            <input class="form-control" value="{{$td->email_id}}" name="email_id" type="email" placeholder="Email ID" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">TPIN</label>
                            <input class="form-control" value="{{$td->tpin}}" name="tpin" type="text" placeholder="TPIN" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Trading Platform Login ID</label>
                            <input class="form-control" name="login_id" value="{{$td->login_id}}" type="text" placeholder="Trading Platform Login ID" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Password</label>
                            <input class="form-control" name="password" id="password" value="{{$td->password}}" type="password" placeholder="Password" required>
                            <input type="checkbox" id="togglePassword"> Show Password
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Security Answer (2FA)</label>
                            <input type="password" class="form-control" value="{{$td->security_ans}}" name="security_ans" id="security_ans" placeholder="Security Answer (2FA)" disabled>
                            <input type="checkbox" id="toggle2FA"> Show Security
                          </div>
                          <div class="mb-3" style="display:none;">
                            <label class="form-label text-uppercase">API Key</label>
                            <input class="form-control" name="api_key" id="api_key" value="{{$td->api_key}}" type="text" placeholder="API Key" >
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">is Active</label>
                            <select class="form-control" name="is_active" id="is_active">
                              @if($td->is_active=="active")
                              <option value="active">Active</option>
                              <option value="inactive">Inactive</option>
                              @else
                              <option value="inactive">Inactive</option>
                              <option value="active">Active</option>
                              @endif
                            </select>
                          </div>
                          <div class="mb-3">
                            <button class="btn btn-primary" type="submit" style="float:right;">Save</button>
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
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script>
      $(document).ready(function() {
        $("#stock_brocker").change(function() {
            if ($(this).val() === 'Zerodha'){
              $('#api_key').prop('disabled', true);
         $('#security_ans').prop('disabled', false);
            }
        });
    });
    </script>
 <script type="text/javascript">

  function getFindPlatform(str){
     if(str=="Zerodha"){
         $('#api_key').prop('disabled', true);
         $('#security_ans').prop('disabled', false);
     }if(str=="Angel"){
         $('#security_ans').prop('disabled', true);
         $('#api_key').prop('disabled', false);
     }
     $.get('/brocker/getFindPlatform', {str:str}, function(result){
         $('#trading_platform').html("");
         $('#trading_platform').append(result);
     });
 }



      // Optional
      Prism.plugins.NormalizeWhitespace.setDefaults({
      'remove-trailing': true,
      'remove-indent': true,
      'left-trim': true,
      'right-trim': true,
      });

    </script>
@endsection

