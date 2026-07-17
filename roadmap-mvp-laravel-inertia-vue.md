# Roadmap MVP: Dashboard & Frontend Laravel + Inertia + Vue

> Target: solo developer / tim kecil, gaya "vibe coding" (iteratif, cepat, AI-assisted), dari nol sampai production-ready.
> Stack asumsi: Laravel 12, PHP 8.2, Vue 3, Inertia.js, TailwindCSS, MySQL 8, Redis, Docker Compose.

---

## Fase 0 — Persiapan (1 hari)

**Tujuan:** environment siap, tidak ada blocker teknis di tengah jalan.

- [ ] Tentukan scope MVP secara tertulis — 1 halaman: fitur wajib vs fitur "nanti"
- [ ] Siapkan repo Git (branch `main` + `dev`)
- [ ] Siapkan local environment: PHP 8.2, Composer, Node 20+, MySQL 8, Redis (bisa via Docker Compose dari awal biar konsisten dengan production nanti)
- [ ] Install Laravel 12 baru → pilih starter kit **Laravel Breeze (Inertia + Vue)** sebagai base auth, jangan bikin auth dari nol

```bash
composer create-project laravel/laravel nama-app
cd nama-app
composer require laravel/breeze --dev
php artisan breeze:install vue
```

- [ ] Commit awal ("initial scaffold")

---

## Fase 1 — Arsitektur & Struktur Project (1–2 hari)

**Tujuan:** fondasi yang tidak perlu dibongkar lagi saat fitur bertambah.

- [ ] Tentukan struktur folder frontend:
  ```
  resources/js/
    Pages/          → 1 file per route Inertia
    Layouts/        → AuthenticatedLayout, GuestLayout
    Components/     → komponen reusable (Button, Table, Modal, dst)
    Composables/     → logic reusable (useForm helpers, usePagination, dst)
  ```
- [ ] Setup **shadcn-vue** atau komponen Tailwind custom sebagai design system dasar — jangan bikin styling dari nol tiap komponen
- [ ] Setup **Pinia** untuk state global (misalnya auth user, notifikasi, sidebar toggle) — hanya jika benar-benar perlu, jangan taruh semua state di Pinia
- [ ] Setup **VeeValidate + Zod** untuk validasi form di frontend (opsional untuk MVP awal, bisa pakai validasi Laravel dulu via Inertia errors)
- [ ] Definisikan konvensi:
  - Controller → Resource Controller pattern
  - Response ke Inertia selalu pakai `Inertia::render()`, hindari logic berat di controller (pindahkan ke Service/Action class)
  - Naming: `Pages/Products/Index.vue`, `Pages/Products/Create.vue`, dst — 1:1 dengan route Laravel

**Checkpoint:** bisa `php artisan serve` + `npm run dev`, halaman login/register Breeze jalan.

---

## Fase 2 — Core Backend: Auth, Role, Model Utama (2–4 hari)

- [ ] Sesuaikan tabel `users` (tambah field yang dibutuhkan: role, status, dll)
- [ ] Setup role/permission — kalau MVP sederhana cukup kolom `role` enum, kalau butuh granular pakai **spatie/laravel-permission**
- [ ] Buat migration + model untuk entitas inti (misal: Product, Order, Customer — sesuaikan domain)
- [ ] Buat Factory + Seeder untuk data dummy (WAJIB — biar bisa develop UI tanpa nunggu data asli)
- [ ] Middleware dasar: auth, role check, redirect unauthenticated ke login

**Checkpoint:** `php artisan migrate:fresh --seed` jalan bersih, bisa login dengan user dummy.

---

## Fase 3 — Dashboard Shell & Navigasi (2–3 hari)

Ini fase yang paling menentukan "rasa" aplikasi, kerjakan sebelum fitur detail.

- [ ] Layout dashboard: sidebar + topbar + content area (pakai `AuthenticatedLayout` dari Breeze sebagai basis, modifikasi)
- [ ] Sidebar navigasi dinamis (bisa collapse, active state sesuai route)
- [ ] Breadcrumb komponen
- [ ] Komponen umum yang dipakai berulang, siapkan lebih dulu:
  - Table (dengan sorting, pagination — pertimbangkan **TanStack Table** kalau butuh fitur kompleks)
  - Modal / Dialog
  - Toast/notification
  - Empty state, loading state, skeleton
- [ ] Halaman dashboard utama (widget/summary card) — bisa pakai data dummy dulu

**Checkpoint:** navigasi antar halaman mulus, layout konsisten, tidak ada flash/flicker (manfaatkan Inertia persistent layout).

---

## Fase 4 — Fitur Utama / CRUD Modul MVP (1–3 minggu, tergantung scope)

Kerjakan modul satu per satu, tiap modul selesai end-to-end sebelum pindah ke modul berikutnya (bukan bikin semua backend dulu baru semua frontend).

Per modul, urutan kerja yang efisien untuk "vibe coding":
1. Migration + Model + Factory
2. Controller (index, create, store, edit, update, destroy) — pakai Form Request untuk validasi
3. Page Vue: List (table + filter + search + pagination)
4. Page Vue: Create/Edit (form)
5. Test manual langsung di browser sebelum lanjut modul lain

