# Panduan Deploy SiKoDung (Gratis)

Kode sudah disiapkan (git repo lokal sudah dibuat). Langkah selanjutnya butuh kamu membuat akun di beberapa layanan — bagian ini tidak bisa dibantu langsung oleh Claude (pembuatan akun harus dilakukan pemilik).

## 1. Push kode ke GitHub

1. Buka https://github.com/new, buat repository baru (nama bebas, misal `sikodung`), **jangan** centang "Add README" (biar tidak konflik dengan yang sudah ada).
2. Setelah repo dibuat, GitHub akan kasih URL seperti `https://github.com/USERNAME/sikodung.git`.
3. Jalankan di terminal (folder project ini):
   ```bash
   git remote add origin https://github.com/USERNAME/sikodung.git
   git branch -M main
   git push -u origin main
   ```
   (Ganti `USERNAME` dan nama repo sesuai punya kamu. Saat push, browser/terminal akan minta login GitHub.)

## 2. Buat database MySQL gratis

Gunakan **Clever Cloud** — daftar di https://www.clever-cloud.com, buat addon MySQL "DEV" (gratis, ~5MB).

> ⚠️ Sebelumnya panduan ini menyarankan db4free.net, tapi domain tersebut sudah tidak lagi menjadi layanan database (kontennya sudah berubah total, tidak jelas kepemilikannya sekarang) — **jangan gunakan/daftar di sana**.

Setelah dibuat, catat: **host**, **port**, **nama database**, **username**, **password**.

## 3. Pindahkan data

1. Buka phpMyAdmin lokal (XAMPP) → database `sikodung` → tab **Export** → format SQL → download.
2. Arahkan phpMyAdmin lokal ke host Clever Cloud (edit `C:\xampp\phpMyAdmin\config.inc.php`, ganti `host` ke alamat dari Clever Cloud) → buat database kosong → tab **Import** → upload file tadi.

## 4. Deploy ke Render

1. Daftar di https://render.com (bisa pakai akun GitHub langsung).
2. New → Web Service → pilih repo `sikodung` yang sudah di-push.
3. Isi:
   - **Runtime**: PHP
   - **Build Command**: `composer install --no-dev --optimize-autoloader`
   - **Start Command**: `php artisan serve --host 0.0.0.0 --port $PORT`
   - **Publish/Root Directory**: kosongkan (default), tapi pastikan `public/` dikenali sebagai document root (Render biasanya deteksi otomatis untuk Laravel; kalau tidak, cek dokumentasi Render untuk "Laravel deployment").

## 5. Environment Variables di Render

Masuk ke tab **Environment** di dashboard Render, isi persis seperti ini (nilai yang ada `<...>` diganti data kamu):

```
APP_NAME=SiKoDung
APP_ENV=production
APP_KEY=<generate dengan: php artisan key:generate --show>
APP_DEBUG=false
APP_URL=<URL yang diberikan Render, misal https://sikodung.onrender.com>

DB_CONNECTION=mysql
DB_HOST=<host dari provider MySQL>
DB_PORT=<port dari provider MySQL>
DB_DATABASE=<nama database>
DB_USERNAME=<username>
DB_PASSWORD=<password>

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=adminsimadang@gmail.com
MAIL_PASSWORD=<lihat App Password Gmail di file .env lokal kamu, baris MAIL_PASSWORD>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=adminsimadang@gmail.com
MAIL_FROM_NAME="Simadang Konservasi Dugong"

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
```

> Catatan: `APP_KEY` harus di-generate baru (jangan pakai punya lokal). Jalankan `php artisan key:generate --show` di lokal untuk dapat nilainya, lalu tempel ke Render.

## 6. Setelah deploy pertama berhasil

Buka **Shell** di dashboard Render (atau jalankan sebagai bagian dari build/start command), jalankan:
```bash
php artisan migrate --force
php artisan storage:link
```

## Catatan lain
- File `database/sikodung.sql` di repo ini adalah skrip import lama (skema sebelum dinormalisasi) — **tidak dipakai lagi** dan tidak akan cocok dengan struktur tabel sekarang. Aman diabaikan, atau boleh dihapus.
- Foto laporan yang sudah ada di lokal (`storage/app/public/laporan/`) tidak ikut ter-push ke GitHub (memang standar Laravel) — jadi foto lama tidak otomatis muncul di versi online. Foto baru yang diupload lewat form laporan setelah online tetap tersimpan normal, hanya berisiko hilang kalau server Render gratis di-restart (disk sementara/tidak permanen).
