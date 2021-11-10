<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', function () {
    return view('welcome');
});
Route::get('/', function () {
    return view('login');
});
Route::post('/admin/login', 'AdminController@authenticate');
Route::get('/logout', 'AdminController@logout');
Route::get('/dashboard', 'HomeController@index');
Route::get('/dashboard/getPortfolio', 'HomeController@getPortfolio');
Route::get('/dashboard/getChangeStockBroker', 'HomeController@getChangeStockBroker');

Route::get('/customer', function () {
    return view('customer.index');
});
Route::get('/customer/create', function () {
    return view('customer.create');
});
Route::get('/customer/edit/{id}', function () {
    return view('customer.edit');
});
Route::post('/customer/store', 'CustomerController@store');
Route::post('/customer/update', 'CustomerController@update');
Route::get('/customer/getDeleteCustomer/{id}', 'CustomerController@delete');


Route::get('/trading/list', function () {
    return view('trading.index');
});
Route::get('/trading/create', function () {
    return view('trading.create');
});
Route::get('/trading/edit/{id}', function () {
    return view('trading.edit');
});
Route::post('/trading/store', 'TradingAccountController@store');
Route::post('/trading/update', 'TradingAccountController@update');
Route::get('/trading/getDeleteTradingAccount/{id}', 'TradingAccountController@delete');

Route::get('/orders/list', 'TradingAccountController@getOrders');
Route::get('/trade/create', function(){
    return view('trade.create');
});
Route::post('/trade/showModifyOrders', 'TradeController@getIdentify');
Route::get('/trade/searchTradeSymbol', 'TradeController@searchTradeSymbol');
Route::post('/trade/create', 'TradeController@create');
Route::post('/trade/modify/update', 'TradeController@update');
Route::post('/trade/getCancelOrder', 'TradeController@getCancelOrder');

Route::get('/brocker/getFindPlatform', function(){
    return view('brocker.getFindPlatform');
});

Route::get('/group', function(){
    return view('group.index');
});
Route::get('/group/create', function(){
    return view('group.create');
});
Route::get('/group/edit/{id}', function(){
    return view('group.edit');
});
Route::get('/group/getgroupaccount', function(){
    return view('group.getgroupaccount');
});
Route::get('/group/getnotgroupaccount', function(){
    return view('group.getnotgroupaccount');
});

Route::post('/group/store', 'GroupController@store');
Route::post('/group/update', 'GroupController@update');
Route::get('/group/getDeleteGroup/{id}', 'GroupController@delete');

Route::get('/diffrent-qty', function(){
    return view('trade.showDiffQty');
});
Route::get('/diffrent-qty/diff', function(){
    return view('trade.showDiffQty_diff');
});

Route::get('/user/activation', 'TradingAccountController@activation');


Route::get('/totp/showfromfortotp', function(){
    return view('totp.showformfortotp');
});

Route::get('/totp/store', 'Authenticator@store');

Route::get('/qrcode', 'HomeController@getQrCodeData');


Route::get('/orders/position', 'HomeController@getAllPosition');


Route::get('/holding', 'HomeController@holding');
// Route::get('/holding', function(){
//     return view('holding.holding');
// });
// Route::get('/ajax/data/holding', 'HomeController@holding');


Route::get('/portfolio', 'HomeController@portfolio');

Route::get('/trade/order-create', function(){
    return view('trade.createorder');
});

Route::get('/general-setting', function(){
    return view('general.index');
});
Route::post('/setting/general', 'GeneralSettingController@update');

Route::get('/jqajax/portfolio', 'HomeController@portfolioByAjax');


Route::get('/access_token', 'TradingAccountController@getKiteUserDetail');
Route::get('/ltp', 'HomeController@getLTP');


Route::get('/jqajqx/marketdepth', function(){
    return view('marketdepth.jqajax.marketdepth');
});
Route::get('/jqajax/ltp', 'HomeController@getLTP');

Route::get('/market-depth', function(){
    return view('marketdepth.index');
});
Route::get('/create/marketdepth', function(){
    return view('marketdepth.jqajax.create');
});
Route::get('/jqajax/mrket-depth', function () {
    return view('marketdepth.jqajax.findmarketdepth');
});
Route::get('/jqajax/popup-mrket-depth', function () {
    return view('marketdepth.jqajax.popupmarketdepth');
});

Route::get('/delete/depth/{id}', 'MarketDepthController@delete');
Route::post('/create/market-depth', 'MarketDepthController@store');



