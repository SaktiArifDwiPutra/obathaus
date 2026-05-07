<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Feedback;
use App\Http\Controllers\AdminController; // Kita buat controller baru biar rapi

Route::middleware(['auth', 'verified'])->group(function () {
    // Rute update status & hapus
    Route::patch('/admin/orders/{order}/status', [AdminController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/admin/orders/{order}', [AdminController::class, 'destroy'])->name('orders.destroy');
});

// 1. Route Toko Customer (Bisa diakses siapa saja)
Route::get('/', [ShopController::class, 'index'])->name('home');
Route::post('/checkout', [ShopController::class, 'checkout'])->name('checkout');
Route::post('/feedback', [ShopController::class, 'storeFeedback']);


// 2. Route Halaman Admin (Hanya bisa diakses kalau sudah Login)
Route::get('/dashboard', function () {
    // 1. Hitung Statistik
    $stats = [
        'revenue' => Order::sum('total_price'), // Total Uang Masuk
        'total_orders' => Order::count(),       // Total Transaksi
        'total_bottles' => OrderItem::sum('quantity'), // Total Botol Terjual
        'avg_rating' => Feedback::avg('rating') ?? 0,  // Rata-rata Bintang
    ];

    // 2. Ambil data Order beserta Item dan Produknya (diurutkan dari yang terbaru)
    $orders = Order::with(['items.product'])->latest()->get();
    
    // 3. Ambil data Kritik & Saran
    $feedbacks = Feedback::latest()->get();

    return Inertia::render('Dashboard', [
        'stats' => $stats,
        'orders' => $orders,
        'feedbacks' => $feedbacks
    ]);
})->middleware(['auth', 'verified', 'prevent-back'])->name('dashboard');


// 3. Route Profil Admin (Bawaan Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';