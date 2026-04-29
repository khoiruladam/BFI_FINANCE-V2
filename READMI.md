    # 🏦 BFI Finance - Dashboard Management System

Project ini adalah Sistem Manajemen Data Unit Kendaraan dan Simulasi Pembiayaan yang dibangun khusus untuk **BFI Finance**. Aplikasi ini menggunakan arsitektur modern berbasis Docker dengan antarmuka **Glassmorphism UI** yang mewah dan responsif.

---

## 🚀 Fitur Utama

* **Premium Dashboard:** Tampilan dashboard dengan efek kaca (glassmorphism) yang modern dan nyaman di mata.
* **Management Unit:** CRUD (Create, Read, Update, Delete) data unit kendaraan secara real-time.
* **Automated Calculation:** Simulasi otomatis pencairan dana 95% dengan animasi angka.
* **Dynamic AJAX Dropdown:** Integrasi dinamis antara Kategori -> Merk -> Unit tanpa reload halaman.
* **Bulk Import:** Fitur unggah data dalam jumlah banyak menggunakan file format CSV.
* **Mobile-First Design:** Tampilan tabel dan sidebar yang dioptimalkan sepenuhnya untuk perangkat smartphone.
* **Secure Authentication:** Sistem login admin dengan proteksi session dan konfirmasi logout via SweetAlert2.

---

## 🛠️ Tech Stack

* **Language:** PHP 8.2 (Native)
* **Database:** MySQL 8.0
* **Containerization:** Docker & Docker Compose
* **Frontend:** Bootstrap 5.3, JavaScript (Vanilla ES6+), CSS3 Custom Animations
* **UI Assets:** FontAwesome 6, Google Fonts (Plus Jakarta Sans)
* **Library:** SweetAlert2 (Pop-up alerts)

---
## 📂 Struktur File CSV (Untuk Import)

Untuk menggunakan fitur **Import Unit**, pastikan file `.csv` Anda memiliki urutan kolom sebagai berikut:
`merk_id, nama_unit, model, tahun_keluaran, harga_pasar`

Contoh isi file:
```csv
1,Avanza,G Luxury,2022,210000000
1,Innova,Reborn,2021,350000000
2,Brio,Satya,2023,165000000