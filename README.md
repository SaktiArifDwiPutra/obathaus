# 🧉 Dashboard Admin - Obat Haus

<p align="center">
  Sistem manajemen pesanan dan panel admin modern untuk <b>Obat Haus</b>.  
  Dibangun menggunakan arsitektur Monolith modern berbasis <b>Laravel + Vue + Inertia.js</b>  
  untuk menghadirkan pengalaman <i>Single Page Application (SPA)</i> yang cepat, interaktif, dan responsif.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/Vue.js-3-green?style=for-the-badge&logo=vuedotjs">
  <img src="https://img.shields.io/badge/Inertia.js-SPA-purple?style=for-the-badge">
  <img src="https://img.shields.io/badge/TailwindCSS-Modern-blue?style=for-the-badge&logo=tailwindcss">
</p>

---

## 🌐 Live Preview

🔗 **Official Website**  
https://obathaus.onrender.com


> *Gunakan tampilan Mobile untuk pengalaman terbaik.*

---

## ✨ Fitur Utama

- 📦 **Manajemen Pesanan Real-time**  
  Pantau pesanan aktif, pindahkan ke arsip (selesai), atau hapus data menggunakan *Custom Confirmation Modal* yang aman dan modern.

- 🗺️ **Smart Maps Routing**  
  Integrasi langsung dengan Google Maps Directions API. Kurir cukup klik satu tombol untuk mendapatkan rute navigasi akurat menuju lokasi pelanggan.

- 💬 **WhatsApp Quick Chat**  
  Fitur *shortcut* untuk menghubungi pelanggan melalui WhatsApp Web/App tanpa perlu menyimpan nomor telepon terlebih dahulu.

- 📊 **Analisa Klinik (Dashboard Statistik)**  
  Menampilkan total pemasukan (*revenue*), jumlah transaksi, total botol terjual, dan rata-rata rating kepuasan pelanggan secara ringkas.

- ⭐ **Filter Kotak Suara**  
  Sistem pengelolaan ulasan pelanggan dengan filter rating bintang (1-5).

- 📱 **Responsive Layout**  
  Desain adaptif dengan *Sidebar Desktop* elegan dan *Hamburger Menu Mobile* yang praktis.

---

## 🛠️ Tech Stack

Aplikasi ini menggunakan teknologi modern berbasis **VILT Stack**:

| Technology | Description |
|---|---|
| ⚙️ Laravel | Backend Framework |
| 🟢 Vue.js 3 | Frontend Framework (Composition API) |
| 🔗 Inertia.js | SPA Bridge |
| 🎨 Tailwind CSS | Utility-first CSS Framework |
| 🐘 PostgreSQL | Database |
| ☁️ Render | Deployment & Hosting |
| ⚡ Neon.tech | Cloud PostgreSQL Hosting |

---

## 🚀 Panduan Instalasi Lokal (Development)

Jika ingin menjalankan project ini di komputer lokal:

### 1️⃣ Clone Repository

```bash
git clone https://github.com/USERNAME_GITHUB/ProjekLaravel.git
cd ProjekLaravel
```

### 2️⃣ Install Dependensi

```bash
composer install
npm install
```

### 3️⃣ Konfigurasi Environment

- Duplikat file `.env.example`
- Ubah nama menjadi `.env`
- Sesuaikan konfigurasi database dan environment

---

### 4️⃣ Generate Key & Migrasi Database

```bash
php artisan key:generate
php artisan migrate
```

---

### 5️⃣ Build Frontend & Jalankan Server

```bash
npm run build
php artisan serve
```

Untuk mode development dengan Hot Reload:

```bash
npm run dev
```

---

## 📸 Preview

> Tambahkan screenshot dashboard di sini agar repository terlihat lebih profesional.

```md
![Preview Dashboard](public/preview-dashboard.png)
```

## 👨‍💻 Developer

Developed with ❤️ by

### **Sakti Arif Dwi Putra S.**  
Rekayasa Perangkat Lunak (RPL)

📧 Email: obathausofficial@gmail.com  
🌐 Portfolio: https://yourportfolio.com  
🐙 GitHub: https://github.com/SaktiArifDwiPutra

---

<p align="center">
  © 2026 Sakti Arif — Obat Haus Project
</p>