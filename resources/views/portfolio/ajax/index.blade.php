<h2 class="section-heading section-heading-ms">Portfolio</h2>
<table class="table table-striped table-bordered nowrap compact dataTable no-footer" id="datatable1">
    <thead>
        <tr role="row" class="alert alert-info">
            <th>Platform</th>
            <th>Acc ID</th>
            <th>Fund</th>
            <th>Total Investment</th>
            <th>Total Return</th>
            <th>Days Gain</th>
            <th>Action</th>
       </tr>
    </thead>
    <tbody id="holdings-body"> 
        <?php
       if($json){
            foreach ($json as $key => $values) {
                ?>
                <tr  class="header">
                    <td>{{$values['broker']}}</td>
                    <td>{{$values['loginId']}}</td>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> 
                        @if($values['broker']=="Angel")
                        <?php
                        $smart_api  = new \AngelBroking\SmartApi(
                            // OPTIONAL
                            //  "YOUR_ACCESS_TOKEN",
                            // "YOUR_REFRESH_TOKEN"
                        );
                        $smart_api ->GenerateSession($values['loginId'], $values['password']);    
                        $jsonData = $smart_api->GetRMS();
                        $preRMS = json_decode($jsonData, true);
                        echo number_format((float)$preRMS['response_data']['data']['availablecash'], 2, '.', '');
                        ?>
                        @endif
                    </td>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> {{$values['totalInvestment']}}</td>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> {{$values['totalReturn']}} <br>
                        @if(str_contains(($values['ltp']-$values['averageprice']), '-'))

                            <span class="text-danger">{{ number_format((float)((($values['ltp']-$values['averageprice'])/$values['averageprice'])*100), 2, '.', '') }}%</span><br>
                            <span class="text-danger"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format((float)($values['ltp']-$values['averageprice']), 2, '.', '') }}</span>

                        @else

                            <span class="text-primary">{{ number_format((float)((($values['ltp']-$values['averageprice'])/$values['averageprice'])*100), 2, '.', '') }}%</span><br>
                            <span class="text-primary"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format((float)($values['ltp']-$values['averageprice']), 2, '.', '') }}</span>

                        @endif
                    </td>
                    <td>{{$values['close']*$values['quantity']}}<br>
                        @if(str_contains(($values['close']-$values['averageprice']), '-'))

                            <span class="text-danger">{{ number_format((float)((($values['close']-$values['averageprice'])/$values['averageprice'])*100), 2, '.', '') }}%</span><br>
                            <span class="text-danger"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format((float)($values['close']-$values['averageprice']), 2, '.', '') }}</span>

                        @else

                            <span class="text-primary">{{ number_format((float)((($values['close']-$values['averageprice'])/$values['averageprice'])*100), 2, '.', '') }}%</span><br>
                            <span class="text-primary"><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format((float)($values['close']-$values['averageprice']), 2, '.', '') }}</span>

                        @endif</td>
                    <td>
                        <div>
                            <a href="/trade/order-create?login={{$values['loginId']}}&type=buy"><button class="btn btn-primary btn-sm mt-2">BUY</button></a>
                            <a href="/trade/order-create?login={{$values['loginId']}}&type=sell"><button class="btn btn-success btn-sm mt-2">SELL</button></a>
                            <button class="btn btn-danger btn-sm mt-2">KILL</button>
                        </div>
                    </td>
                </tr>
                <?php
                $y=0;
                foreach($values['ITEMS'] as $items){
                    $y=$y+1;
                    ?>
                    <tr class="hidetr">
                        <td>{{$items['broker']}}</td>
                        <td>{{$items['loginId']}}</td>
                        <td>{{$items['averageprice']}}</td>
                        <td>{{$items['ltp']}}</td>
                        <td>{{$items['totalReturn']}}<br>
                            @if(str_contains(($items['ltp']-$items['averageprice']), '-'))

                                <span class="text-danger">{{ number_format((float)((($items['ltp']-$items['averageprice'])/$items['averageprice'])*100), 2, '.', '') }}%</span><br>
                                <span class="text-danger">{{ number_format((float)($items['ltp']-$items['averageprice']), 2, '.', '') }}</span>

                            @else

                                <span class="text-primary">{{ number_format((float)((($items['ltp']-$items['averageprice'])/$items['averageprice'])*100), 2, '.', '') }}%</span><br>
                                <span class="text-primary">{{ number_format((float)($items['ltp']-$items['averageprice']), 2, '.', '') }}</span>

                            @endif
                        </td>
                        <td>{{$items['close']*$items['quantity']}}
                            <br>
                            @if(str_contains(($items['close']-$items['averageprice']), '-'))

                                <span class="text-danger">{{ number_format((float)((($items['close']-$items['averageprice'])/$items['averageprice'])*100), 2, '.', '') }}%</span><br>
                                <span class="text-danger">{{ number_format((float)($items['close']-$items['averageprice']), 2, '.', '') }}</span>

                            @else

                                <span class="text-primary">{{ number_format((float)((($items['close']-$items['averageprice'])/$items['averageprice'])*100), 2, '.', '') }}%</span><br>
                                <span class="text-primary">{{ number_format((float)($items['close']-$items['averageprice']), 2, '.', '') }}</span>

                            @endif
                        </td>
                        <td>
                            <div>
                                <a href="/trade/order-create?login={{$values['loginId']}}&symbol={{$items['tradingsymbol']}}&type=buy"><button class="btn btn-primary btn-sm mt-2">BUY</button></a>
                                <a href="/trade/order-create?login={{$values['loginId']}}&symbol={{$items['tradingsymbol']}}&type=sell"><button class="btn btn-success btn-sm mt-2">SELL</button></a>
                                <button class="btn btn-danger btn-sm mt-2">KILL</button> 
                            </div>
                        </td>
                    </tr>
                    <?php
                    # code...
                }
            }
        }
        ?>
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
     <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="/assets/js/tables-datatable.714838df.js"></script>
<script>
    "use strict";
    $(document).ready(function() {
    $('#datatable1').DataTable( {
        "lengthMenu": [[100, 250, 500, -1], [100, 250, 500, "All"]]
    } );
} );
document.addEventListener("DOMContentLoaded", function () {
    // document.getElementById('account').size = '4';
    lengthMenu: [[50, 100, 500, -1], [50, 100, 500, "All"]],
});
    
</script>
<script>
    $(document).ready(function() {
        window.setTimeout(function(){
             $.get('/jqajax/portfolio', function(result){
                $('#card-body').html("");
                $('#card-body').html(result);
             });   
        }, 15000);


//Fixing jQuery Click Events for the iPad
var ua = navigator.userAgent,
event = (ua.match(/iPad/i)) ? "touchstart" : "click";
if ($('.table').length > 0) {
$('.table .header').on(event, function() {
  $(this).toggleClass("active", "").nextUntil('.header').css('display', function(i, v) {
    return this.style.display === 'table-row' ? 'none' : 'table-row';
  });
});
}
})
</script>