# Cecile Private Collection — Laravel Web App

Website e-commerce perhiasan dengan fitur personalisasi desain menggunakan Generative AI.

> **Skripsi:** Pengembangan Website E-Commerce Perhiasan dengan Fitur Personalisasi Desain Menggunakan Generative AI
> **Penulis:** Matthew Tricahyo (C14220080) — Universitas Kristen Petra

---

## Requirement

| Software | Versi |
|----------|-------|
| [Laragon](https://laragon.org/download/) | Latest |
| PHP | ^8.3 |
| Composer | ^2.8 |
| MySQL | ^8.0 |
| Node.js | ^22.x LTS |
| NPM | Included with Node.js |

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/MatthewTri/website-perhiasan.git
cd website-perhiasan
```

### 2. Install Dependency PHP

```bash
composer install
```

### 3. Install Dependency JavaScript

```bash
npm install
```

### 4. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Lalu buka file `.env` dan isi variabel berikut:

```dotenv
# Database (sesuaikan dengan konfigurasi Laragon kamu)
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_kamu
DB_USERNAME=root
DB_PASSWORD=

# Email (gunakan Gmail SMTP)
MAIL_USERNAME=emailkamu@gmail.com
MAIL_PASSWORD=app_password_gmail_kamu
MAIL_FROM_ADDRESS=emailkamu@gmail.com
MAIL_ADMIN_EMAIL=email_admin@gmail.com

# AI Microservice (jalankan repo microservice dulu, lalu isi URL-nya)
AI_SERVICE_URL=http://localhost:PORT_MICROSERVICE
INTERNAL_AI_KEY=isi_dengan_key_yang_sama_di_microservice

# PayPal (gunakan sandbox untuk development)
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=client_id_dari_developer_paypal
PAYPAL_SANDBOX_CLIENT_SECRET=client_secret_dari_developer_paypal
```

> 💡 Untuk `MAIL_PASSWORD`, gunakan **App Password** dari Google (bukan password Gmail biasa).
> Buat di: [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)

> 💡 Untuk PayPal sandbox, daftar di [developer.paypal.com](https://developer.paypal.com)

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Buat Database

Buka **phpMyAdmin** di Laragon (atau Laragon → Database), lalu buat database baru sesuai nama yang kamu isi di `DB_DATABASE`.

### 7. Jalankan Migration & Seeder

```bash
php artisan migrate --seed
```

### 8. Buat Storage Link

```bash
php artisan storage:link
```

---

## Menjalankan Aplikasi

Pastikan **Laragon sudah aktif** (Apache + MySQL menyala), lalu buka **3 terminal terpisah**:

**Terminal 1 — Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 — Tailwind CSS (Vite):**
```bash
npm run dev
```

**Terminal 3 — Queue Worker** *(diperlukan untuk fitur kirim email):*
```bash
php artisan queue:work
```

Akses aplikasi di browser: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Catatan Tambahan

- Pastikan repo **AI Microservice** juga sudah berjalan sebelum menggunakan fitur Generative AI.
- Fitur email (notifikasi order, dll) membutuhkan **Terminal 3** (`queue:work`) aktif.
- Untuk mode production, ubah `APP_ENV=production` dan `APP_DEBUG=false` di `.env`.

---

## Lisensi

Proyek ini dibuat untuk keperluan Tugas Akhir (Skripsi) di Universitas Kristen Petra Surabaya.
