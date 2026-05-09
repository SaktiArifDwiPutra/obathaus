# 🧉 Dashboard Admin - Obat Haus

Sistem manajemen pesanan dan panel admin modern untuk "Obat Haus". Dibangun menggunakan arsitektur Monolith modern (Inertia.js) untuk memberikan pengalaman *Single Page Application* (SPA) yang sangat cepat, interaktif, dan responsif baik di desktop maupun mobile.

## ✨ Fitur Utama

- **Manajemen Pesanan Real-time**: Pantau pesanan aktif, pindahkan ke arsip (selesai), atau hapus data dengan *Custom Confirmation Modal* yang aman.
- **Smart Maps Routing**: Integrasi langsung dengan Google Maps Directions API. Kurir cukup klik satu tombol untuk mendapatkan rute navigasi akurat ke titik lokasi pelanggan.
- **WhatsApp Quick Chat**: Fitur *shortcut* untuk menghubungi pelanggan via WhatsApp Web/App secara langsung tanpa perlu menyimpan nomor telepon ke kontak.
- **Analisa Klinik (Statistik Dashboard)**: Laporan ringkas yang memantau total pemasukan (revenue), jumlah transaksi, total botol terjual, dan rata-rata rating kepuasan pelanggan.
- **Filter Kotak Suara**: Sistem manajemen ulasan pelanggan yang dilengkapi dengan filter rating bintang (1-5).
- **Responsive Layout**: Desain adaptif dengan *Sidebar* elegan untuk Desktop dan menu *Hamburger* praktis untuk Mobile.

## 🛠️ Tech Stack

Aplikasi ini menggunakan teknologi modern (VILT Stack):
- **Backend**: [Laravel](https://laravel.com/) (PHP Framework)
- **Frontend**: [Vue.js 3](https://vuejs.org/) (Composition API) + [Inertia.js](https://inertiajs.com/)
- **Styling**: [Tailwind CSS](https://tailwindcss.com/) & FontAwesome
- **Database**: PostgreSQL (di-hosting menggunakan [Neon.tech](https://neon.tech/))
- **Deployment & Hosting**: [Render](https://render.com/)

## 🚀 Panduan Instalasi Lokal (Development)

Jika ingin mengembangkan atau menjalankan aplikasi ini di komputer lokal (Localhost), ikuti langkah-langkah berikut:

1. **Clone Repository**
   ```bash
   git clone [https://github.com/USERNAME_GITHUB/ProjekLaravel.git](https://github.com/USERNAME_GITHUB/ProjekLaravel.git)
   cd ProjekLaravel
   
```

2. **Install Dependensi**
   ```bash
   composer install
   npm install
   
```

3. **Konfigurasi Environment**
   - Duplikat file `.env.example` dan ubah namanya menjadi `.env`
   - Sesuaikan kredensial `DB_CONNECTION`, `DB_HOST`, dll dengan database lokal atau database cloud Anda.

4. **Generate Key & Migrasi Database**
   ```bash
   php artisan key:generate
   php artisan migrate
   
```

5. **Build Frontend & Jalankan Server Lokal**
   Karena menggunakan Vue & Inertia, aset frontend wajib di-*build*:
   ```bash
   npm run build
   php artisan serve
   
```
   *(Gunakan perintah `npm run dev` pada terminal terpisah jika ingin mengaktifkan Hot Module Replacement saat mengedit kode UI).*

## 👨‍💻 Developer

Dikembangkan oleh:
**Sakti Arif Dwi Putra Sinaga (Daus Company)**  
Program Keahlian Rekayasa Perangkat Lunak (RPL) - SMKN 4 Padalarang.