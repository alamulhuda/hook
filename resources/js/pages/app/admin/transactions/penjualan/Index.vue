<script setup lang="ts">
import { ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/components/layout/AppLayout.vue'
import PageHeader from '@/components/layout/PageHeader.vue'
import Button from '@/components/ui/button.vue'
import Input from '@/components/ui/input.vue'
import Card from '@/components/ui/card.vue'
import Badge from '@/components/ui/badge.vue'
import { Plus, Search, Eye, Pencil, Trash2, Filter, X } from 'lucide-vue-next'

const page = usePage()

interface Penjualan {
    id_penjualan: number
    no_nota: string
    tanggal_penjualan: string
    total: number
    diskon_total: number
    grand_total: number
    status_pembayaran: string
    member: { nama_member: string } | null
    karyawan: { nama: string } | null
}

const penjualans = computed(() => page.props.penjualans?.data || [])
const stats = computed(() => page.props.stats || {})
const filters = computed(() => page.props.filters || {})

const searchQuery = ref(filters.value.search || '')
const statusFilter = ref(filters.value.status || 'all')

function formatCurrency(value: number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value)
}

function formatDate(date: string) {
    return new Date(date).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    })
}

function applyFilters() {
    router.get('/app/admin/transactions/penjualan', {
        search: searchQuery.value,
        status: statusFilter.value,
        from: filters.value.from,
        to: filters.value.to,
    }, { preserveState: true })
}

function resetFilters() {
    searchQuery.value = ''
    statusFilter.value = 'all'
    router.get('/app/admin/transactions/penjualan', {}, { preserveState: true })
}

function deletePenjualan(id: number) {
    if (confirm('Are you sure you want to delete this transaction?')) {
        router.delete(`/app/admin/transactions/penjualan/${id}`)
    }
}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="Penjualan"
                description="Manage your sales transactions."
                :breadcrumbs="[
                    { label: 'Transactions', href: '/app/admin/transactions' },
                    { label: 'Penjualan' }
                ]"
            >
                <template #actions>
                    <Link href="/app/admin/transactions/penjualan/create">
                        <Button>
                            <Plus class="h-4 w-4 mr-2" />
                            New Transaction
                        </Button>
                    </Link>
                </template>
            </PageHeader>
            
            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-4">
                <Card class="p-4">
                    <p class="text-sm text-muted-foreground">Total Transactions</p>
                    <p class="text-2xl font-bold">{{ stats.total_count || 0 }}</p>
                </Card>
                <Card class="p-4">
                    <p class="text-sm text-muted-foreground">Lunas</p>
                    <p class="text-2xl font-bold text-green-600">{{ stats.lunas_count || 0 }}</p>
                </Card>
                <Card class="p-4">
                    <p class="text-sm text-muted-foreground">Belum Lunas</p>
                    <p class="text-2xl font-bold text-red-600">{{ stats.belum_lunas_count || 0 }}</p>
                </Card>
                <Card class="p-4">
                    <p class="text-sm text-muted-foreground">Total Revenue</p>
                    <p class="text-2xl font-bold">{{ formatCurrency(stats.total_revenue || 0) }}</p>
                </Card>
            </div>
            
            <!-- Filters -->
            <Card class="p-4">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="relative flex-1 min-w-[200px]">
                        <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            v-model="searchQuery"
                            placeholder="Search by no. nota or customer..."
                            class="pl-10"
                            @keyup.enter="applyFilters"
                        />
                    </div>
                    
                    <select
                        v-model="statusFilter"
                        class="h-10 rounded-md border border-input bg-background px-3 py-2 text-sm"
                    >
                        <option value="all">All Status</option>
                        <option value="lunas">Lunas</option>
                        <option value="belum_lunas">Belum Lunas</option>
                    </select>
                    
                    <div class="flex gap-2">
                        <Button variant="outline" size="sm" @click="resetFilters">
                            <X class="h-4 w-4 mr-1" />
                            Reset
                        </Button>
                        <Button size="sm" @click="applyFilters">
                            <Filter class="h-4 w-4 mr-1" />
                            Filter
                        </Button>
                    </div>
                </div>
                
                <div v-if="filters.from && filters.to" class="mt-3 text-sm text-muted-foreground">
                    Filter: {{ formatDate(filters.from) }} - {{ formatDate(filters.to) }}
                </div>
            </Card>
            
            <!-- Table -->
            <Card class="overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-muted/50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground">No. Nota</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground">Tanggal</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-muted-foreground">Customer</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-muted-foreground">Total</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-muted-foreground">Diskon</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-muted-foreground">Grand Total</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-muted-foreground">Status</th>
                                <th class="px-4 py-3 text-center text-sm font-medium text-muted-foreground">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr
                                v-for="penjualan in penjualans"
                                :key="penjualan.id_penjualan"
                                class="hover:bg-muted/50 transition-colors"
                            >
                                <td class="px-4 py-3">
                                    <span class="font-medium">{{ penjualan.no_nota }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ formatDate(penjualan.tanggal_penjualan) }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ penjualan.member?.nama_member || '-' }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm">
                                    {{ formatCurrency(penjualan.total) }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-muted-foreground">
                                    {{ formatCurrency(penjualan.diskon_total || 0) }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-medium">
                                    {{ formatCurrency(penjualan.grand_total) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <Badge
                                        :variant="penjualan.status_pembayaran === 'lunas' ? 'success' : 'destructive'"
                                    >
                                        {{ penjualan.status_pembayaran === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1">
                                        <Link :href="`/app/admin/transactions/penjualan/${penjualan.id_penjualan}`">
                                            <Button variant="ghost" size="icon" class="h-8 w-8">
                                                <Eye class="h-4 w-4" />
                                            </Button>
                                        </Link>
                                        <Link :href="`/app/admin/transactions/penjualan/${penjualan.id_penjualan}/edit`">
                                            <Button variant="ghost" size="icon" class="h-8 w-8">
                                                <Pencil class="h-4 w-4" />
                                            </Button>
                                        </Link>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="h-8 w-8 text-destructive hover:text-destructive"
                                            @click="deletePenjualan(penjualan.id_penjualan)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="penjualans.length === 0">
                                <td colspan="8" class="px-4 py-8 text-center text-muted-foreground">
                                    No transactions found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="border-t px-4 py-3 flex items-center justify-between">
                    <p class="text-sm text-muted-foreground">
                        Showing {{ page.props.penjualans?.from || 0 }} to {{ page.props.penjualans?.to || 0 }}
                        of {{ page.props.penjualans?.total || 0 }} results
                    </p>
                    <div class="flex gap-2">
                        <Link
                            v-if="page.props.penjualans?.prev_page_url"
                            :href="page.props.penjualans.prev_page_url"
                        >
                            <Button variant="outline" size="sm">Previous</Button>
                        </Link>
                        <Link
                            v-if="page.props.penjualans?.next_page_url"
                            :href="page.props.penjualans.next_page_url"
                        >
                            <Button variant="outline" size="sm">Next</Button>
                        </Link>
                    </div>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
