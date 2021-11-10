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
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                          <span style="color:red;">{{Session::get('msg')}}</span>
                        <form action="/trading/store" method="post"> 
                            @csrf
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Stock Broker</label>
                            <select class="form-control" name="stock_brocker" onchange="getFindPlatform(this.value)" required>
                                <option value="Angel">Angel</option>
                                <option value="Zerodha">Zerodha</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Trading Platform</label>
                            <select class="form-control" name="trading_platform" id="trading_platform" required>
                                <option value="SMART_API">SMART_API</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Name</label>
                            <input class="form-control" name="name" type="text" placeholder="Name" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Mobile</label>
                            <input class="form-control" name="mobile" type="number" placeholder="Mobile" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Email Id</label>
                            <input class="form-control" name="email_id" type="email" placeholder="Email ID" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">TPIN</label>
                            <input class="form-control" name="tpin" type="text" placeholder="TPIN" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Trading Platform Login ID</label>
                            <input class="form-control" name="login_id" type="text" placeholder="Trading Platform Login ID" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Password</label>
                            <input class="form-control" name="password" type="password" id="password" placeholder="Password" required>
                            <input type="checkbox" id="togglePassword"> Show Password 
                            {{-- <i class="fa fa-eye-slash" id="togglePassword"></i> --}}
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Security Answer (2FA)</label>
                            <input type="password" class="form-control" name="security_ans" id="security_ans" placeholder="Security Answer (2FA)" disabled>
                            <input type="checkbox" id="toggle2FA"> Show Password 
                          </div>
                          <div class="mb-3" style="display:none;">
                            <label class="form-label text-uppercase">API Key (User Key)</label>
                            <input type="hidden" class="form-control" name="api_key" id="api_key" type="text" placeholder="Trading Platform API Key (User Key)" >
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

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
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

