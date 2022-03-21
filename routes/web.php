<?php

use Illuminate\Http\Request;
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

Route::get('/', function () {
    // return view('welcome');
    return "BOT Ticketing!";
});

// Többféle HTTP methodhoz is hozzárendeljük a route-ot
Route::match(['post', 'get'], '/testgetpost', function (){
   return "Get or Post request..";
});

// Bármilyen methoddal érkezik a kérés a route-ra, kiszolgálja
Route::any('/testany', function(){
   return "Any method request";
});


// Lesznek olyan függvényeink, amiknek nem adjuk át  paramétereket, mégis mekapják őket
// A fgv, ami kiszolgálja a kérést, paraméterben várja a requesteket
// Dependency Injection -> A Request paramétert a Laravel adja
Route::get('/testrequest', function(Request $request){
    var_dump($request->method()); // Kérés HTTP Method-ja
    // var_dump($request->input()); // Inputok (_POST, _GET)
});

// Redirect Route
// Ha jön egy kérés, át lehet irányítani valahova
// HTTP 302 Redirect - Ideiglenes átirányítás
Route::redirect('/alma', '/testrequest');

// HTTP 301 Redirect - Végleges átirányítás
Route::permanentRedirect('/korte', '/testrequest');


// Nézet közvetlen megjelenítése (nézet fájl: welcome.blade.php)
Route::view('/testview', 'welcome')->name('welcome');
// Nézet közvtlen egjelenítése. paraméterek átadása a nézethez
// Route::view('/testview', 'welcome', ['name'=>'Nora']);

// Paraméteres route, a {}-ek között megadott helyőrző(k)
// értékét megkapjuk a route által hívott fgv azonos nevű paraméterében
/* Route::get('/book/{id}/rent/{number}', function ($id, $number){
   return 'Book:' . $id . ' Rent:' . $number;
}); */

// Request (DI) kombinálható a paraméteres route-okkal,
// viszont az első paraméterben adjuk meg a Request $r-t
Route::get('/book/{id}/rent/{number}', function (Request $req, $id, $number){
    return 'Book:' . $id . ' Rent:' . $number;
});

// Paraméteres route, opcionális paraméterekkel
// kérdőjel a helyőrző neve után {nev?}
// fgv-ben az opcionális paraméterhez tartozó route-hez default...
// ... értéket kell renelni: pl. $license_plate = null
Route::get('/car/{license_plate?}', function ($license_plate = null){
    return 'Car:'.$license_plate;
});

Route::get('/car/{lp?}/c/{color}', function ($lp = null, $color="R"){
    return 'Car:'.$lp.' Color:'.$color;
});

// Regular expression - Route paraméterek korlátozása ->where(RegExp)
Route::get('/user/{id}', function ($id){
   //
})->where('id', '[0-9]+')->name('regex');
// ->whereNumber('id') // Az 'id' route paraméter csak szám lehet
// ->whereAlpha('id') // Az 'id' route paraméter csak betű lehet
// ->whereAlphaNumeric('id') // Az 'id' route paraméter csak betű és szám lehet

Route::get('/routename', function (){
    // route() fgv létrehozza a 'welcome' nevű route-ra mutató...
    // teljes útvonalat: (http://<host>/<route>
    // return \route('welcome');

    // paraméteres route esetén a route() fgv második paraméterében
    // megadhatjuk egy tömbben, hogy mi legyen az adott paraméterekhez
    // rendelt érték
    return route('regex', ['id'=>5]);
})->name('routename'); // route nevesítése a ->name()-el

Route::get('/redirectname', function (){
   // átirányítás a oute() fgv-be megadott útvonalra
    // paraméteres útvonal esetén itt is megadhatóak a route fgv ...
    // 2. paraméterében az értékek
    return redirect()->route('welcome');
});

//
Route::get('/routeinfo', function (){
   // $cr = "Route: " . Route::current() . "<br>";
   $cr = "Route name: " . Route::currentRoutename() . "<br>";
   return $cr;
})->name('RouteInfo');
