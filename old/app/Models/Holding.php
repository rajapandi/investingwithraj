<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holding extends Model
{


    public $fillable = array('tradingsymbol', 'exchange', 'isin', 't1quantity', 'realisedquantity', 'quantity', 'authorisedquantity', 'profitandloss', 'product', 'collateralquantity', 'collateraltype', 'haircut', 'averageprice', 'ltp', 'symboltoken', 'close', 'accountId', 'loginId', 'platform', 'broker',  'totalInvestment', 'totalReturn', 'daysReturn', 'password');

	// protected $table = 'groups';

    public $ITEMS = array();
    // public $ITEMS = array('tradingsymbol', 'exchange', 'isin', 't1quantity', 'realisedquantity', 'quantity', 'authorisedquantity', 'profitandloss', 'product', 'collateralquantity', 'collateraltype', 'haircut', 'averageprice', 'ltp', 'symboltoken', 'close', 'accountId', 'loginId', 'platform', 'broker', 'ITEMS');

}