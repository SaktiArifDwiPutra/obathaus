<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Feedback;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException; // Wajib ditambahkan

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $feedbacks = Feedback::latest()->take(10)->get(); // Kirim review ke halaman depan
        return Inertia::render('Customer/Home', [
            'products' => $products,
            'feedbacks' => $feedbacks
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'whatsapp_number' => 'required', // WA Wajib
            'latitude' => 'required',
            'longitude' => 'required',
            'address_detail' => 'required',
            'cart' => 'required|array',
        ]);

        $totalPrice = 0;

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'whatsapp_number' => $request->whatsapp_number,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address_detail' => $request->address_detail,
            'total_price' => 0,
            'status' => 'pending'
        ]);

        foreach($request->cart as $item) {
            $subtotal = $item['price'] * $item['qty'];
            $totalPrice += $subtotal;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['qty'],
                'subtotal' => $subtotal
            ]);
        }

        $order->update(['total_price' => $totalPrice]);
        return redirect()->back()->with('success', 'Pesanan masuk! Mohon siapkan uang pas ya.');
    }

    // FUNGSI ANTI-SPAM FEEDBACK
    public function storeFeedback(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'whatsapp_number' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required'
        ]);

        // Cek apakah nomor WA ini pernah beli?
        $hasOrdered = Order::where('whatsapp_number', $request->whatsapp_number)->exists();

        if (!$hasOrdered) {
            // Jika belum pernah beli, tolak dan kembalikan pesan error!
            throw ValidationException::withMessages([
                'whatsapp_number' => 'Nomor WA ini belum pernah memesan Obat Haus. Ulasan ditolak!'
            ]);
        }

        Feedback::create([
            'customer_name' => $request->customer_name,
            'whatsapp_number' => $request->whatsapp_number, // Simpan WA
            'rating' => $request->rating,
            'message' => $request->message
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Ulasan kamu sangat berharga.');
    }
}