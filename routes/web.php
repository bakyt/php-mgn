<?php

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

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();
Route::get('test', "HomeController@create")->name("test.create");
Route::get('image', "HomeController@out")->name("test.out");
Route::post('test', "HomeController@store")->name("test.store");
//Route::get('/users', 'UserController@toProfile');
Route::get('/users/subscribe', "UserController@getSubscribeInterface");
Route::post('/users/subscribe', "UserController@setFirebaseToken");
Route::post('/users/notice', 'UserController@actionNotice')->name("notice");
Route::post('/users/check', 'UserController@checkExisting');
Route::post('/password/check', 'Auth\ForgotPasswordController@phoneCheck')->name('password.check');
Route::get('/password/change', 'Auth\ResetPasswordController@change')->name('password.change');
Route::post('/password/update', 'Auth\ResetPasswordController@update')->name('password.update');
Route::post('/users/update/{id}', 'UserController@update')->name('user.update');
Route::post('/users/delivery/{id}', 'UserController@deliveryUpdate')->name('user.delivery.update');
Route::get('/users/{id}', 'UserController@profile');
Route::get('/', 'HomeController@index')->name("home");
Route::get('/home', 'HomeController@index');

Route::get('/category/create', 'GlobalCategoryController@create')->name('category.create');
Route::post('/category/create', 'GlobalCategoryController@store')->name('category.store');
Route::get('/category/moderate/{id}', 'GlobalCategoryController@moderate')->name('category.moderate');
Route::get('/category/edit/{id}', 'GlobalCategoryController@edit')->name('category.edit');
Route::post('/category/edit/{id}', 'GlobalCategoryController@update')->name('category.update');
Route::get('/category/{id}', 'GlobalCategoryController@subList')->name('category.sub');

Route::get('/item/create', 'RentItemController@createCategorySelector')->name('item.createCat');
Route::post('/item/create', 'RentItemController@createCategorySelect')->name('item.createCatRed');
Route::get('/item/create/{id}/{type}', 'RentItemController@create')->name('item.create');
Route::post('/item/create/{id}/{type}', 'RentItemController@store')->name('item.store');
Route::get('/item/edit/{id}/{type}', 'RentItemController@edit')->name('item.edit');
Route::post('/item/edit/{id}/{type}', 'RentItemController@update')->name('item.update');
Route::get('/item/plusplus', 'RentItemController@viewPlus');
Route::get('/view/{id}', 'RentItemController@item')->name('item.view');
Route::get('/list/{id}', 'RentItemController@listItems')->name('category.getItems');
Route::post('/item/cron/timeout', 'RentItemController@cron');
Route::post('/item/order/create', 'RentItemController@order');
Route::post('/item/order/to_history', 'RentItemController@toHistory');

Route::post('/message/get_new', 'UserController@getNewMessages');

Route::post('/message/get', 'UserController@getMessagesWith');
Route::post('/message/update', 'UserController@getContacts');
Route::post('/message/send', 'UserController@sendMessage');
Route::post('/message/deletewith', 'UserController@deleteMessagesWith');
Route::post('/message/delete', 'UserController@deleteMessage');
Route::post('/guest/check', 'UserController@guestCheck');
Route::post('/guest/create', 'UserController@createGuest');
Route::get('/phone/codes', 'UserController@getPhoneProperties');
Route::get('/phone/property', 'UserController@getPhoneProperty');

Route::get('/message/push', 'UserController@test');
Route::post('/notice/get_new/', 'UserController@getNewNotices');
Route::post('/notice/deliver/', 'UserController@deliverNotices');
Route::post('/moderation/get_new/', 'UserController@getNewModerates');
Route::post('/guest/logout', 'UserController@guestLogout')->name('logout.guest');
Route::get('/timezone/set', 'HomeController@setTimezone');
Route::get('/timezone/check', 'HomeController@checkTimezone');
Route::get('/location/get', 'HomeController@getLocation');
Route::post('/visit/update', 'UserController@userVisitUpdate');
Route::post('/avatar/upload', 'UserController@uploadAvatar');
Route::post('/avatar/delete', 'UserController@deleteAvatar');
//Route::get('/page/test', function (){
//    $nodes = array();
//    for($i=0;$i<50; $i++){
//        array_push($nodes, 'http://megazonlocal.kg/');
//    }
//    $node_count = count($nodes);
//
//    $curl_arr = array();
//    $master = curl_multi_init();
//
//    for($i = 0; $i < $node_count; $i++)
//    {
//        $url =$nodes[$i];
//        $curl_arr[$i] = curl_init($url);
//        curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
//        curl_multi_add_handle($master, $curl_arr[$i]);
//    }
//
//    do {
//        curl_multi_exec($master,$running);
//    } while($running > 0);
//
//    echo "results: ";
//    for($i = 0; $i < $node_count; $i++)
//    {
//        $results = curl_multi_getcontent  ( $curl_arr[$i]  );
//        echo( $i . "\n" . $results . "\n");
//    }
//    echo 'done';
//});
Route::get('/page/{slug}', 'PagesController@show');
//Market
Route::get('/markets', 'MarketController@marketList');
Route::post('/markets/getMarkets', 'MarketController@getMarkets');
Route::get('/markets/create/{type}', 'MarketController@createMarket')->name('market.create');
Route::get('/markets/create', 'MarketController@createMarketTypeSelector')->name('market.selector');
Route::post('/markets/create', 'MarketController@createMarketTypeSelect')->name('market.select');
Route::post('/markets/create/{type}', 'MarketController@storeMarket')->name('market.store');
Route::get('/markets/check', 'MarketController@checkMarket');
Route::get('/markets/edit/{id}', 'MarketController@editMarket')->name('market.edit');
Route::post('/markets/edit/{id}', 'MarketController@updateMarket')->name('market.update');
Route::post('/markets/destroy/{id}', 'MarketController@destroyMarket')->name('market.destroy');

Route::get('/search', 'HomeController@search')->name('search');

Route::get('{slug}', 'MarketController@index')->name('market.index');
Route::get('{slug}/getProducts', 'MarketController@getProducts');
Route::post('{slug}/getProducts', 'MarketController@getProducts');
Route::get('{slug}/orders', 'MarketController@orders')->name('market.orders');
Route::get('{slug}/category', 'MarketController@editCategory')->name('market.category');
Route::post('{slug}/category', 'MarketController@updateCategory')->name('market.category.edit');
Route::get('{slug}/create', 'MarketController@createCategorySelector')->name('market.item.catselector');
Route::post('{slug}/create', 'MarketController@createCategorySelect')->name('market.item.catselect');
Route::get('{slug}/create/{id}', 'MarketController@create')->name('market.item.create');
Route::post('{slug}/create/{id}', 'MarketController@store')->name('market.item.store');
Route::get('{slug}/edit/{id}', 'MarketController@edit')->name('market.item.edit');
Route::post('{slug}/edit/{id}', 'MarketController@update')->name('market.item.update');
Route::get('{slug}/setting/', 'MarketController@setting')->name('market.setting');
Route::get('{slug}/list/{id}', 'MarketController@itemList')->name('market.list');
Route::get('{slug}/view/{id}', 'MarketController@view')->name('market.item.view');
Route::get('{slug}/search', 'MarketController@search')->name('search.market');
//!Market