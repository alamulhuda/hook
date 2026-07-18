<script setup lang="ts">
import { ref, computed, provide, onMounted, watch, inject } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'
import { getModuleNavItems } from '@/module-loader'
import {
    LayoutDashboard,
    ShoppingCart,
    PackageSearch,
    Warehouse,
    Wrench,
    BookOpen,
    BarChart3,
    Megaphone,
    Users,
    Settings,
    LogOut,
    ChevronRight,
    Package,
    Building2,
    ShieldCheck,
    KeyRound,
    FileText,
    TrendingUp,
    Truck,
    ClipboardList,
    Calculator,
    ReceiptText,
    Layers,
    UserCog,
    Cog,
} from 'lucide-vue-next'

// ─── Types ───────────────────────────────────────────────────────────────────

interface NavItem {
    label: string
    icon: any
    href?: string
    module?: string   // If set, item is hidden when this module is disabled
    badge?: string    // Optional badge (e.g. "Soon")
    children?: NavItem[]
}

interface MainNavGroup {
    id: string
    label: string
    icon: any
    href?: string
    module?: string   // If set, entire group is hidden when this module is disabled
}

// ─── Setup ───────────────────────────────────────────────────────────────────

const page = usePage()

const props = defineProps<{
    class?: string
}>()

const isMobileMenuOpen = ref(false)
provide('mobileMenuOpen', isMobileMenuOpen)
provide('toggleMobileMenu', () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value
})

// ─── Module Guard ─────────────────────────────────────────────────────────────

/**
 * Check if a module is enabled in Inertia shared props.
 * No moduleKey = always visible (core feature).
 */
function isModuleActive(moduleKey?: string): boolean {
    if (!moduleKey) return true
    const enabled = page.props.enabled_modules as string[] | undefined
    if (!enabled || !Array.isArray(enabled)) return true
    const target = moduleKey.toLowerCase().replace(/-/g, '')
    return enabled.some(e => e.toLowerCase().replace(/-/g, '') === target)
}

// ─── Navigation Definition ───────────────────────────────────────────────────
// Structure follows international ERP standards (Odoo / SAP / NetSuite style).
// Each top-level group = a business domain.
// Sub-items with `module` tag are hidden when that module is disabled.

const mainNavigation: MainNavGroup[] = [
    // 1 — Always visible: business overview
    { id: 'dashboard',   label: 'Dashboard',   icon: LayoutDashboard },

    // 2 — Sales domain (core: SO + Customers; module-gated extras)
    { id: 'sales',       label: 'Sales',        icon: ShoppingCart },

    // 3 — Purchasing domain (core)
    { id: 'purchasing',  label: 'Purchasing',   icon: Truck },

    // 4 — Inventory domain (core products; advanced ops module-gated)
    { id: 'inventory',   label: 'Inventory',    icon: Warehouse },

    // 5 — Service & Repair (entire group gated: ServiceRepair module)
    { id: 'service',     label: 'Service',      icon: Wrench,       module: 'ServiceRepair' },

    // 6 — Accounting & Finance (entire group gated: Akunting module)
    { id: 'accounting',  label: 'Accounting',   icon: BookOpen,     module: 'Akunting' },

    // 7 — Reports & Analytics (entire group gated: Reports module)
    { id: 'reports',     label: 'Reports',      icon: BarChart3,    module: 'Reports' },

    // 8 — CRM / Marketing (entire group gated: Marketing module)
    { id: 'marketing',   label: 'CRM',          icon: Megaphone,    module: 'Marketing' },

    // 9 — HR & Payroll (entire group gated: HR module)
    { id: 'hr',          label: 'HR',           icon: Users,        module: 'HR' },

    // 10 — Settings: always visible
    { id: 'settings',    label: 'Settings',     icon: Settings },
]

