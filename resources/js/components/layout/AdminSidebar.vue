<script setup lang="ts">
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'
import {
    Users,
    Package,
    ShoppingCart,
    FileText,
    Settings,
    BarChart3,
    Calendar,
    Bell,
    Menu,
    X,
    ChevronDown,
    LogOut,
    User,
} from 'lucide-vue-next'
import Button from '@/components/ui/button.vue'

interface NavItem {
    label: string
    icon: any
    href?: string
    badge?: string | number
    children?: NavItem[]
}

const props = defineProps<{
    class?: string
}>()

const isMobileMenuOpen = ref(false)
const expandedGroups = ref<string[]>(['master-data', 'transactions'])

const navigation = [
    {
        group: 'Dashboard',
        items: [
            { label: 'Overview', icon: BarChart3, href: '/app/dashboard' },
        ],
    },
    {
        group: 'Master Data',
        items: [
            { label: 'Produk', icon: Package, href: '/app/admin/master-data/produk' },
            { label: 'Brand', icon: Package, href: '/app/admin/master-data/brand' },
            { label: 'Kategori', icon: Package, href: '/app/admin/master-data/kategori' },
            { label: 'Supplier', icon: Package, href: '/app/admin/master-data/supplier' },
            { label: 'Member', icon: Users, href: '/app/admin/master-data/member' },
            { label: 'Jasa', icon: Package, href: '/app/admin/master-data/jasa' },
            { label: 'Gudang', icon: Package, href: '/app/admin/master-data/gudang' },
            { label: 'Akun Transaksi', icon: FileText, href: '/app/admin/master-data/akun-transaksi' },
        ],
    },
    {
        group: 'Transactions',
        items: [
            { label: 'Penjualan', icon: ShoppingCart, href: '/app/admin/penjualan' },
            { label: 'Pembelian', icon: ShoppingCart, href: '/app/admin/pembelian' },
            { label: 'Tukar Tambah', icon: ShoppingCart, href: '/app/admin/tukar-tambah' },
        ],
    },
    {
        group: 'Inventory',
        items: [
            { label: 'Stock Adjustment', icon: Package, href: '/app/admin/inventory/stock-adjustment' },
            { label: 'Stock Opname', icon: Package, href: '/app/admin/inventory/stock-opname' },
        ],
    },
    {
        group: 'Akunting',
        items: [
            { label: 'Chart of Accounts', icon: FileText, href: '/app/akunting/chart-of-accounts' },
            { label: 'Input Transaksi', icon: FileText, href: '/app/akunting/input-transaksi' },
            { label: 'Laporan Laba Rugi', icon: BarChart3, href: '/app/akunting/laporan-laba-rugi' },
            { label: 'Laporan Neraca', icon: BarChart3, href: '/app/akunting/laporan-neraca' },
        ],
    },
    {
        group: 'Settings',
        items: [
            { label: 'Users', icon: Users, href: '/app/admin/users' },
            { label: 'Settings', icon: Settings, href: '/app/settings' },
        ],
    },
]

function toggleGroup(group: string) {
    const index = expandedGroups.value.indexOf(group)
    if (index > -1) {
        expandedGroups.value.splice(index, 1)
    } else {
        expandedGroups.value.push(group)
    }
}
</script>

<template>
    <aside
        :class="cn(
            'fixed left-0 top-0 z-40 h-screen w-64 border-r bg-card transition-transform lg:translate-x-0',
            isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full',
            props.class
        )"
    >
        <div class="flex h-16 items-center justify-between border-b px-4">
            <Link href="/app" class="flex items-center gap-2 font-semibold">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                    <Package class="h-5 w-5" />
                </div>
                <span>Hook App</span>
            </Link>
            <Button
                variant="ghost"
                size="icon"
                class="lg:hidden"
                @click="isMobileMenuOpen = false"
            >
                <X class="h-5 w-5" />
            </Button>
        </div>
        
        <nav class="flex flex-col gap-1 p-4 overflow-y-auto h-[calc(100vh-4rem)]">
            <div v-for="group in navigation" :key="group.group" class="space-y-1">
                <button
                    class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-medium text-muted-foreground hover:bg-accent hover:text-accent-foreground"
                    @click="toggleGroup(group.group)"
                >
                    {{ group.group }}
                    <ChevronDown
                        class="h-4 w-4 transition-transform"
                        :class="{ 'rotate-180': expandedGroups.includes(group.group) }"
                    />
                </button>
                
                <div v-if="expandedGroups.includes(group.group)" class="space-y-1 pl-2">
                    <Link
                        v-for="item in group.items"
                        :key="item.href"
                        :href="item.href || '#'"
                        class="flex items-center gap-3 rounded-md px-3 py-2 text-sm hover:bg-accent hover:text-accent-foreground"
                        :class="{ 'bg-accent text-accent-foreground': $page.url === item.href }"
                    >
                        <component :is="item.icon" class="h-4 w-4" />
                        {{ item.label }}
                        <span
                            v-if="item.badge"
                            class="ml-auto rounded-full bg-primary px-2 py-0.5 text-xs text-primary-foreground"
                        >
                            {{ item.badge }}
                        </span>
                    </Link>
                </div>
            </div>
        </nav>
        
        <div class="absolute bottom-0 left-0 right-0 border-t p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-muted">
                    <User class="h-4 w-4" />
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">
                        {{ $page.props.user?.name || 'User' }}
                    </p>
                    <p class="text-xs text-muted-foreground truncate">
                        {{ $page.props.user?.email || 'user@example.com' }}
                    </p>
                </div>
                <Link href="/app/logout" method="post" as="button" type="button">
                    <LogOut class="h-4 w-4 text-muted-foreground hover:text-foreground" />
                </Link>
            </div>
        </div>
    </aside>
    
    <div
        v-if="isMobileMenuOpen"
        class="fixed inset-0 z-30 bg-black/50 lg:hidden"
        @click="isMobileMenuOpen = false"
    />
</template>
