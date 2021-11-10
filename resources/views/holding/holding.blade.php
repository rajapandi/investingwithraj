@extends('layout.app')

@section('content')
<style>
td.details-control {
    background: url('https://cdn.rawgit.com/DataTables/DataTables/6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('https://cdn.rawgit.com/DataTables/DataTables/6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_close.png') no-repeat center center;
}
</style>
<div class="page-holder bg-gray-100">
    <div class="container-fluid px-lg-4 px-xl-5">
      <section class="mb-4 mb-lg-5">
            
            <div class="row text-dark">
                <div class="col-md-12 col-xl-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="section-heading section-heading-ms">Holding</h2>
                            <table class="display" id="example"   cellspacing="0" width="100%">
				                <thead class="alert alert-info" class="th" > 
					                <tr role="row">
					                    <th>SR</th>
					                    <th>Stocks</th>
                                        <th>LTP</th>
					                    <th>Quantity</th>
					                    <th>Total Investment</th>
					                    <th>Total Return</th>
					                    <th>Days Return</th>
                                        <th>Action</th>
					               </tr>
				                </thead>
				                <tbody > 
				                
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

<script>
$(document).ready(function() {
    var table = $('#example').DataTable( {
        "ajax": "/ajax/data/holding",
        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "name" },
            { "data": "position" },
            { "data": "office" },
            { "data": "salary" }
        ],
        "order": [[1, 'asc']]
    } );
});
   
</script>
@endsection


