# Filament to Inertia.js + shadcn/ui Migration Plan

## Project Overview

- **Current Stack**: Laravel + Filament (3 panels: Admin, Akunting, POS)
- **Target Stack**: Laravel + Inertia.js (Vue 3) + shadcn/ui + TanStack Table + VeeValidate + Zod
- **Reason for Migration**: Performance constraints
- **App Complexity**: Large (50+ pages, 42 Filament resources, 48 models)
- **Migration Approach**: Incremental (parallel stacks, shared backend)

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Laravel Application                       │
├─────────────────────┬───────────────────────────────────────┤
│   Filament (legacy) │         Inertia (new)                 │
│   /admin/*          │         /app/*                         │
│   /akunting/*       │         (shares all Models)           │
│   /pos/*            │         (shares Controllers)          │
└─────────────────────┴───────────────────────────────────────┘
```

**URL Structure**:
- `yourdomain.com/admin/*` → Filament (existing, continue building)
- `yourdomain.com/app/*` → Inertia (new frontend)

**Key Principle**: Keep Filament running for active development while building Inertia frontend. Share backend code (Models, Controllers, Services).

---

## Technology Stack

### Frontend
| Package | Purpose | Version |
|---------|---------|---------|
| Vue 3 | UI Framework | ^3.4 |
| Inertia.js | SPA routing | ^2.0 |
| TypeScript | Type safety | ^5.0 |
| shadcn/ui | UI components | latest |
| Tailwind CSS | Styling | ^3.4 |
| TanStack Table | Data tables | ^8.x |
| TanStack Form | Form management | ^0.x |
| VeeValidate | Form validation | ^4.x |
| Zod | Schema validation | ^3.x |
| Pinia | State management | ^2.x |
| VueUse | Vue utilities | ^10.x |
| Recharts | Charts | ^2.x |
| FullCalendar | Calendar | ^6.x |
| Lucide Vue | Icons | latest |

### Real-time
| Package | Purpose |
|---------|---------|
| Laravel Echo | Real-time events |
| Pusher JS | WebSocket client |
| Pusher / Soketi | WebSocket server |

---

## Phased Implementation Plan

### Phase 0: Proof of Concept (Weeks 1-2)
**Module**: User Management

**Goal**: Establish patterns and component library

| Day | Tasks |
|-----|-------|
| 1-2 | Install Vue 3 + Inertia + TypeScript + Vite |
| 1-2 | Configure shadcn/ui + Tailwind + dark mode |
| 1-2 | Install TanStack Table, TanStack Form, VeeValidate, Zod, Pinia |
| 1-2 | Create basic layout component structure |
| 3-4 | Create `/app/login` page |
| 3-4 | Create `/app/register` page |
| 3-4 | Integrate with Laravel Sanctum/Fortify |
| 5 | Create AdminSidebar component |
| 5 | Create PageHeader, Breadcrumbs components |
| 5-6 | Configure Inertia routing with layouts |
| 5-6 | Add role-based navigation |
| 7-10 | User list page with TanStack Table + filters |
| 7-10 | User create/edit page with VeeValidate + Zod |
| 7-10 | Role assignment UI |

**Deliverables**:
- `/app/login` - Login page
- `/app/register` - Registration page
- `/app/users` - User list with DataTable
- `/app/users/create` - Create user form
- `/app/users/:id/edit` - Edit user form

---

### Phase 1: Foundation (Weeks 3-5)
**Module**: Master Data

**Resources**: 8 resources (Produk, Brand, Kategori, Supplier, Member, Jasa, Gudang, AkunTransaksi)

| Week | Tasks |
|------|-------|
| 3 | Build reusable `DataTable` component |
| 3 | Build reusable `FormField` component (VeeValidate wrapper) |
| 3 | Build `Repeater` component for nested array fields |
| 3 | Build `RelationSelect` component (searchable selects) |
| 3 | Build `CurrencyInput` component |
| 3 | Create Produk resource pages (list, create, edit, detail) |
| 4 | Create Brand, Kategori, Supplier resource pages |
| 4 | Create Member resource pages |
| 4 | Create Jasa, Gudang, AkunTransaksi resource pages |
| 5 | Media picker component (integrate existing media library) |
| 5 | Map picker component (Leaflet + shadcn/ui) |
| 5 | Code review, refactor, polish |

**Deliverables**:
- Reusable component library
- All 8 Master Data resources migrated

---

### Phase 2: Transactions (Weeks 6-10)
**Module**: Penjualan, Pembelian, TukarTambah

**Complexity**: Very High (nested repeaters, serial numbers, payment processing)

| Week | Tasks |
|------|-------|
| 6 | Penjualan list + detail pages |
| 6 | Penjualan create/edit form with nested items |
| 7 | Penjualan payment processing UI |
| 7 | Penjualan serial number management |
| 7 | Pembelian list + detail pages |
| 8 | Pembelian create/edit form |
| 8 | Pembelian payment processing |
| 8 | TukarTambah tabbed form |
| 9 | Relation manager UI patterns |
| 9 | Member/Employee selector modals |
| 10 | TukarTambah trade-in workflow |
| 10 | Code review, refactor, polish |

**Key Challenges**:
- Nested repeater state management
- Serial number tracking across inventory
- Payment processing UI
- Complex validation rules

---

### Phase 3: POS & Inventory (Weeks 11-14)
**Module**: POS, Inventory, Stock

| Week | Tasks |
|------|-------|
| 11 | POS cart interface (real-time) |
| 11 | POS product search |
| 11 | POS checkout flow |
| 12 | POS transaction history |
| 12 | Stock Adjustment form |
| 12 | Stock Opname form + approval workflow |
| 13 | Inventory dashboard with charts |
| 13 | Low stock alerts |
| 13 | Reorder management |
| 14 | POS activity log |
| 14 | Code review, refactor, polish |

---

### Phase 4: Accounting (Weeks 15-18)
**Module**: Akunting, Reports

**Resources**: 6 resources (JenisAkun, KodeAkun, InputTransaksiToko, LaporanLabaRugi, LaporanNeraca, LaporanInputTransaksi)

| Week | Tasks |
|------|-------|
| 15 | Chart of Accounts tree view |
| 15 | Input Transaksi form |
| 16 | Laba Rugi report (migrate LiveWire → Vue) |
| 16 | Neraca report (migrate LiveWire → Vue) |
| 16 | Custom LiveWire → Vue: LabaRugiPenjualanTable |
| 17 | Custom LiveWire → Vue: LabaRugiBebanTable |
| 17 | Custom LiveWire → Vue: LabaRugiPembelianTable |
| 17 | Export functionality (Excel/PDF) |
| 18 | Accounting dashboard |
| 18 | Code review, refactor, polish |

---

### Phase 5: HR & Attendance (Weeks 19-22)
**Module**: Absensi, Lembur, Cuti, Gaji

| Week | Tasks |
|------|-------|
| 19 | Absensi check-in/out with geolocation |
| 19 | Absensi history table |
| 19 | Geolocation hooks + shadcn/ui Map |
| 20 | Lembur (overtime) management |
| 20 | Cuti (leave) request workflow |
| 20 | Gaji Karyawan payroll UI |
| 21 | Attendance reports with charts |
| 21 | Laporan Absensi |
| 22 | Laporan Pengajuan Cuti |
| 22 | Code review, refactor, polish |

---

### Phase 6: Scheduling (Weeks 23-26)
**Module**: Penjadwalan, Kalender, Tasks

| Week | Tasks |
|------|-------|
| 23 | FullCalendar integration |
| 23 | Kalender Events CRUD |
| 23 | Drag-drop event creation |
| 24 | Service scheduling Kanban board |
| 24 | Task scheduling Kanban board |
| 24 | Task comments (real-time with Echo) |
| 25 | Delivery scheduling with map view |
| 25 | Crosscheck management tables |
| 26 | Code review, refactor, polish |

---

### Phase 7: Dashboard & Widgets (Weeks 27-29)
**Module**: All remaining widgets

| Week | Tasks |
|------|-------|
| 27 | Admin dashboard with charts |
| 27 | AdvancedStatsOverview replacement |
| 27 | MonthlyRevenueTrendChart replacement |
| 28 | TopSellingProductsTable |
| 28 | ActiveMembersTable |
| 28 | LowStockProductsTable |
| 29 | Weather widget |
| 29 | Welcome widget |
| 29 | Code review, polish |

---

### Phase 8: Polish & Production (Weeks 30-32)
**Module**: Infrastructure

| Week | Tasks |
|------|-------|
| 30 | Global search (Laravel Scout) |
| 30 | Export functionality |
| 30 | Performance optimization |
| 31 | Lazy loading, virtual scrolling |
| 31 | Error handling + Sentry |
| 32 | Unit tests |
| 32 | Dusk tests for critical flows |
| 32 | Documentation |

---

## Timeline Summary

| Phase | Module | Duration | Week |
|-------|--------|----------|------|
| POC | User Management | 2 weeks | 1-2 |
| 1 | Master Data | 3 weeks | 3-5 |
| 2 | Penjualan, Pembelian, TukarTambah | 4 weeks | 6-10 |
| 3 | POS, Inventory, Stock | 3 weeks | 11-14 |
| 4 | Akunting, Reports | 4 weeks | 15-18 |
| 5 | HR, Attendance | 4 weeks | 19-22 |
| 6 | Scheduling | 4 weeks | 23-26 |
| 7 | Dashboard, Widgets | 3 weeks | 27-29 |
| 8 | Polish, Testing | 3 weeks | 30-32 |

**Estimated Total: 32 weeks (8 months)**

---

## Component Architecture

```
resources/js/
├── components/
│   ├── ui/                    # shadcn/ui components
│   ├── forms/
│   │   ├── FormField.vue      # VeeValidate wrapper
│   │   ├── Repeater.vue       # Nested array fields
│   │   ├── MemberSelect.vue   # Searchable relation select
│   │   ├── CurrencyInput.vue  # Money formatting
│   │   └── ProductSelect.vue
│   ├── tables/
│   │   ├── DataTable.vue      # TanStack Table wrapper
│   │   ├── TableFilters.vue   # Filter sidebar
│   │   └── BulkActions.vue
│   ├── layout/
│   │   ├── AdminSidebar.vue
│   │   ├── AdminHeader.vue
│   │   ├── PageHeader.vue
│   │   └── Breadcrumbs.vue
│   ├── Kanban/
│   │   ├── KanbanBoard.vue
│   │   ├── KanbanColumn.vue
│   │   └── KanbanCard.vue
│   ├── Calendar/
│   │   ├── CalendarView.vue
│   │   └── EventModal.vue
│   └── shared/
│       ├── NotificationToast.vue
│       ├── ConfirmDialog.vue
│       └── StatusBadge.vue
├── pages/
│   ├── app/
│   │   ├── Dashboard.vue
│   │   └── auth/
│   │       ├── Login.vue
│   │       └── Register.vue
│   ├── admin/
│   │   ├── users/
│   │   │   ├── Index.vue
│   │   │   ├── Create.vue
│   │   │   └── [id]/Edit.vue
│   │   └── master-data/
│   │       ├── produk/
│   │       ├── brand/
│   │       └── ...
│   ├── akunting/
│   │   ├── chart-of-accounts/
│   │   ├── input-transaksi/
│   │   └── laporan-laba-rugi/
│   └── pos/
│       ├── cart/
│       └── sales/
├── composables/
│   ├── useFormServer.ts       # Inertia form handling
│   ├── usePermissions.ts     # Spatie permission integration
│   ├── useRealtime.ts        # Laravel Echo integration
│   └── useToast.ts           # Toast notifications
└── lib/
    ├── validators/           # Zod schemas per resource
    │   ├── user.ts
    │   └── produk.ts
    └── utils.ts
```

---

## Key Implementation Patterns

### 1. DataTable Pattern (TanStack Table)
```vue
<template>
  <div class="space-y-4">
    <TableFilters v-model="filters" :columns="tableColumns" />
    <DataTable
      :data="users"
      :columns="columns"
      :pagination="pagination"
      @sort="handleSort"
      @page-change="handlePageChange"
    >
      <template #bulk-actions>
        <BulkActions :selected="selected" @delete="bulkDelete" />
      </template>
    </DataTable>
  </div>
</template>
```

### 2. Form Pattern (VeeValidate + Zod)
```vue
<template>
  <Form :validation-schema="userSchema" @submit="onSubmit">
    <FormField name="name" label="Name">
      <Input v-model="form.name" />
    </FormField>
    <FormField name="email" label="Email">
      <Input v-model="form.email" type="email" />
    </FormField>
    <Button type="submit" :loading="processing">Save</Button>
  </Form>
</template>
```

### 3. Nested Repeater Pattern
```vue
<template>
  <Repeater v-model="form.items" :template="itemTemplate" :min-items="1">
    <template #default="{ index }">
      <div class="grid grid-cols-12 gap-4">
        <FormField :name="`items.${index}.product_id`" class="col-span-6">
          <ProductSelect v-model="form.items[index].product_id" />
        </FormField>
        <FormField :name="`items.${index}.quantity`" class="col-span-3">
          <InputNumber v-model="form.items[index].quantity" />
        </FormField>
      </div>
    </template>
  </Repeater>
</template>
```

### 4. Real-time Pattern (Laravel Echo)
```ts
// composables/useRealtime.ts
export function useRealtime() {
  const notifications = ref([])
  
  Echo.private(`user.${userId}`)
    .notification((notification) => {
      notifications.value.push(notification)
      useToast().toast(notification.message)
    })
    
  return { notifications }
}
```

---

## Risk Mitigation

| Risk | Impact | Mitigation |
|------|--------|------------|
| Nested repeaters complexity | High | Create reusable Repeater component |
| LiveWire → Vue rewrite | High | Allocate dedicated time per component |
| Plugin equivalents missing | Medium | Build custom solutions |
| Performance at scale | Medium | Lazy loading, virtual scrolling |
| Token limit during migration | High | This plan document saved |

---

## Next Steps

1. Review and approve this plan
2. Create migration branch
3. Set up staging environment
4. Begin **Phase 0: Proof of Concept** (User Management)

---

*Document created: April 5, 2026*
