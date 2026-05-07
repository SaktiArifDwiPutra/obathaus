<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
public function updateStatus(Order $order, Request $request)
{
    // Cek apakah data status masuk
    $validated = $request->validate([
        'status' => 'required|string',
    ]);

    $order->update([
        'status' => $validated['status']
    ]);

    return redirect()->back()->with('success', 'Pesanan diselesaikan!');
}

    // Fungsi ini yang tadi hilang/error
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->back()->with('success', 'Pesanan berhasil dihapus!');
    }
}