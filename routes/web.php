<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });




Route::get('/',function () {
    return Inertia::render('Home');
});

Route::get('/register',function () {
    return Inertia::render('register');
});


Route::get('/login',function () {
    return Inertia::render('login');
});

Route::get('/My-orders',function(){
    return Inertia::render('MyOrder');
});




Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::get('/orders', function () {
//     return Inertia::render('Orders');
// })->middleware(['auth']);


// ✅ Add this Products route (using Inertia like your other routes)
Route::get('/products', function () {
    return Inertia::render('Products'); // This will render resources/js/Pages/Products.vue
});//->middleware(['auth']); // Add auth middleware if needed

Route::get('/admin', function () {
    return Inertia::render('Admin'); // This will render resources/js/Pages/Admin.vue
});
Route::get('/cart', function () {
    return Inertia::render('Cart'); // This loads Cart.vue
});


require __DIR__.'/auth.php';
