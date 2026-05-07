<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minuman Segar 12K - Delivery</title>
    
    <!-- Tailwind CSS (CDN untuk testing UI cepat) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        /* Memastikan peta tidak menutupi elemen UI Tailwind */
        #map { z-index: 10; }
        .leaflet-bottom, .leaflet-top { z-index: 10 !important; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">

    <!-- Navbar/Header -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-green-600"><i class="fa-solid fa-mug-hot mr-2"></i>Daus Drink</h1>
            <div class="text-sm font-semibold text-gray-500">
                <i class="fa-solid fa-fire text-orange-500 mr-1"></i> {{ $totalOrders }} Pesanan Sukses
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 py-8">
        
        <!-- Alert Success -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-bold"><i class="fa-solid fa-check-circle mr-2"></i>Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- KOLOM KIRI: Daftar Menu & Feedback -->
            <div class="md:col-span-2 space-y-8">
                
                <!-- Form Utama untuk Order -->
                <form action="{{ route('checkout') }}" method="POST" id="orderForm">
                    @csrf
                    
                    <!-- Section Menu -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="bg-green-500 text-white px-6 py-4">
                            <h2 class="text-xl font-bold"><i class="fa-solid fa-utensils mr-2"></i>Pilihan Menu</h2>
                        </div>
                        
                        <div class="p-2">
                            @foreach($products as $product)
                            <div class="flex justify-between items-center p-4 border-b last:border-0 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <!-- Placeholder gambar, bisa diganti nanti -->
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-image text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800">{{ $product->name }}</h3>
                                        <p class="text-green-600 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <label class="text-xs text-gray-500 mb-1">Jumlah</label>
                                    <input type="number" name="items[{{ $product->id }}]" min="0" value="0" 
                                           class="w-20 p-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-green-400 focus:outline-none">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section Pengiriman (Digabung dengan form order) -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-8">
                        <div class="px-6 py-4 border-b">
                            <h2 class="text-xl font-bold text-gray-800"><i class="fa-solid fa-motorcycle mr-2 text-green-500"></i>Detail Pengiriman</h2>
                        </div>
                        <div class="p-6 space-y-5">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemesan</label>
                                <input type="text" name="customer_name" placeholder="Masukkan nama Anda" required
                                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pin Lokasi Peta</label>
                                <p class="text-xs text-gray-500 mb-2">Geser pin merah ke titik lokasi pengantaran untuk kurir kami.</p>
                                <div id="map" class="h-64 w-full rounded-lg border border-gray-300 shadow-inner"></div>
                                <input type="hidden" name="latitude" id="lat" required>
                                <input type="hidden" name="longitude" id="lng" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                <textarea name="address_detail" rows="3" required
                                          placeholder="Contoh: Jl. Mawar No.12, Pagar Hitam depan warung (Sertakan patokan yang jelas)"
                                          class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none"></textarea>
                            </div>

                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-lg shadow-md transition-colors text-lg">
                                <i class="fa-solid fa-bag-shopping mr-2"></i>Pesan & Bayar COD
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- KOLOM KANAN: Feedback & Social -->
            <div class="space-y-8">
                
                <!-- Form Feedback -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-star text-yellow-400 mr-2"></i>Berikan Ulasan</h2>
                    </div>
                    <form action="{{ route('feedback.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        <input type="text" name="customer_name" placeholder="Nama Anda" required
                               class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        
                        <select name="rating" required class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <option value="">-- Pilih Rating --</option>
                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Enak)</option>
                            <option value="4">⭐⭐⭐⭐ (Enak)</option>
                            <option value="3">⭐⭐⭐ (Biasa Saja)</option>
                            <option value="2">⭐⭐ (Kurang)</option>
                            <option value="1">⭐ (Tidak Enak)</option>
                        </select>
                        
                        <textarea name="message" rows="3" placeholder="Kritik atau saran..." required
                                  class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"></textarea>
                        
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg shadow transition-colors">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>

                <!-- List Feedback Terbaru -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Ulasan Pelanggan</h3>
                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        @forelse($feedbacks as $fb)
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-sm">{{ $fb->customer_name }}</span>
                                    <span class="text-yellow-400 text-xs">
                                        @for($i=1; $i<=5; $i++)
                                            @if($i <= $fb->rating) <i class="fa-solid fa-star"></i>
                                            @else <i class="fa-regular fa-star text-gray-300"></i>
                                            @endif
                                        @endfor
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 italic">"{{ $fb->message }}"</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center">Belum ada ulasan.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Social Links -->
                <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                    <h3 class="font-bold text-gray-800 mb-4">Temukan Kami</h3>
                    <div class="flex justify-center space-x-4">
                        <a href="#" class="w-10 h-10 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center hover:bg-pink-200 transition">
                            <i class="fa-brands fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center hover:bg-green-200 transition">
                            <i class="fa-brands fa-whatsapp text-xl"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800 transition">
                            <i class="fa-brands fa-tiktok text-xl"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-6.914744, 107.609810], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var marker = L.marker([-6.914744, 107.609810], {draggable: true}).addTo(map);

        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            document.getElementById('lat').value = position.lat;
            document.getElementById('lng').value = position.lng;
        });

        // Auto-locate
        map.locate({setView: true, maxZoom: 16});
        map.on('locationfound', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('lat').value = e.latlng.lat;
            document.getElementById('lng').value = e.latlng.lng;
        });
        
        // Atasi bug map abu-abu (render issue pada Leaflet saat diload di container tersembunyi/dinamis)
        setTimeout(function(){ map.invalidateSize(); }, 500);
    </script>
</body>
</html>