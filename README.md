# Asset Inventory Management System (Sistem Manajemen Inventaris Aset)

Sistem Informasi Inventaris Aset adalah platform berbasis web yang digunakan untuk mencatat data barang operasional kantor, memproses transaksi peminjaman secara otomatis, serta menyajikan laporan sirkulasi barang.

## Fitur Utama
1. **Multi-Role Authentication:** Hak akses terbagi menjadi 3 level (Admin, Staff, Manager).
2. **Master Data Barang (CRUD):** Pencatatan barang dengan kode barang unik, kategori, stok, lokasi, kondisi, dan unggahan foto barang.
3. **Transaksi Peminjaman & Pengembalian:** Pengurangan stok otomatis saat dipinjam, dan pengembalian barang terkonfirmasi.
4. **Dashboard & Statistik:** Visualisasi grafik tren peminjaman per bulan menggunakan Chart.js.
5. **Laporan & Ekspor Data:** Filter laporan sirkulasi barang berdasarkan rentang tanggal, dengan opsi Cetak PDF dan Export Excel (CSV).
6. **REST API:** Menyediakan endpoint JSON untuk integrasi platform eksternal.

---

## Stack Teknologi yang Digunakan
* **Backend:** PHP 8.2 & Laravel 12
* **Frontend:** Tailwind CSS (Laravel Breeze) & Chart.js
* **Database:** PostgreSQL (Hosted on Supabase)
* **Kontainerisasi:** Docker
* **CI/CD:** GitHub Actions (Automated Testing via PHPUnit)
* **Cloud Hosting:** Google Cloud Run (GCP)

---

## Cara Instalasi & Menjalankan Proyek secara Lokal

### 1. Prasyarat
Pastikan laptop Anda sudah terinstall:
* PHP 8.2 atau lebih tinggi
* Composer
* Node.js & NPM
* Database PostgreSQL atau MySQL

### 2. Kloning Repository
```bash
git clone https://github.com/rajjafadzella/inventory-system.git
cd inventory-system
```

### 3. Install Dependensi PHP & JavaScript
```bash
composer install
npm install
```

### 4. Konfigurasi Environment File
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` baru tersebut, lalu sesuaikan konfigurasi database Anda:
```env
DB_CONNECTION=pgsql
DB_HOST=your-supabase-db-host
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

### 5. Generate Application Key & Migrasi Database
```bash
php artisan key:generate
php artisan migrate --seed
```
*Perintah di atas sekaligus akan membuat tabel dan mengisi data role, kategori, serta akun testing bawaan.*

### 6. Compile Asset Frontend & Jalankan Server Lokal
```bash
# Compile asset JS/CSS
npm run dev

# Jalankan server local PHP
php artisan serve
```
Akses aplikasi melalui browser Anda di: `http://127.0.0.1:8000`

---

## Cara Menjalankan Pengujian (Automated Testing)
Proyek ini dilengkapi dengan unit dan feature testing untuk memastikan integritas logika bisnis.
Untuk menjalankan pengujian secara lokal, jalankan perintah berikut:
```bash
php artisan test
```

---

## Akun Login Testing (Bawaan Database Seeder)

* **Role Admin (Full Access)**
  * **Email:** `admin@telkomsel.com`
  * **Password:** `password123`
* **Role Staff (Kelola Barang & Peminjaman)**
  * **Email:** `staff@telkomsel.com`
  * **Password:** `password123`
* **Role Manager (Hanya Melihat Laporan)**
  * **Email:** `manager@telkomsel.com`
  * **Password:** `password123`

---

## 📡 Dokumentasi REST API

Semua request REST API mengembalikan data berformat JSON.

### 1. Get All Products
Mendapatkan semua daftar barang inventaris.
* **Endpoint:** `GET /api/products`
* **Response Contoh (200 OK):**
```json
{
  "status": "success",
  "message": "Daftar barang inventaris",
  "data": [
    {
      "id": 1,
      "code": "ELK-LPT-001",
      "name": "Laptop Lenovo ThinkPad",
      "category_id": 1,
      "stock": 12,
      "location": "Gudang IT",
      "condition": "Bagus",
      "image": "products/Thinkpad.jpg",
      "category": {
        "id": 1,
        "name": "Elektronik"
      }
    }
  ]
}
```

### 2. Get Product Detail
Mendapatkan informasi detail satu barang berdasarkan ID.
* **Endpoint:** `GET /api/products/{id}`
* **Response Contoh (200 OK):**
```json
{
  "status": "success",
  "message": "Detail barang inventaris",
  "data": {
    "id": 1,
    "code": "ELK-LPT-001",
    "name": "Laptop Lenovo ThinkPad",
    "category_id": 1,
    "stock": 12,
    "location": "Gudang IT",
    "condition": "Bagus",
    "image": "products/Thinkpad.jpg",
    "category": {
      "id": 1,
      "name": "Elektronik"
    }
  }
}
```
