@extends('layout.app')

@section('content')

    <div class="page-holder bg-gray-100">
        <div class="container-fluid px-lg-4 px-xl-5">
          
          <section class="mb-5">
            <div class="card">
              <div class="card-body">
                  <div class="row">
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                          <span style="color:red;">{{Session::get('msg')}}</span>
                        <form action="/group/store" method="post"> 
                            @csrf
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Group Name</label>
                            <input class="form-control" name="group_name" type="text" placeholder="Group Name">
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Description</label>
                            <textarea class="form-control" name="description" placeholder="Description"></textarea>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Account</label>
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <th>Add</th>
                                    <th>Trading Acc</th>
                                    <th>Brocker</th>
                                </tr>
                                <?php
                                $ta = App\Models\TradingAccount::all();
                                if($ta){
                                    foreach($ta as $allta){
                                        ?>
                                        <tr>
                                            <td><input type="checkbox" name="accountCheckbox[]" value="{{$allta->login_id}}"></td>
                                            <td>{{$allta->login_id}}</td>
                                            <td>{{$allta->stock_brocker}}</td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </table>
                          </div>
                          
                          <div class="mb-3">       
                            <button class="btn btn-primary" type="submit">Create</button>
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
      // Optional
      Prism.plugins.NormalizeWhitespace.setDefaults({
      'remove-trailing': true,
      'remove-indent': true,
      'left-trim': true,
      'right-trim': true,
      });
          
    </script>
@endsection

