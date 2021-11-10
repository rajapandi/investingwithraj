@extends('layout.app')

@section('content')

    <div class="page-holder bg-gray-100">
        <div class="container-fluid px-lg-4 px-xl-5">
          <!-- Page Header-->
          <div class="page-header d-flex justify-content-between align-items-center">
            <h1 class="page-heading">Customers</h1>
            <div><a class="btn btn-primary text-uppercase" href="/customer/create"> <i class="fa fa-plus me-2"> </i>Add new</a></div>
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
                      <th>Email</th>
                      <th>Mobile</th>
                      <th>Customer Code</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $x=0;
                      $cd = DB::select('select * from customers');
                      if($cd){
                          foreach($cd as $allcd){
                              $x= $x+1;
                              ?>
                                <tr>
                                  <td>{{$x}}</td>
                                  <td>{{$allcd->name}}</td>
                                  <td>{{$allcd->email}}</td>
                                  <td>{{$allcd->mobile}}</td>
                                  <td>{{$allcd->customer_code}}</td>
                                  <td>
                                      <a href="/customer/edit/{{$allcd->id}}"><button class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></button></a>&nbsp;&nbsp;&nbsp;
                                  <button class="btn btn-danger btn-sm" onclick="getDeleteCustomer({{$allcd->id}})"><i class="fa fa-trash"></i></button></td>
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

