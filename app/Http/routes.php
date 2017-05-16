<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('restart', 'WelcomeController@restart');



/**************************************************/

Route::get('analytics.clr', function() {
    $line_to_write = '[' . \Carbon\Carbon::now()->toDateTimeString() . '] Wyczyszczone.';   
    $line_to_write .= PHP_EOL;
    
    $myfile = fopen(public_path("analytics.txt"), "w");
    fwrite($myfile, $line_to_write);
    fclose($myfile);
});

Route::post('payu-listener', 'PaymentController@payuListener');
Route::get('potwierdzenie-platnosci/{order_id}', 'OrderController@paymentReturn');


Route::get('test', 'AdminController@createUrlForCategories');
Route::post('huj', 'ItemController@storeItemImage');

Route::post('loginr/{patch}', 'Auth\AuthController@postLoginWithRedirect');
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);





Route::group(['middleware' => array('auth', 'role'),'prefix' => 'admin'], function() {
    Route::get('get-nov', 'AdminController@getDescriptionFromCsv'); // pobiera opisy novotermu z csv
    Route::get('get-dv', 'AdminController@getDubielPriceFromCSV'); // pobiera ceny DV z csv 
    Route::get('get-duschy', 'AdminController@getDuschyPriceFromCsv'); // pobiera ceny duschy z csv 
    Route::get('change-bad', 'AdminController@changeBad'); // poprawia polskie znaki


    Route::get('', 'AdminController@index');
    
    Route::controller('orders', 'OrderController');
    
    Route::resource('roles', 'RoleController');
    Route::resource('users', 'UserController');
    Route::resource('urls', 'FriendlyUrlController');
    Route::resource('items', 'ItemController');
    Route::resource('items/{id}/remove', 'ItemController@remove');
    Route::resource('shipment', 'ShipmentController');
        Route::get('shipment/show/{id}', 'ShipmentController@show');
    Route::resource('categories', 'CategoryController');
    Route::resource('categories/{id}/remove', 'CategoryController@remove');
    Route::resource('schemas', 'SchemaCategoriesController');
   
    Route::resource('mails', 'MailController');
    Route::get('manufacturers', 'ItemController@manufacturers');
    Route::post('manufacturers', 'ItemController@manufacturers');
    Route::get('create-manufacture', 'ItemController@createManufacture');
    Route::post('store-manufacture', 'ItemController@storeManufacture');
    Route::post('store-item', 'ItemController@storeItem');
    Route::get('manufacturers/{id}/edit', 'ItemController@editManufacture');
    
    Route::resource('pages', 'PageController');
//    Route::get('templates', 'PageController@templates');
//    Route::get('templates/create/{type?}', 'PageController@createTemplate'); // type = wrapper || content
//    Route::post('store-template-file', 'PageController@storeTemplateFile');

    Route::get('elfinder/{id}', '\Barryvdh\Elfinder\ElfinderController@showPopup');
    Route::get('activity', 'WelcomeController@userActivity');

    Route::get('product-groups/create', 'ProductGroupController@create');
    Route::get('product-groups/{id}/edit', 'ProductGroupController@edit');
    Route::get('product-groups', 'ProductGroupController@index');
    Route::post('product-groups', 'ProductGroupController@index');
    Route::post('product-groups/store', 'ProductGroupController@store');



    
});

//Route::get('image/{id}', 'ImageController@get_image']);  22 850 15 04
Route::get('image/{directory}/{file}', 'ImageController@getImage');
Route::get('image/{directory}/{directory2}/{file}', 'ImageController@getImage');
//Route::get('no-image', 'ImageController@getNoImage');

Route::post('get-item-by-id', 'ItemController@getItemData'); // pobiera dane do popupa edycji itemu na stronie (item_flow_main) 
Route::post('edit-item-data', 'AdminController@getItemEditPopup'); // pobiera dane do popupa edycji itemu na stronie (item_flow_main) 
Route::post('save-edited-item', 'ItemController@saveEditedItem'); // zapisuje edytowany item

// =========== START SHOP ============