const subNavigation: Record<string, NavItem[]> = {
    // ── 1. Dashboard ──────────────────────────────────────────────────────────
    'dashboard': [
        { label: 'Overview',         icon: LayoutDashboard, href: '/app/dashboard' },
    ],

    // ── 2. Sales ──────────────────────────────────────────────────────────────
    // Core items are always visible. Module-gated extras disappear when disabled.
    'sales': [
        // Core
        { label: 'Sales Orders',     icon: ClipboardList,   href: '/app/admin/transactions/penjualan' },
        { label: 'Customers',        icon: Users,            href: '/app/admin/master-data/member' },
        { label: 'Sales Return',     icon: ReceiptText,      href: '#',                                                  badge: 'Soon' },
        // Module: TukarTambah
        { label: 'Trade-In',         icon: Layers,           href: '/app/admin/transactions/tukar-tambah',  module: 'TukarTambah' },
        // Module: POS
        { label: 'Point of Sale',    icon: ShoppingCart,     href: '/app/modules/pos/cashier',               module: 'POS' },
    ],

    // ── 3. Purchasing ─────────────────────────────────────────────────────────
    'purchasing': [
        // Core
        { label: 'Purchase Orders',  icon: ClipboardList,   href: '/app/admin/transactions/pembelian' },
        { label: 'Suppliers',        icon: Building2,        href: '/app/admin/master-data/supplier' },
        { label: 'Purchase Return',  icon: ReceiptText,      href: '#',                                                  badge: 'Soon' },
    ],

    // ── 4. Inventory ──────────────────────────────────────────────────────────
    // Core product master is always visible. Advanced ops are module-gated.
    'inventory': [
        // Core
        { label: 'Products',         icon: Package,          href: '/app/admin/inventory/products' },
        { label: 'Categories',       icon: Layers,           href: '/app/admin/master-data/kategori' },
        { label: 'Brands',           icon: TrendingUp,       href: '/app/admin/master-data/brand' },
        { label: 'Warehouses',       icon: Warehouse,        href: '/app/modules/warehouse/gudang', module: 'Warehouse' },
        // Module: Inventory
        { label: 'Stock Adjustment', icon: PackageSearch,    href: '/app/modules/inventory/stock-adjustment', module: 'Inventory' },
        { label: 'Stock Opname',     icon: ClipboardList,    href: '/app/modules/inventory/stock-opname',     module: 'Inventory' },
        { label: 'Stock Movement',   icon: Truck,            href: '#',                                        module: 'Inventory', badge: 'Soon' },
    ],

    // ── 5. Service & Repair (entirely gated: ServiceRepair) ───────────────────
    'service': [
        { label: 'Work Orders',      icon: Wrench,           href: '/app/modules/service-repair/work-orders',                module: 'ServiceRepair', badge: 'Soon' },
        { label: 'Master Jasa',      icon: Cog,              href: '/app/modules/service-repair/master-data/jasa',           module: 'ServiceRepair' },
        { label: 'Technicians',      icon: UserCog,          href: '#',                                                      module: 'ServiceRepair', badge: 'Soon' },
    ],

    // ── 6. Accounting & Finance (entirely gated: Akunting) ────────────────────
    'accounting': [
        { label: 'Chart of Accounts', icon: BookOpen,        href: '/app/modules/akunting/chart-of-accounts',  module: 'Akunting' },
        { label: 'Journal Entries',  icon: FileText,         href: '/app/modules/akunting/input-transaksi',    module: 'Akunting' },
        { label: 'Invoices',         icon: ReceiptText,      href: '#',                                          module: 'Akunting', badge: 'Soon' },
        { label: 'Expenses',         icon: Calculator,       href: '#',                                          module: 'Akunting', badge: 'Soon' },
        { label: 'Cash & Bank',      icon: Building2,        href: '/app/admin/master-data/akun-transaksi',      module: 'Akunting' },
        { label: 'Profit & Loss',    icon: BarChart3,        href: '/app/modules/akunting/laporan-laba-rugi',  module: 'Akunting' },
        { label: 'Balance Sheet',    icon: BarChart3,        href: '/app/modules/akunting/laporan-neraca',     module: 'Akunting' },
    ],

    // ── 7. Reports & Analytics (entirely gated: Reports) ─────────────────────
    'reports': [
        { label: 'Sales Report',     icon: TrendingUp,       href: '#', module: 'Reports', badge: 'Soon' },
        { label: 'Purchase Report',  icon: Truck,            href: '#', module: 'Reports', badge: 'Soon' },
        { label: 'Inventory Report', icon: PackageSearch,    href: '#', module: 'Reports', badge: 'Soon' },
        { label: 'Financial Summary',icon: BarChart3,        href: '#', module: 'Reports', badge: 'Soon' },
    ],

    // ── 8. CRM / Marketing (entirely gated: Marketing) ───────────────────────
    'marketing': [
        { label: 'Promotions',       icon: Megaphone,        href: '#', module: 'Marketing', badge: 'Soon' },
        { label: 'Content Calendar', icon: ClipboardList,    href: '#', module: 'Marketing', badge: 'Soon' },
        { label: 'Supplier Agents',  icon: Users,            href: '#', module: 'Marketing', badge: 'Soon' },
        { label: 'Segments',         icon: Layers,           href: '#', module: 'Marketing', badge: 'Soon' },
    ],

    // ── 9. HR & Payroll (entirely gated: HR) ─────────────────────────────────
    'hr': [
        { label: 'Employees',        icon: Users,            href: '#', module: 'HR', badge: 'Soon' },
        { label: 'Attendance',       icon: ClipboardList,    href: '#', module: 'HR', badge: 'Soon' },
        { label: 'Payroll',          icon: Calculator,       href: '#', module: 'HR', badge: 'Soon' },
        { label: 'Leave Management', icon: FileText,         href: '#', module: 'HR', badge: 'Soon' },
    ],

    // ── 10. Settings ──────────────────────────────────────────────────────────
    'settings': [
        { label: 'General Settings', icon: Cog,              href: '/app/settings' },
        { label: 'Bank Accounts',    icon: Building2,        href: '/app/admin/master-data/akun-transaksi' },
        { label: 'Company Profile',  icon: Building2,        href: '#',                        badge: 'Soon' },
        { label: 'Tax',              icon: Calculator,       href: '#',                        badge: 'Soon' },
        // User & Access Management (nested under Settings — ERP standard)
        { label: 'Users',            icon: Users,            href: '/app/admin/users' },
        { label: 'Roles',            icon: ShieldCheck,      href: '/app/admin/roles' },
        { label: 'Permissions',      icon: KeyRound,         href: '/app/admin/permissions' },
        { label: 'Modules',          icon: Layers,           href: '/app/settings/modules' },
    ],
}

