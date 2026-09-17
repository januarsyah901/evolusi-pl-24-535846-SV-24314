# evolusi-pl-24-535846-SV-24314

[![Deployment Pipeline](https://github.com/januarsyah901/evolusi-pl-24-535846-SV-24314/actions/workflows/deploy.yml/badge.svg)](https://github.com/januarsyah901/evolusi-pl-24-535846-SV-24314/actions/workflows/deploy.yml)

Repositori Praktikum Mata Kuliah **Konstruksi & Evolusi Perangkat Lunak (KEPL)** — Pertemuan 03: *Continuous Deployment untuk Laravel*.

- **Nama Mahasiswa:** Januarsyah Akbar
- **NIM:** 24/535846/SV/24314
- **Dosen Pengampu:** Galih Malela Damaraji, S.Pd., M.Eng.
- **Institusi:** Universitas Gadjah Mada

---

## 🏛️ Gambaran Proyek

Proyek ini adalah implementasi RESTful API Platform Transparansi Penanganan Kasus Hukum (**Sampaimana**) yang dibangun menggunakan framework **Laravel 11**. Sistem ini mengelola data kasus hukum (nomor kasus, judul, kategori perkara, tahapan status, serta keterangan proses hukum) dengan pengujian otomatis (*automated testing*) dan pipeline CI/CD empat tahap.

---

## 🚀 Alur Pipeline CI/CD (GitHub Actions)

Alur otomatisasi didefinisikan pada `.github/workflows/deploy.yml` dengan empat tahapan berurutan (*sequential jobs*) yang dirangkai menggunakan direktif `needs:`:

```text
[ build ] ───(needs)───> [ test ] ───(needs)───> [ staging ] ───(needs)───> [ production ]
  composer                 artisan                 echo                    hanya branch main
  install                  test                    simulasi                environment: production
```

1. **Job 1 (`build`):** Memasang dependensi produksi menggunakan `composer install --no-dev --optimize-autoloader` dan menyimpan *release artifact* folder `vendor/`.
2. **Job 2 (`test`):** Menjalankan pengujian otomatis PHPUnit via `php artisan test` dengan basis data SQLite in-memory, isolasi konfigurasi `.env`, dan `php artisan key:generate`.
3. **Job 3 (`staging`):** Melakukan simulasi deployment otomatis ke lingkungan server tiruan (*staging*) tanpa risiko.
4. **Job 4 (`production`):** Menjalankan deployment rilis ke server produksi. Job ini dilindungi dengan dua aturan:
   - **Branch Guard:** Hanya dieksekusi pada branch `main` (`if: github.ref == 'refs/heads/main'`). Pada branch fitur atau dev, job ini otomatis di-*skip*.
   - **Environment Protection:** Menggunakan GitHub Environment `production` dengan *required reviewer*.

---

## 📜 7 Langkah Deployment Script (`deploy.sh` - Slide 6)

Skrip `deploy.sh` menerapkan opsi `set -e` agar proses deployment langsung berhenti secara aman jika terjadi kegagalan pada salah satu perintah:

```bash
#!/usr/bin/env bash
set -e # berhenti bila ada perintah gagal
cd /var/www/aplikasi

# 1 · Kunci pintu — tampilkan halaman pemeliharaan
php artisan down --retry=60

# 2 · Ambil kode terbaru
git pull origin main

# 3 · Pasang dependensi (tanpa paket dev)
composer install --no-dev --optimize-autoloader

# 4 · Ubah skema basis data. --force = jangan tanya
php artisan migrate --force

# 5 · Bangun ulang cache dengan kode & config baru
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6 · Muat ulang pekerja antrean
php artisan queue:restart

# 7 · Buka pintu kembali
php artisan up
```

---

## 🛠️ Menjalankan Pengujian Lokal

```bash
# Salin konfigurasi lingkungan
cp .env.example .env

# Pasang dependensi
composer install

# Buat application key
php artisan key:generate

# Jalankan automated tests
php artisan test
```
