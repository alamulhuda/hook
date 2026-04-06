<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue'
import { ref, computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import { Plus, Search, Pencil, Trash2, Image, DollarSign } from 'lucide-vue-next'
import PageHeader from '@/components/layout/PageHeader.vue'
import Button from '@/components/ui/button.vue'
import Input from '@/components/ui/input.vue'
import Card from '@/components/ui/card.vue'
import Badge from '@/components/ui/badge.vue'
import DataTable from '@/components/tables/DataTable.vue'
import DropdownMenu from '@/components/ui/dropdown-menu/index.vue'
import DropdownMenuContent from '@/components/ui/dropdown-menu/dropdown-menu-content.vue'
import DropdownMenuItem from '@/components/ui/dropdown-menu/dropdown-menu-item.vue'
import DropdownMenuSeparator from '@/components/ui/dropdown-menu/dropdown-menu-separator.vue'
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/dropdown-menu-item.vue'
import Sheet from '@/components/ui/sheet/index.vue'
import SheetContent from '@/components/ui/sheet/sheet.vue'
import FormField from '@/components/forms/FormField.vue'
import RelationSelect from '@/components/forms/RelationSelect.vue'
import CurrencyInput from '@/components/forms/CurrencyInput.vue'
import Repeater from '@/components/forms/Repeater.vue'
import { formatCurrency } from '@/lib/utils'

const page = usePage()

interface Produk {
    id: number
    nama: string
    sku: string
    brand: { id: number; nama: string }
    kategori: { id: number; nama: string }
    harga_beli: number
    harga_jual: number
    stok: number
    foto: string[]
}

const produks = ref<Produk[]>(page.props.produks || [])
const isLoading = ref(false)
const showDrawer = ref(false)
const showDeleteModal = ref(false)
const selectedProduk = ref<Produk | null>(null)
const searchQuery = ref('')

const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0,
})

const columns = [
    { key: 'foto', label: 'Photo', sortable: false },
    { key: 'nama', label: 'Name', sortable: true },
    { key: 'sku', label: 'SKU', sortable: true },
    { key: 'brand', label: 'Brand', sortable: true },
    { key: 'kategori', label: 'Category', sortable: true },
    { key: 'harga_beli', label: 'Purchase Price', sortable: true },
    { key: 'harga_jual', label: 'Selling Price', sortable: true },
    { key: 'stok', label: 'Stock', sortable: true },
]

const form = useForm({
    nama: '',
    sku: '',
    brand_id: null as number | null,
    kategori_id: null as number | null,
    deskripsi: '',
    harga_beli: null as number | null,
    harga_jual: null as number | null,
    stok: 0,
    foto: [] as string[],
})

const brandOptions = computed(() =>
    (page.props.brands || []).map((b: any) => ({ label: b.nama, value: b.id }))
)

const kategoriOptions = computed(() =>
    (page.props.kategoris || []).map((k: any) => ({ label: k.nama, value: k.id }))
)

function openCreateDrawer() {
    selectedProduk.value = null
    form.reset()
    form.clearErrors()
    showDrawer.value = true
}

function openEditDrawer(produk: Produk) {
    selectedProduk.value = produk
    form.nama = produk.nama
    form.sku = produk.sku
    form.brand_id = produk.brand?.id || null
    form.kategori_id = produk.kategori?.id || null
    form.harga_beli = produk.harga_beli
    form.harga_jual = produk.harga_jual
    form.stok = produk.stok
    showDrawer.value = true
}

function openDeleteModal(produk: Produk) {
    selectedProduk.value = produk
    showDeleteModal.value = true
}

function handleSort(field: string, direction: 'asc' | 'desc') {
    // Handle sort
}

function handlePageChange(page: number) {
    pagination.value.current_page = page
}

function handleRowClick(produk: Produk) {
    openEditDrawer(produk)
}

function submitForm() {
    if (selectedProduk.value) {
        form.put(`/app/admin/master-data/produk/${selectedProduk.value.id}`, {
            onSuccess: () => {
                showDrawer.value = false
                form.reset()
            },
        })
    } else {
        form.post('/app/admin/master-data/produk', {
            onSuccess: () => {
                showDrawer.value = false
                form.reset()
            },
        })
    }
}

