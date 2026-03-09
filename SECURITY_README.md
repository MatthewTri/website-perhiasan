# 🔐 Sistem Keamanan Authentication - Laravel Best Practices

## ✅ Fitur Keamanan yang Sudah Diimplementasikan

### 1. **Remember Me Functionality**
✅ Secure cookie-based persistent login
✅ Token tersimpan aman di database
✅ Otomatis expired setelah jangka waktu tertentu

### 2. **Password Reset System**
✅ Token-based reset dengan hash SHA-256
✅ Token expire dalam 60 menit
✅ One-time use token (auto-delete setelah digunakan)
✅ Force logout semua device setelah reset password

### 3. **Rate Limiting (Anti Brute Force)**
✅ Login: Max 5 attempts per menit per IP
✅ Password Reset: Max 3 requests per 10 menit per IP
✅ Auto-clear attempts setelah login berhasil

### 4. **Session Security**
✅ Session regeneration setelah login (prevent session fixation)
✅ Session invalidation saat logout
✅ CSRF token regeneration

### 5. **Email Security**
✅ Professional HTML email template
✅ Security warnings di email
✅ Alternative link untuk compatibility

---

## 🚀 Cara Menggunakan

### **Remember Me:**
1. User centang checkbox "Remember me" saat login
2. Laravel otomatis simpan token di cookie
3. Durasi: 5 tahun (default Laravel)

### **Forgot Password:**
1. User klik "Forgot your password?" di halaman login
2. Masukkan email
3. Sistem kirim link reset ke email
4. User klik link, masukkan password baru
5. Semua session user di-logout otomatis (security)

---

## ⚙️ Konfigurasi Email (WAJIB!)

Untuk mengirim email reset password, konfigurasi SMTP di `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Untuk Gmail:**
1. Buka https://myaccount.google.com/apppasswords
2. Buat App Password baru
3. Gunakan password tersebut di `MAIL_PASSWORD`

**Alternatif SMTP Providers:**
- Gmail (gratis, 500 email/hari)
- Mailgun (10,000 email/bulan gratis)
- SendGrid (100 email/hari gratis)
- Mailtrap (development only)

---

## 🛡️ Best Practices yang Diterapkan

### **1. Password Hashing**
- ✅ Bcrypt algorithm (Laravel default)
- ✅ Auto-salt generation
- ✅ 10 rounds (secure & performant)

### **2. Token Security**
- ✅ Random 64 characters token
- ✅ Hashed di database (tidak plaintext)
- ✅ Time-based expiration
- ✅ One-time usage

### **3. Input Validation**
- ✅ Email format validation
- ✅ Email DNS check (`email:dns`)
- ✅ Password length minimum 6 characters
- ✅ Password confirmation matching

### **4. Rate Limiting**
- ✅ IP-based throttling
- ✅ Exponential backoff
- ✅ Clear attempts on success

### **5. User Feedback**
- ✅ Clear error messages
- ✅ Success confirmations
- ✅ Loading states
- ✅ Auto-hide alerts (UX)

---

## 🔒 Security Checklist

- [x] Password hashing (Bcrypt)
- [x] Remember Me token
- [x] Password reset functionality
- [x] Rate limiting (login & reset)
- [x] Session regeneration
- [x] CSRF protection
- [x] Token expiration
- [x] Input validation
- [ ] Two-Factor Authentication (2FA) - Future
- [ ] Email verification - Future
- [ ] Password strength meter - Future
- [ ] Account lockout after X failed attempts - Future

---

## 📱 Testing Guide

### **Test Remember Me:**
```bash
1. Login dengan checkbox "Remember me" dicentang
2. Close browser completely
3. Buka browser lagi, akses website
4. Harus tetap login (tidak diminta login lagi)
```

### **Test Forgot Password:**
```bash
1. Klik "Forgot password" di login page
2. Masukkan email yang terdaftar
3. Cek inbox email
4. Klik link reset (valid 60 menit)
5. Masukkan password baru
6. Login dengan password baru
```

### **Test Rate Limiting:**
```bash
# Login Rate Limit
1. Login dengan password salah 5x berturut-turut
2. Attempt ke-6 akan error: "Too many login attempts"
3. Tunggu 60 detik
4. Coba login lagi

# Password Reset Rate Limit
1. Request reset password 3x berturut-turut
2. Request ke-4 akan error: "Too many requests"
3. Tunggu 10 menit
4. Coba request lagi
```

---

## 🐛 Troubleshooting

### **Email Tidak Terkirim:**
```bash
# Check mail configuration
php artisan config:clear
php artisan cache:clear

# Test email (buat test route)
Route::get('/test-mail', function() {
    Mail::raw('Test email', function($message) {
        $message->to('test@example.com')->subject('Test');
    });
    return 'Email sent!';
});
```

### **Remember Me Tidak Berfungsi:**
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Cek .env
SESSION_DRIVER=database  # atau file
SESSION_LIFETIME=120
```

### **Token Expired Terlalu Cepat:**
```php
// Ubah di ForgotPasswordController.php line 130
if (now()->diffInMinutes($passwordReset->created_at) > 60) {
    // Ubah 60 menjadi nilai lebih besar (misal 120 = 2 jam)
}
```

---

## 📈 Future Enhancements

### **1. Two-Factor Authentication (2FA)**
- SMS OTP
- Google Authenticator
- Email OTP

### **2. Email Verification**
- Verify email setelah register
- Prevent unverified user login

### **3. Password Strength Meter**
- Real-time strength indicator
- Enforce strong password policy

### **4. Account Security**
- Login history/activity log
- Suspicious activity detection
- Device management

### **5. Social Login**
- Google OAuth
- Facebook Login
- GitHub Login

---

## 🎓 Referensi

- [Laravel Authentication](https://laravel.com/docs/authentication)
- [Laravel Password Reset](https://laravel.com/docs/passwords)
- [OWASP Authentication Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html)
- [Laravel Rate Limiting](https://laravel.com/docs/rate-limiting)

---

**Dibuat dengan standar keamanan kelas dunia 🌍**
**Last Updated:** {{ date('Y-m-d') }}
