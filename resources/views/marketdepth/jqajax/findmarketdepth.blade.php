<?php
$ta = App\Models\TradingAccount::where('login_id', 'CS0523')->first();
$md = App\Models\MarketDepth::where('is_active', 'active')->orderBY('id', 'desc')->limit(8)->get();
if($md){
    foreach ($md as $allmd) {
        $exchange = $allmd->exchange;
        $sysmbol = $allmd->symbol;

            $curl=curl_init();
            curl_setopt($curl,CURLOPT_URL,'https://api.kite.trade/quote?i='.$allmd->exchange.':'.$allmd->symbol);
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
                $f = $allmd->exchange.':'.$allmd->symbol;
            ?>
            <div class="col-sm-6">
                <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">{{$sysmbol}} <span style="font-size: 10px;">{{$exchange}}</span>
                </h6>
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
                    <p>Bid</p>
                    <table style="width: 100%">
                        <thead>
                        <tr style="font-size: 11px;">
                            <th>Price</th>
                            <th>Orders</th>
                            <th>Qty</th>
                        </tr>
                        </thead>
                    <?php
                    foreach ($finalData->$f->depth->buy as  $buy) {
                        ?>
                        <tbody>
                        <tr style="font-size: 11px;">
                            <td class="text-green"><a href="/trade/order-create?symbol={{$sysmbol}}&price={{$buy->price}}&type=buy">{{$buy->price}}</a></td>
                            <td class="text-green">{{$buy->orders}}</td>
                            <td class="text-green">{{$buy->quantity}}</td>
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
                    <div class="col-sm-6">
                    <p>Ask Price</p>
                    <table style="width: 100%">
                        <tr style="font-size: 11px;">
                        <th>Price</th>
                        <th>Orders</th>
                        <th>Qty</th>
                        </tr>
                    <?php
                    foreach ($finalData->$f->depth->sell as $sell) {
                        ?>
                        <tr style="font-size: 11px;">
                        <td class="text-danger"><a href="/trade/order-create?symbol={{$sysmbol}}&price={{$sell->price}}&type=sell">{{$sell->price}}</a></td>
                        <td class="text-danger">{{$sell->orders}}</td>
                        <td class="text-danger">{{$sell->quantity}}</td>
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
                    <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-3" style="font-size: 11px;">{{$finalData->$f->ohlc->open}}<br>O</div>
                        <div class="col-sm-3" style="font-size: 11px;">{{$finalData->$f->ohlc->high}}<br>H</div>
                        <div class="col-sm-3" style="font-size: 11px;">{{$finalData->$f->ohlc->low}}<br>L</div>
                        <div class="col-sm-3" style="font-size: 11px;">{{$finalData->$f->ohlc->close}}<br>C</div>
                    </div>
                    </div>
                    <hr>
                    <div class="col-sm-12">
                        <a href="/trade/order-create?symbol={{$sysmbol}}&price={{$finalData->$f->last_price}}&type=buy"><button type="button" class="btn btn-primary btn-sm">Buy</button></a>
                        <a href="/trade/order-create?symbol={{$sysmbol}}&price={{$finalData->$f->last_price}}&type=sell"><button type="button" class="btn btn-danger btn-sm">Sell</button></a>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-dismiss="modal" onclick="deleteDepth({{$allmd->id}})">Delete</button>
                    </div>

                </div>

                </div>
            </div>
            <?php
            }
    }
}
?>