- [ ] Modul 1: __________
- [ ] Modul 2: __________
- [ ] Modul 3: __________
- [ ] Relasi antar modul (misal Order → Product) — pastikan foreign key & UI-nya konsisten

**Tips vibe coding tetap rapi:**
- Jangan skip Form Request validation walau buru-buru — ini sering jadi sumber bug production
- Pakai Laravel Policy untuk otorisasi per-model kalau ada multi-role
- Commit per modul selesai, jangan menumpuk commit besar

---

## Fase 5 — Polishing UX (2–4 hari)

- [ ] Loading state di semua form submit (pakai `Inertia.js` progress indicator / `router.visit` events)
- [ ] Error handling: 404, 403, 500 custom page
- [ ] Konfirmasi delete (modal, bukan langsung hapus)
- [ ] Flash message sukses/gagal konsisten di semua aksi
- [ ] Responsive check — minimal tablet & mobile untuk halaman yang sering diakses dari HP
- [ ] Dark mode (opsional, skip kalau MVP mengejar waktu)

---

## Fase 6 — Testing & QA (2–3 hari)

- [ ] Feature test untuk flow kritis (login, CRUD modul utama) pakai Pest/PHPUnit
- [ ] Cek N+1 query — pakai `Laravel Debugbar` atau `Telescope` selama development, **matikan di production**
- [ ] Cek validasi form — coba input invalid, cek pesan error jelas
- [ ] Cek authorization — user tanpa akses tidak bisa akses route/data orang lain
- [ ] Review `.env.example` — pastikan semua env var yang dibutuhkan terdaftar

---

## Fase 7 — Optimisasi Sebelum Deploy (1–2 hari)

- [ ] `php artisan optimize` (config, route, view cache)
- [ ] Build frontend production: `npm run build`
- [ ] Setup **Redis** untuk cache & session (bukan file-based) — sudah biasa dipakai, tinggal pastikan config `.env` benar
- [ ] Queue untuk job berat (kirim email, generate report) — pakai `database` atau `redis` driver, jalankan `queue:work` via supervisor/systemd
- [ ] Compress asset image, cek bundle size frontend (`npm run build -- --report` kalau pakai Vite bundle analyzer)
- [ ] Set `APP_DEBUG=false`, `APP_ENV=production` di env production

---

## Fase 8 — Deployment (1–2 hari)

Sesuai dengan setup yang biasa dipakai: Docker Compose + self-hosted (TrueNAS Scale) + Cloudflare Tunnel.

- [ ] Dockerfile multi-stage (composer install → npm build → PHP-FPM image ramping)
- [ ] `docker-compose.yml`: app (PHP-FPM), nginx, mysql, redis, queue worker sebagai service terpisah
- [ ] Setup `.env` production — **hati-hati karakter `$` di password**, wrap pakai single quote atau escape
- [ ] Migration production: `php artisan migrate --force` (jangan `migrate:fresh` di production!)
- [ ] Setup Cloudflare Tunnel ke domain production
- [ ] SSL otomatis via Cloudflare (mode Full/Full Strict)
- [ ] Cek queue worker jalan sebagai service persistent (systemd/supervisor), bukan proses manual

**Checkpoint go-live:** aplikasi bisa diakses via domain publik, login jalan, data persist setelah restart container.

---

## Fase 9 — Post-Launch (ongoing)

- [ ] Setup backup database otomatis (cron `mysqldump` ke storage terpisah, atau snapshot TrueNAS)
- [ ] Monitoring dasar: uptime check (bisa pakai UptimeRobot/Cloudflare) + log error (Laravel log ke file, cek berkala)
- [ ] Kumpulkan feedback user pertama minggu 1–2 → prioritaskan bug fix di atas fitur baru
- [ ] Backlog fitur "nanti" dari Fase 0 → mulai direalisasikan bertahap (versi 1.1, 1.2, dst)

---

## Ringkasan Timeline (estimasi solo dev, scope MVP sedang)

| Fase | Estimasi |
|---|---|
| 0. Persiapan | 1 hari |
| 1. Arsitektur | 1–2 hari |
| 2. Core Backend | 2–4 hari |
| 3. Dashboard Shell | 2–3 hari |
| 4. Fitur Utama | 1–3 minggu |
| 5. Polishing UX | 2–4 hari |
| 6. Testing | 2–3 hari |
| 7. Optimisasi | 1–2 hari |
| 8. Deployment | 1–2 hari |
| **Total** | **±3–5 minggu** |

**Prinsip utama supaya tetap "vibe coding" tapi tidak berantakan:**
1. Selesaikan 1 modul end-to-end sebelum modul lain — jangan horizontal (semua backend dulu)
2. Jangan skip validasi & authorization walau demi kecepatan
3. Commit kecil dan sering
4. Data dummy dari Fase 2, jangan nunggu data asli untuk develop UI
5. Docker Compose dari environment lokal, biar tidak ada surprise saat deploy