// ─── Active Section Detection ────────────────────────────────────────────────

function getActiveSectionFromPath(path: string): string {
    // Check all sub-navigation href matches first (most specific)
    for (const [section, items] of Object.entries(subNavigation)) {
        for (const item of items) {
            if (item.href && item.href !== '#' && (path === item.href || path.startsWith(item.href + '/'))) {
                return section
            }
        }
    }
    // Path-based fallbacks
    if (path.startsWith('/app/admin/transactions')) return 'sales'
    if (path.startsWith('/app/admin/master-data/member')) return 'sales'
    if (path.startsWith('/app/admin/transactions/pembelian') || path.startsWith('/app/admin/master-data/supplier')) return 'purchasing'
    if (path.startsWith('/app/admin/inventory') || path.startsWith('/app/modules/inventory') || path.startsWith('/app/modules/warehouse')) return 'inventory'
    if (path.startsWith('/app/admin/master-data')) return 'inventory'
    if (path.startsWith('/app/modules/akunting')) return 'accounting'
    if (path.startsWith('/app/modules/service-repair')) return 'service'
    if (path.startsWith('/app/modules/pos')) return 'sales'
    if (path.startsWith('/app/modules/reports')) return 'reports'
    if (path.startsWith('/app/admin/master-data/akun-transaksi')) return 'settings'
    if (path.startsWith('/app/admin/users') || path.startsWith('/app/admin/roles') || path.startsWith('/app/settings')) return 'settings'
    if (path.startsWith('/app/dashboard')) return 'dashboard'
    return 'dashboard'
}

const activeMainNav = ref(getActiveSectionFromPath(window.location.pathname))

onMounted(() => {
    router.on('navigate', (event) => {
        const url = new URL(event.detail.page.url, window.location.origin)
        activeMainNav.value = getActiveSectionFromPath(url.pathname)
    })
})

function setActiveMainNav(id: string) {
    activeMainNav.value = id
}

function isActiveHref(href?: string): boolean {
    if (!href || href === '#') return false
    const pathname = window.location.pathname
    return pathname === href || pathname.startsWith(href + '/')
}

// ─── Filtered Navigation (respects module enable/disable) ────────────────────

const filteredMainNavigation = computed(() => {
    return mainNavigation.filter(item => {
        // If the top-level group has a module guard — hide if disabled
        if (item.module && !isModuleActive(item.module)) return false
        // If ALL sub-items are module-gated and ALL are disabled — hide the group
        const subs = subNavigation[item.id] || []
        if (subs.length > 0 && !item.href) {
            const activeSubs = subs.filter(s => isModuleActive(s.module))
            if (activeSubs.length === 0) return false
        }
        return true
    })
})

const activeSubNavItems = computed(() => {
    const section = activeMainNav.value
    const staticItems = (subNavigation[section] || []).filter(item => isModuleActive(item.module))

    // Merge dynamic module nav items that match this section (from module routes.ts manifests)
    const enabled = page.props.enabled_modules as string[] | undefined
    const dynamicGroups = getModuleNavItems(enabled)
    const matchingGroups = dynamicGroups.filter(g => {
        const gSlug = g.label.toLowerCase().replace(/\s+/g, '-').replace(/[&]/g, '').replace(/--+/g, '-')
        return gSlug === section || g.label.toLowerCase().replace(/\s+/g, '-') === section
    })
    const dynamicItems: NavItem[] = matchingGroups.flatMap(g =>
        g.children.map(c => ({ label: c.label, icon: Package, href: c.to }))
    )

    // Deduplicate: dynamic items already present in static list are skipped
    const staticHrefs = new Set(staticItems.map(i => i.href))
    const uniqueDynamic = dynamicItems.filter(d => !staticHrefs.has(d.href))

    return [...staticItems, ...uniqueDynamic]
})

