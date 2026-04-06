# Inertia.js Migration Project - Progress

## Project Overview
Migrating Laravel Filament to Inertia.js + Vue 3 + shadcn/ui for enterprise application with 3 panels (Admin, Akunting, POS), 42 Filament resources, 48 models.

## Current Issue: Blank White Page on /app/dashboard

**Root Cause:** The `@vite` directive in `app.blade.php` connects to Vite dev server (`127.0.0.1:5173`) when `APP_DEBUG=true`, but the Vite dev server has ENOSPC error and isn't running properly.

**Latest Status:** 
- Build works correctly (`npm run inertia:build` produces `public/build/assets/app-*.js` at ~215KB and `app-*.css` at ~114KB)
- HTML contains correct Inertia `data-page` JSON
- But `@vite` directive still loads from dev server instead of built assets

**Solution Options:**
1. Set `APP_DEBUG=false` in `.env` and run `php artisan config:clear`
2. Or run `npm run inertia:dev` to start Vite dev server alongside

## What Was Fixed So Far

### Build Configuration
- Removed conflicting `vite.config.js` (was overriding `vite.config.ts`)
- Fixed `vite.config.ts` to use `laravel()` plugin properly
- CSS is now bundled via import in `app.ts`: `import '../css/inertia.css'`

### Vue Component Issues Fixed
- `VariantProps<typeof buttonVariants>` - changed to inline string union types
- `HTMLAttributes` extends issues - simplified prop definitions
- Barrel file imports for `dropdown-menu` and `sheet` components (converted to directory structure with index.vue)
- Created missing `card-title.vue` component

### App.ts Resolve Function
```typescript
const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue')

resolve: (name) => {
    const page = pages[`./pages/${name}.vue`]
    if (!page) {
        return pages['./pages/app/dashboard.vue']
    }
    return page()  // Must call as function for lazy loading
}
```

## Files Created/Modified

### Vue Pages (Inertia)
- `resources/js/pages/app/dashboard.vue`
- `resources/js/pages/app/minimal-test.vue` (test page)
- `resources/js/pages/app/admin/users/Index.vue`
- `resources/js/pages/app/admin/master-data/*/Index.vue` (6 files)
- `resources/js/pages/app/akunting/*/Index.vue`
- `resources/js/pages/app/pos/*/Index.vue`

### Controllers
- `app/Http/Controllers/App/DashboardController.php`
- `app/Http/Controllers/App/SupplierController.php`
- `app/Http/Controllers/App/MemberController.php`
- `app/Http/Controllers/App/JasaController.php`
- `app/Http/Controllers/App/GudangController.php`
- `app/Http/Controllers/App/AkunTransaksiController.php`
- `app/Http/Controllers/App/KategoriController.php`

### Routes
- `/app/dashboard` - Dashboard (requires auth)
- `/app/admin/*` - Admin panel pages
- `/app/akunting/*` - Akunting pages
- `/app/pos/*` - POS pages
- `/test-inertia` - Test route (can be removed)
- `/test-minimal` - Minimal Vue test (can be removed)

### Vue Components
- `resources/js/components/ui/button.vue` - Fixed prop types
- `resources/js/components/ui/badge.vue` - Fixed prop types
- `resources/js/components/ui/card.vue` - Fixed prop types
- `resources/js/components/ui/card-title.vue` - Created
- `resources/js/components/ui/dropdown-menu/` - Converted to directory
- `resources/js/components/ui/sheet/` - Converted to directory
- `resources/js/components/layout/AppLayout.vue` - Fixed duplicate defineProps
- `resources/js/components/layout/PageHeader.vue`

### Configuration
- `vite.config.ts` - Properly configured with laravel plugin
- `resources/views/app.blade.php` - Uses `@vite('resources/css/inertia.css')` and `@vite('resources/js/app.ts')`
- `resources/js/app.ts` - Imports CSS, proper resolve function
- `bootstrap/app.php` - Added `HandleInertiaRequests` middleware to web group

## Phase 1 Completion (2026-04-06)

### ✅ Completed
- All 8 Master Data resources migrated (Produk, Brand, Kategori, Supplier, Member, Jasa, Gudang, AkunTransaksi)
- Reusable components: DataTable, FormField, Repeater, RelationSelect, CurrencyInput
- **MediaPicker component** - Image upload with drag & drop
- **MapPicker component** - Leaflet + OpenStreetMap location picker

### 🔄 Current Status (2026-04-06)
- Dashboard: ✅ Working with real data + date filter
- Blank page issue: ✅ Fixed (APP_DEBUG=false)
- Vue pages: ✅ All Inertia pages rendering

## Next Steps When Resuming

1. **Continue Phase 2** - Transactions (Penjualan, Pembelian, Tukar Tambah)
2. **Fix storage permissions** - `/var/www/storage` owned by wrong user in container
3. **Add real-time features** - Laravel Echo setup

## Docker
- Containers: arabica-app, arabica-nginx, arabica-db
- Storage broken in container (owner issue)
- Vite dev server broken due to ENOSPC (file watcher limits)
