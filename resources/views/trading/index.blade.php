@extends('layout.app')

@section('content')

    <div class="page-holder bg-gray-100">
        <div class="container-fluid px-lg-4 px-xl-5">
          <!-- Page Header-->
          <div class="page-header d-flex justify-content-between align-items-center">
            <h1 class="page-heading">Trading Accounts</h1>
            <div><a class="btn btn-primary text-uppercase" href="/trading/create"> <i class="fa fa-plus me-2"> </i>Create</a></div>
          </div>
          <section class="mb-5">
            <div class="card">
              <div class="card-body">
                  <span class="color:red;">{{Session::get('msg')}}</span>
                <table class="table table-hover align-middle" id="postDatatable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Login Id</th>
                      <th>Broker</th>
                      <th>Platform</th>
                      <th>Validation</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $api_key="";
                      $x=0;
                      $cd = DB::select('select * from trading_account');
                      if($cd){
                          foreach($cd as $allcd){
                              $x= $x+1;
                              $api_key = $allcd->api_key;
                              ?>
                                <tr>
                                  <td>{{$x}}</td>
                                  <td>{{$allcd->name}}</td>
                                  <td><a href="/portfolio?account={{$allcd->login_id}}">{{$allcd->login_id}}</a></td>
                                  <td>{{$allcd->stock_brocker}}</td>
                                  <td>{{$allcd->trading_platform}}</td>
                                  <td>
                                      @if($allcd->is_active=="active")
                                        <span class="label label-success">Success</span>
                                      @else
                                        <span class="label label-danger">Failed</span>
                                      @endif
                                    </td>
                                  <td>
                                    @if($allcd->stock_brocker=="Zerodha" && $allcd->trading_platform=="KITE")
                                        @if($allcd->api_key!="" || $allcd->api_key!=null)

                                            <span id="totpData">
                                              <?php echo file_get_contents('http://buildercrm.whizzact.com/authenticator/index.php?key='.$allcd->api_key) ?>
                                              <span id="timer"></span>
                                              <button class="btn btn-success btn-sm" onclick="enterKeyForTOTP('{{$allcd->login_id}}')">TOTP</button>&nbsp;&nbsp;&nbsp;
                                            </span>
                                        @else
                                            <button class="btn btn-success btn-sm" onclick="enterKeyForTOTP('{{$allcd->login_id}}')">TOTP</button>
                                        @endif
                                    @endif

                                    <a href="/trading/edit/{{$allcd->id}}"><button class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></button></a>&nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-danger btn-sm" onclick="getDeleteTradingAccount({{$allcd->id}})"><i class="fa fa-trash"></i></button>
                                    @if($allcd->is_active=="inactive")
                                    <button class="btn btn-danger btn-sm" onclick="getInactiveUser({{$allcd->id}})">Inactive</button>
                                    @else
                                    <button class="btn btn-success btn-sm" onclick="getActiveUser({{$allcd->id}})">Active</button>
                                    @endif
                                  </td>
                                </tr>
                              <?php
                          }
                      }
                      ?>
                  </tbody>
                </table>
              </div>
            </div>
          </section>
        </div>

      </div>
    </div>

    <div class="modal fade" id="MySecondmodal">
      <div class="modal-dialog">
        <div class="modal-content" id="model_content">
          <div class="modal-body">
            <p>Waiting...</p>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
     <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="/assets/js/tables-datatable.714838df.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
 <script type="text/javascript">


    var intervalId = window.setInterval(function(){
      var key = '{{$api_key}}';
      if(key!=null || key!=""){
        $.get('http://buildercrm.whizzact.com/authenticator/index.php', {key:key}, function(result){
            if(result=="FAILED"){

            }else{
            var minute = 0;
            var sec = 30;
            setInterval(function() {
                document.getElementById("timer").innerHTML =  sec;
                sec--;
                if (sec == 00) {
                minute --;
                sec = 30;
                if (minute == 0) {
                    minute = 5;
                }
                }
            }, 1000);
            $('#totpData').html("");
            $('#totpData').html(result);
            }
        });
        }
    }, 30000);


      // Optional
      Prism.plugins.NormalizeWhitespace.setDefaults({
      'remove-trailing': true,
      'remove-indent': true,
      'left-trim': true,
      'right-trim': true,
      });



    </script>

@endsection