// ─── Section Label ────────────────────────────────────────────────────────────

const activeSectionLabel = computed(() => {
    return filteredMainNavigation.value.find(m => m.id === activeMainNav.value)?.label ?? ''
})

const setHasSubNav = inject<(val: boolean) => void>('setHasSubNav', () => {})
watch(activeSubNavItems, (newItems) => {
    setHasSubNav(newItems.length > 0)
}, { immediate: true })
</script>

<template>
    <aside
        :class="cn(
            'fixed left-0 top-0 z-40 flex h-screen bg-card border-r transition-transform lg:translate-x-0',
            isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full',
            props.class
        )"
    >
        <!-- ── Main Navigation (Icon Column) ───────────────────────────────── -->
        <div class="w-[68px] flex flex-col border-r bg-card shrink-0">

            <!-- Logo -->
            <div class="flex h-14 items-center justify-center border-b shrink-0">
                <Link
                    href="/app/dashboard"
                    class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary text-primary-foreground shadow-sm"
                >
                    <Package class="h-4 w-4" />
                </Link>
            </div>

            <!-- Nav Icons -->
            <nav class="flex-1 py-3 overflow-y-auto scrollbar-none">
                <div class="space-y-0.5 px-2">
                    <button
                        v-for="item in filteredMainNavigation"
                        :key="item.id"
                        class="w-full flex flex-col items-center justify-center py-2.5 px-1 rounded-lg transition-all duration-150 relative group"
                        :class="[
                            activeMainNav === item.id
                                ? 'bg-primary/10 text-primary'
                                : 'text-muted-foreground hover:bg-accent hover:text-foreground'
                        ]"
                        :title="item.label"
                        @click="setActiveMainNav(item.id)"
                    >
                        <component :is="item.icon" class="h-[18px] w-[18px] mb-1 shrink-0" />
                        <span class="text-[9px] font-medium text-center leading-tight block w-full truncate px-0.5">
                            {{ item.label }}
                        </span>

                        <!-- Active bar -->
                        <div
                            v-if="activeMainNav === item.id"
                            class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-7 bg-primary rounded-r-full"
                        />
                    </button>
                </div>
            </nav>

            <!-- Logout -->
            <div class="border-t p-2 shrink-0">
                <Link
                    href="/app/logout"
                    method="post"
                    as="button"
                    class="w-full flex flex-col items-center justify-center py-2.5 rounded-lg text-muted-foreground hover:bg-destructive/10 hover:text-destructive transition-colors"
                    title="Logout"
                >
                    <LogOut class="h-[18px] w-[18px] mb-1" />
                    <span class="text-[9px] font-medium">Logout</span>
                </Link>
            </div>
        </div>

        <!-- ── Sub Navigation (Label + Items Column) ───────────────────────── -->
        <div
            v-if="activeSubNavItems.length > 0"
            class="w-52 bg-card flex flex-col"
        >
            <!-- Section header -->
            <div class="h-14 flex items-center px-4 border-b shrink-0">
                <span class="text-sm font-semibold tracking-tight">
                    {{ activeSectionLabel }}
                </span>
            </div>

            <nav class="flex-1 py-2 overflow-y-auto">
                <div class="px-2 space-y-0.5">
                    <Link
                        v-for="item in activeSubNavItems"
                        :key="item.label"
                        :href="item.href || '#'"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm transition-colors group"
                        :class="[
                            isActiveHref(item.href)
                                ? 'bg-primary/10 text-primary font-medium'
                                : 'text-muted-foreground hover:bg-accent hover:text-foreground'
                        ]"
                    >
                        <component :is="item.icon" class="h-4 w-4 shrink-0" />
                        <span class="flex-1 truncate">{{ item.label }}</span>
                        <!-- Badge -->
                        <span
                            v-if="item.badge"
                            class="text-[9px] font-semibold uppercase px-1.5 py-0.5 rounded-full bg-muted text-muted-foreground/70 leading-none"
                        >
                            {{ item.badge }}
                        </span>
                        <ChevronRight v-else-if="item.children" class="h-3.5 w-3.5 ml-auto opacity-50" />
                    </Link>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Mobile overlay -->
    <div
        v-if="isMobileMenuOpen"
        class="fixed inset-0 z-30 bg-black/50 lg:hidden"
        @click="isMobileMenuOpen = false"
    />
</template>
