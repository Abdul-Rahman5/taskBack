<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
//Auth
Route::controller(ApiAuthController::class)->group(function(){
    //register
    Route::post("register","register");
    //login
    Route::post("login","login");
    //logout
    Route::post("logout","logout");

});

//product
Route::controller(ProductController::class)->group(function(){

Route::get("allProduct",'index');
Route::post("addProduct",'store');

});
