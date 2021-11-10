
<?php
$sysmbol = $_GET['symbol'];
$exchange = $_GET['exchange'];
$ta = App\Models\TradingAccount::where('login_id', 'XA2065')->first();
if($ta){
  $curl=curl_init();
  curl_setopt($curl,CURLOPT_URL,'https://api.kite.trade/quote?i='.$exchange.':'.$sysmbol);
  curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,2);
  curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json', 'X-Kite-Version: 3', 'Authorization:token '.ENV('KITE_KEY').':'.$ta->access_token));
  $result = curl_exec($curl);
  curl_close($curl);
  if (empty($result)){
      print "Nothing returned from url.<p>";
        
  }
  else{
    $finalData = json_decode($result)->data;
    $f = $exchange.':'.$sysmbol;
    ?>
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">{{$sysmbol}} <span style="font-size: 12px;">{{$exchange}}</span>
      </h5>
      <span style="float: right!important">
        <i class="fa fa-inr" aria-hidden="true"></i> {{$finalData->$f->last_price}} 
          @if(str_contains(($finalData->$f->average_price-$finalData->$f->last_price), '-'))
            <span class="text-danger">{{ number_format($finalData->$f->average_price-$finalData->$f->last_price, 2)}}
           </span>
          @else
            <span class="text-green">+{{number_format($finalData->$f->average_price-$finalData->$f->last_price, 2)}} </span>
          @endif
      </span>
      {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
    </div>
    <div class="modal-body">
      <div class="row depth-table">
        <div class="col-sm-6">
          <p>Bid Price</p>
          <table style="width: 100%">
            <thead>
              <tr>
                <th>Price</th>
                <th>Orders</th>
                <th>Qty</th>
              </tr>
            </thead>
          <?php
          foreach ($finalData->$f->depth->buy as  $buy) {
            ?>
            <tbody>
              <tr>
                <td class="text-green"><a href="/trade/order-create?symbol={{$sysmbol}}&price={{$buy->price}}&type=buy">{{$buy->price}}</a></td>
                <td class="text-green">{{$buy->price}}</td>
                <td class="text-green">{{$buy->price}}</td>
              </tr>
            </tbody>
            <?php
          }  
          ?>
          <tfoot>
            <tr>
              <td>Total</td>
              <td colspan="2"><span class="text-right text-green" style="float: right!important;">{{$finalData->$f->buy_quantity}}</span></td>
            </tr>
          </tfoot>
          </table>
        </div>
        {{-- <div class="col-sm-2"></div> --}}
        <div class="col-sm-6">
          <p>Ask Price</p>
          <table style="width: 100%">
            <tr>
              <th>Price</th>
              <th>Orders</th>
              <th>Qty</th>
            </tr>
          <?php
          foreach ($finalData->$f->depth->sell as $sell) {
            ?>
            <tr>
              <td class="text-danger"><a href="/trade/order-create?symbol={{$sysmbol}}&price={{$sell->price}}&type=sell">{{$sell->price}}</a></td>
              <td class="text-danger">{{$sell->price}}</td>
              <td class="text-danger">{{$sell->price}}</td>
            </tr>
            <?php
          }  
          ?>
           <tfoot>
            <tr>
              <td>Total</td>
              <td colspan="2"><span class="text-right text-danger" style="float: right">{{$finalData->$f->sell_quantity}}</span></td>
            </tr>
          </tfoot>
          </table>
        </div>
        <hr>
        <div class="col-sm-6">
          <div class="row">
            <div class="col-sm-6">Open</div>
            <div class="col-sm-6">{{$finalData->$f->ohlc->open}}</div>
            <div class="col-sm-6">High</div>
            <div class="col-sm-6">{{$finalData->$f->ohlc->high}}</div>
            <div class="col-sm-6">Low</div>
            <div class="col-sm-6">{{$finalData->$f->ohlc->low}}</div>
            <div class="col-sm-6">Close</div>
            <div class="col-sm-6">{{$finalData->$f->ohlc->close}}</div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="row">
            <div class="col-sm-6">ATP</div>
            <div class="col-sm-6">{{$finalData->$f->average_price}}</div>
            <div class="col-sm-6">Volume</div>
            <div class="col-sm-6">{{$finalData->$f->volume}}</div>
            <div class="col-sm-6">LCL</div>
            <div class="col-sm-6">{{$finalData->$f->lower_circuit_limit}}</div>
            <div class="col-sm-6">UCL</div>
            <div class="col-sm-6">{{$finalData->$f->upper_circuit_limit}}</div>
          </div>
        </div>
        
      </div>

    </div>
    <div class="modal-footer">
      <div class="col-sm-6"></div>
      <div class="col-sm-6">
        <a href="/trade/order-create?symbol={{$sysmbol}}&price={{$finalData->$f->last_price}}&type=buy"><button type="button" class="btn btn-primary">Buy</button></a>
        <a href="/trade/order-create?symbol={{$sysmbol}}&price={{$finalData->$f->last_price}}&type=sell"><button type="button" class="btn btn-danger">Sell</button></a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      
    </div>
    <?php
      
  }
}
?>