Route::get(config('shop.item_page_prefix').'/{id}/{seo_text?}', 'ItemController@details');
Route::get('koszyk', 'CartController@index');
Route::post('add-to-cart', 'CartController@addToCart');
Route::post('cart-remove-item', 'CartController@removeFromCart');
Route::post('cart-quantity-up', 'CartController@quantityUp');
Route::post('cart-quantity-down', 'CartController@quantityDown');
Route::post('refresh-cart', 'CartController@refreshCart');
Route::post('load-items-to-cart-list', 'CartController@getCartItemsList');
Route::post('remove-from-cart', 'CartController@removeFromCart');
Route::get('producenci/{id?}', 'ShopController@producenci');
Route::post('search-with-option', 'ShopController@searchWithOption');
Route::get('search/{search}/{page?}/{take?}', 'ShopController@searchItem');

Route::get(config('shop.promotion_page_prefix').'/{id}/{seo_text?}', 'ProductGroupController@show');


Route::post('checkout', 'CartController@cartCheckout');// zapisuje w sesji shipment id, sprawdza koszyk czy jest active
Route::get('checkout/{register?}', 'OrderController@displayCheckoutPage');// wyswietla formularz z danymi do ordera

Route::post('order-checkout', 'OrderController@checkoutOrder');// tutaj leca dane z checkout forma, tu siedzi validator tych danych, jesli ok to wyswietla strone z podsumowaniem danych i zakupow (ale order jeszcze sie nei tworzy), jak bledy to wraca
Route::post('order-checkout/{register}', 'Auth\AuthController@_postRegister'); // to co wyzej tylko ze rejestruje usera

Route::post('create-order', 'OrderController@createOrder'); // główna funkcja ktora tworzy ordera
Route::get('confirm-order/{id}/{hash}', 'OrderController@confirmFromMail');// potwierdzenie zamowienia (link z maila)
Route::get('order-sent/{id}/{hash}', 'OrderController@markOrderAsSent'); // oznacza zamowienie jako wysłane (link z maila do sprzedajacego po potwierdzeniu zamowieni)


Route::get('/', 'WelcomeController@index'); // strona glówana z roznymi pierdolami bajerami slayerami sliderami i ...
Route::get(config('shop.page_prefix').'/{page}', 'PageController@getPage');

// Route::get('{schema}', 'ShopController@index'); // tu trzeba zrobic jakies super fajne produkty polecane czy cos z roznych kategorii bestsellery i huj wie co jeszcze
Route::get('kategorie/{schema}/{name?}/{page?}/{take?}/{params?}', 'ShopController@itemsOfCategory');
// ============ END SHOP =============

Route::get('artykuly/{article_url?}', 'PageController@show');


// Route::get('tracker', 'TrackerController@index');
// Route::get('tracker/{id}/view', 'TrackerController@view');
// Route::post('tracker-send-data', 'TrackerController@storeTrack');



// ========= START PAYPAL ============


//Route::filter('csrf', function(){
//
//    // bypass route names (could move to a config file)
//    $tmpStr = array('listen.store',);
//
//    //bypass on these routes
//    $routename = Route::currentRouteName();
//
//    if (!in_array($routename, $tmpStr)) {
//
//        if (Session::token() !== Input::get('_token'))
//        {
//            throw new Illuminate\Session\TokenMismatchException;
//        }
//
//    }
//
//});

 
// Route::resource('listen', 'PaymentController');



// ========== END PAYPAL =============

/*
Route::get('{url}', function($url){
    $action = \App\FriendlyUrl::getRoute($url);
    if(empty($action))
        abort(404);
    $action = str_replace('App\Http\Controllers\\', '', $action);
    Route::get('walcie-sie-huje', $action);
});
*/


/*
 * Route::get('{name}/displays',function($name){
    $r=\App\Airts::getRoute($name);
//    /dd($r);
    if(empty($r)){
        abort(404);
    }
    $http=strpos($r,'http');
    if($http!==false){
        return Redirect::away($r);
    }
    $req=Request::duplicate();
    $re=$req->create($r,'GET',$_GET,$_COOKIE,$_FILES,$_SERVER);
    $req->initialize($re->query->all(), $re->request->all(), $re->attributes->all(), $_COOKIE, $_FILES, $re->server->all());
    return Route::dispatchToRoute($req);
});
 */


























