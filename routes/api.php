<?php
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\API\OrderController;
use App\Http\Middleware\IsAdmin;




// Public API auth endpoints for Postman
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);


 
 


//routes for admin
Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
     //Route::post('/products', [ProductController::class, 'store']);
     Route::get('/orders',[OrderController::class,'index']);//see only orders
    Route::put('/orders/{id}', [OrderController::class, 'update']);//status or updating other things

    Route::get('/admin-orders', [OrderController::class, 'adminOrders']);//see orders with products
    // Route::delete('/products/{id}', [ProductController::class, 'destroy']);

  Route::apiResource('products', ProductController::class);
});


//routes for users

 Route::middleware(['auth:sanctum', ])->group(function () {

    Route::get('/allproducts', [ProductController::class, 'index']);
   // Route::get('/createorder')

   Route::post('/orders-with-items', [OrderController::class, 'storeWithItems']);

   
//      Route::get('/orders',[OrderController::class,'index']);
//     Route::put('/orders/{id}', [OrderController::class, 'update']);
//     // Route::delete('/products/{id}', [ProductController::class, 'destroy']);

//    Route::apiResource('products', ProductController::class);
 });


Route::middleware('auth:sanctum')->get('/my-orders', [OrderController::class, 'myOrders']);





// Protected routes (work with Sanctum token or SPA cookie)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me',      [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Product CRUD (already built in Step 3)

    // Route::apiResource('products', ProductController::class);

    // Order routes
    // Route::apiResource('orders', OrderController::class);

    


    Route::post('/orders-with-items', [OrderController::class, 'storeWithItems']);

});


