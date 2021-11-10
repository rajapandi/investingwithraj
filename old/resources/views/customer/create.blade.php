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
                        <form action="/customer/store" method="post"> 
                            @csrf
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Customer Name</label>
                            <input class="form-control" name="name" type="text" placeholder="Customer Name">
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Customer Email</label>
                            <input class="form-control" name="email" type="email" placeholder="Email Address">
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Customer Mobile</label>
                            <input class="form-control" name="mobile" type="number" placeholder="Customer Mobile">
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Customer Address</label>
                            <textarea class="form-control" name="address" placeholder="Customer Address"></textarea>
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Customer Code</label>
                            <input class="form-control" name="customer_code" type="text" placeholder="Customer Code">
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-uppercase">Password</label>
                            <input class="form-control" name="password" type="password" placeholder="Customer Password">
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