function deleteProduk() {
    if (selectedProduk.value) {
        form.delete(`/app/admin/master-data/produk/${selectedProduk.value.id}`, {
            onSuccess: () => {
                showDeleteModal.value = false
                selectedProduk.value = null
            },
        })
    }
}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="Products"
                description="Manage your product inventory and catalog."
                :breadcrumbs="[
                    { label: 'Master Data', href: '/app/admin/master-data' },
                    { label: 'Products' }
                ]"
            >
                <template #actions>
                    <Button @click="openCreateDrawer">
                        <Plus class="h-4 w-4 mr-2" />
                        Add Product
                    </Button>
                </template>
            </PageHeader>
            
            <Card class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="relative flex-1 max-w-sm">
                        <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            v-model="searchQuery"
                            placeholder="Search products..."
                            class="pl-10"
                        />
                    </div>
                </div>
                
                <DataTable
                    :data="produks"
                    :columns="columns"
                    :pagination="pagination"
                    :loading="isLoading"
                    @sort="handleSort"
                    @page-change="handlePageChange"
                    @row-click="handleRowClick"
                >
                    <template #cell:foto="{ row }">
                        <div class="h-10 w-10 rounded-md overflow-hidden bg-muted">
                            <img
                                v-if="row.original.foto?.[0]"
                                :src="row.original.foto[0]"
                                class="h-full w-full object-cover"
                            />
                            <div v-else class="flex h-full w-full items-center justify-center">
                                <Image class="h-5 w-5 text-muted-foreground" />
                            </div>
                        </div>
                    </template>
                    
                    <template #cell:brand="{ row }">
                        {{ row.original.brand?.nama || '-' }}
                    </template>
                    
                    <template #cell:kategori="{ row }">
                        {{ row.original.kategori?.nama || '-' }}
                    </template>
                    
                    <template #cell:harga_beli="{ row }">
                        {{ formatCurrency(row.original.harga_beli) }}
                    </template>
                    
                    <template #cell:harga_jual="{ row }">
                        {{ formatCurrency(row.original.harga_jual) }}
                    </template>
                    
                    <template #cell:stok="{ row }">
                        <Badge :variant="row.original.stok < 10 ? 'destructive' : 'secondary'">
                            {{ row.original.stok }}
                        </Badge>
                    </template>
                </DataTable>
            </Card>
        </div>
        
        <Sheet :open="showDrawer" @update:open="showDrawer = $event" class="w-[500px]">
            <SheetContent>
                <div class="space-y-6">
                    <div>
                        <h2 class="text-lg font-semibold">
                            {{ selectedProduk ? 'Edit Product' : 'Create Product' }}
                        </h2>
                        <p class="text-sm text-muted-foreground">
                            {{ selectedProduk ? 'Update product information' : 'Add a new product to your catalog' }}
                        </p>
                    </div>
                    
                    <form @submit.prevent="submitForm" class="space-y-4">
                        <FormField label="Product Name" name="nama" required>
                            <Input v-model="form.nama" placeholder="Enter product name" />
                            <p v-if="form.errors.nama" class="text-sm text-red-500">{{ form.errors.nama }}</p>
                        </FormField>
                        
                        <FormField label="SKU" name="sku" required>
                            <Input v-model="form.sku" placeholder="Enter SKU" />
                            <p v-if="form.errors.sku" class="text-sm text-red-500">{{ form.errors.sku }}</p>
                        </FormField>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <FormField label="Brand" name="brand_id">
                                <RelationSelect
                                    v-model="form.brand_id"
                                    :options="brandOptions"
                                    placeholder="Select brand"
                                />
                            </FormField>
                            
                            <FormField label="Category" name="kategori_id">
                                <RelationSelect
                                    v-model="form.kategori_id"
                                    :options="kategoriOptions"
                                    placeholder="Select category"
                                />
                            </FormField>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <FormField label="Purchase Price" name="harga_beli">
                                <CurrencyInput v-model="form.harga_beli" />
                            </FormField>
                            
                            <FormField label="Selling Price" name="harga_jual">
                                <CurrencyInput v-model="form.harga_jual" />
                            </FormField>
                        </div>
                        
                        <FormField label="Initial Stock" name="stok">
                            <Input v-model.number="form.stok" type="number" min="0" />
                        </FormField>
                        
                        <div class="flex justify-end gap-2 pt-4 border-t">
                            <Button type="button" variant="outline" @click="showDrawer = false">
                                Cancel
                            </Button>
                            <Button type="submit" :loading="form.processing">
                                {{ selectedProduk ? 'Update' : 'Create' }}
                            </Button>
                        </div>
                    </form>
                </div>
            </SheetContent>
        </Sheet>
        
        <Dialog :open="showDeleteModal" @update:open="showDeleteModal = $event" class="max-w-md">
            <div class="space-y-4">
                <h2 class="text-lg font-semibold">Delete Product</h2>
                <p class="text-muted-foreground">
                    Are you sure you want to delete <strong>{{ selectedProduk?.nama }}</strong>?
                    This action cannot be undone.
                </p>
                <div class="flex justify-end gap-2 pt-4">
                    <Button variant="outline" @click="showDeleteModal = false">Cancel</Button>
                    <Button variant="destructive" @click="deleteProduk" :loading="form.processing">
                        Delete
                    </Button>
                </div>
            </div>
        </Dialog>
    </AppLayout>
</template>
