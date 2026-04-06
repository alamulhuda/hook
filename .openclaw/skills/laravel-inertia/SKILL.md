---
name: laravel-inertia
description: Workspace skill for Laravel + Inertia.js + Vue 3 development in this project. Triggers when working on: (1) Laravel routes, controllers, models, or database, (2) Inertia.js pages, forms, or shared data, (3) Vue 3 components using Composition API, (4) Filament admin panel resources or pages, (5) Laravel Volt or Folio routes, (6) Jetstream or Breeze inertia setup.
---

# Laravel + Inertia.js Workspace Skill

## Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Vue 3 + Inertia.js + Tailwind CSS 3
- **Admin:** Filament 3
- **Build:** Vite 6
- **State:** Pinia

## Project Structure

```
app/
├── Filament/Resources/     # Admin panel resources
├── Http/
│   ├── Controllers/       # Laravel controllers
│   └── Requests/          # Form requests
├── Livewire/             # Livewire components
├── Models/               # Eloquent models
└── Services/             # Business logic
resources/
├── css/filament/admin/   # Filament custom styles
└── js/
    ├── Pages/            # Inertia pages (Vue)
    └── components/       # Vue components
routes/
└── web.php               # Main routes
```

## Key Patterns

### Inertia Page (Vue 3 Composition API)

```vue
<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
  item: Object,
  errors: Object
})

const form = useForm({
  name: props.item?.name ?? '',
})

function submit() {
  form.post('/route', {
    onSuccess: () => router.reload({ only: ['item'] })
  })
}
</script>
```

### Inertia Form with Validation

```vue
<script setup>
import { useForm } from '@inertiajs/vue3'
import Zod from 'zod'
import { toTypedSchema } from '@vee-validate/zod'

const schema = toTypedSchema(Zod.object({
  name: Zod.string().min(2),
  email: Zod.string().email(),
}))
</script>
```

### Inertia Shared Data

Set in controller:
```php
return Inertia::render('Page', [
  'data' => $model,
  'shared' => ['appName' => config('app.name')] // accessible via usePage()
]);
```

Access in Vue:
```vue
<script setup>
import { usePage } from '@inertiajs/vue3'
const { appName } = usePage().props.shared
</script>
```

### Filament Resource

Resources live in `app/Filament/Resources/`. Each resource has:
- `List` page (table view)
- `Create` / `Edit` pages (forms)
- `View` page (detail view)

### Database Models

Available models in this project:

**Inventory:** Produk, Brand, Kategori, StockOpname, StockAdjustment

**Transactions:** Penjualan, PenjualanItem, PenjualanJasa, Pembelian, PembelianItem

**HR:** Karyawan, GajiKaryawan, Absensi, Lembur, LiburCuti

**Accounting:** AkunTransaksi, JenisAkun, KodeAkun

**Service:** Jasa, PenjadwalanService, TukarTambah

**Members:** Member, Supplier

## Conventions

1. **Vue files use `<script setup>` with Composition API**
2. **Forms use VeeValidate + Zod for validation**
3. **Use `router.reload({ only: [...] })` after mutations**
4. **Tailwind v3** (not v4)
5. **Pinia for global state**

## References

- [Laravel Patterns](references/laravel-patterns.md) - Common patterns for this codebase
- [Inertia Patterns](references/inertia-patterns.md) - Inertia-specific patterns
