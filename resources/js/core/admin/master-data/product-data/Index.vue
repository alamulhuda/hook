<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { usePage, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/components/layout/AppLayout.vue'
import PageHeader from '@/components/layout/PageHeader.vue'
import Card from '@/components/ui/card.vue'
import Button from '@/components/ui/button.vue'
import Input from '@/components/ui/input.vue'
import Textarea from '@/components/ui/textarea.vue'
import Select from '@/components/ui/select.vue'
import Checkbox from '@/components/ui/checkbox.vue'
import Dialog from '@/components/ui/dialog.vue'
import Slideover from '@/components/ui/slideover.vue'
import Badge from '@/components/ui/badge.vue'
import FormField from '@/components/forms/FormField.vue'
import DataTable from '@/components/tables/DataTable.vue'
import MultiImageUpload, { type ProdukImage } from '@/components/ui/MultiImageUpload.vue'
import {
    Plus,
    Search,
    Pencil,
    Trash2,
    Package,
    Eye,
    RotateCcw,
    Layers,
    Tag,
    Box,
    Truck,
    Info,
    Image as ImageIcon,
    CheckCircle2,
    XCircle,
} from 'lucide-vue-next'

interface Brand {
    id: number
    nama_brand: string
}

interface Kategori {
    id: number
    nama_kategori: string
}

interface Product {
    id: number
    nama_produk: string
    sku: string
    sn?: string | null
    garansi?: string | null
    tipe_produk: 'physical' | 'service'
    is_sellable?: boolean
    is_purchasable?: boolean
    kategori_id?: number | null
    brand_id?: number | null
    berat?: number | null
    panjang?: number | null
    lebar?: number | null
    tinggi?: number | null
    deskripsi?: string | null
    image_url?: string | null
    images?: ProdukImage[]
    brand?: Brand | null
    kategori?: Kategori | null
}

const page = usePage()

const produksRaw = computed(() => page.props.produks as any)
const productsList = computed<Product[]>(() => {
    if (Array.isArray(produksRaw.value)) return produksRaw.value
    return produksRaw.value?.data || []
})

const brands = computed<Brand[]>(() => (page.props.brands as Brand[]) || [])
const kategoris = computed<Kategori[]>(() => (page.props.kategoris as Kategori[]) || [])
const initialFilters = computed(() => (page.props.filters as Record<string, string>) || {})

// Filters state
const searchQuery = ref(initialFilters.value.search || '')
const selectedBrandFilter = ref(initialFilters.value.brand_id || '')
const selectedKategoriFilter = ref(initialFilters.value.kategori_id || '')
let filterTimeout: ReturnType<typeof setTimeout> | null = null

function applyFilters() {
    router.get(
        '/app/admin/master-data/product-data',
        {
            search: searchQuery.value || undefined,
            brand_id: selectedBrandFilter.value || undefined,
            kategori_id: selectedKategoriFilter.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    )
}

function handleSearchInput(value: string) {
    searchQuery.value = value
    if (filterTimeout) clearTimeout(filterTimeout)
    filterTimeout = setTimeout(() => {
        applyFilters()
    }, 350)
}

function handleFilterChange() {
    applyFilters()
}

function resetFilters() {
    searchQuery.value = ''
    selectedBrandFilter.value = ''
    selectedKategoriFilter.value = ''
    applyFilters()
}

// Table columns
const columns = [
    { key: 'image_url', label: 'Image', sortable: false, width: '70px' },
    { key: 'nama_produk', label: 'Product Details', sortable: true },
    { key: 'kategori', label: 'Category', sortable: false },
    { key: 'brand', label: 'Brand', sortable: false },
    { key: 'tipe_produk', label: 'Type', sortable: false },
    { key: 'dimensi', label: 'Dimensions & Weight', sortable: false },
]

// Modal & Slideover states
const showSlideover = ref(false)
const showDetailModal = ref(false)
const showDeleteModal = ref(false)
const selectedProduct = ref<Product | null>(null)
const isDeleting = ref(false)

const form = useForm({
    nama_produk: '',
    tipe_produk: 'physical' as 'physical' | 'service',
    deskripsi: '',
    berat: '' as number | '',
    panjang: '' as number | '',
    lebar: '' as number | '',
    tinggi: '' as number | '',
    sku: '',
    sn: '',
    garansi: '',
    is_sellable: true,
    is_purchasable: true,
    kategori_id: '' as number | '',
    brand_id: '' as number | '',
    images: [] as File[],
})

function openCreateModal() {
    selectedProduct.value = null
    form.reset()
    form.clearErrors()
    showSlideover.value = true
}

function openEditModal(product: Product) {
    selectedProduct.value = product
    form.nama_produk = product.nama_produk
    form.tipe_produk = product.tipe_produk || 'physical'
    form.deskripsi = product.deskripsi || ''
    form.berat = product.berat ?? ''
    form.panjang = product.panjang ?? ''
    form.lebar = product.lebar ?? ''
    form.tinggi = product.tinggi ?? ''
    form.sku = product.sku || ''
    form.sn = product.sn || ''
    form.garansi = product.garansi || ''
    form.is_sellable = product.is_sellable ?? true
    form.is_purchasable = product.is_purchasable ?? true
    form.kategori_id = product.kategori_id ?? (product.kategori?.id || '')
    form.brand_id = product.brand_id ?? (product.brand?.id || '')
    form.images = []
    form.clearErrors()
    showSlideover.value = true
}

function openDetailModal(product: Product) {
    selectedProduct.value = product
    showDetailModal.value = true
}

function openDeleteModal(product: Product) {
    selectedProduct.value = product
    showDeleteModal.value = true
}

function closeSlideover() {
    showSlideover.value = false
    selectedProduct.value = null
    form.reset()
    form.clearErrors()
}

function closeDeleteModal() {
    showDeleteModal.value = false
    selectedProduct.value = null
}

function closeDetailModal() {
    showDetailModal.value = false
    selectedProduct.value = null
}

function submitForm() {
    const payload = {
        forceFormData: true,
        onSuccess: () => {
            closeSlideover()
        },
    }

    if (selectedProduct.value) {
        form.post(`/app/admin/master-data/product-data/${selectedProduct.value.id}?_method=PUT`, payload)
    } else {
        form.post('/app/admin/master-data/product-data', payload)
    }
}

function deleteProduct() {
    if (!selectedProduct.value) return
    isDeleting.value = true
    form.delete(`/app/admin/master-data/product-data/${selectedProduct.value.id}`, {
        onSuccess: () => {
            closeDeleteModal()
            isDeleting.value = false
        },
        onError: () => {
            isDeleting.value = false
        },
    })
}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6">
            <PageHeader
                title="Products Catalog"
                description="Manage products, dimensions, stock SKUs, categories, brands, and media."
                :breadcrumbs="[
                    { label: 'Master Data', href: '/app/admin/master-data/product-data' },
                    { label: 'Products' },
                ]"
            >
                <template #actions>
                    <Button @click="openCreateModal" class="shadow-sm">
                        <Plus class="h-4 w-4 mr-2" />
                        Add Product
                    </Button>
                </template>
            </PageHeader>

            <!-- Filter Card -->
            <Card class="p-5 border shadow-sm bg-card/60 backdrop-blur">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <div class="relative md:col-span-2">
                        <Search
                            class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                        />
                        <Input
                            :model-value="searchQuery"
                            @update:model-value="handleSearchInput"
                            placeholder="Search by product name or SKU..."
                            class="pl-10 h-10"
                        />
                    </div>

                    <div>
                        <Select
                            v-model="selectedKategoriFilter"
                            @update:model-value="handleFilterChange"
                        >
                            <option value="">All Categories</option>
                            <option
                                v-for="k in kategoris"
                                :key="k.id"
                                :value="k.id"
                            >
                                {{ k.nama_kategori }}
                            </option>
                        </Select>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="flex-1">
                            <Select
                                v-model="selectedBrandFilter"
                                @update:model-value="handleFilterChange"
                            >
                                <option value="">All Brands</option>
                                <option
                                    v-for="b in brands"
                                    :key="b.id"
                                    :value="b.id"
                                >
                                    {{ b.nama_brand }}
                                </option>
                            </Select>
                        </div>
                        <Button
                            v-if="searchQuery || selectedBrandFilter || selectedKategoriFilter"
                            variant="outline"
                            size="icon"
                            class="h-10 w-10 shrink-0"
                            title="Reset filters"
                            @click="resetFilters"
                        >
                            <RotateCcw class="h-4 w-4 text-muted-foreground" />
                        </Button>
                    </div>
                </div>
            </Card>

            <!-- Data Table Card -->
            <Card class="p-6 border shadow-sm">
                <DataTable
                    :data="productsList"
                    :columns="columns"
                >
                    <!-- Custom Image Column -->
                    <template #cell:image_url="{ row }">
                        <div class="flex items-center justify-center">
                            <div
                                v-if="row.image_url"
                                class="h-11 w-11 rounded-lg border overflow-hidden bg-muted/30 flex items-center justify-center shrink-0"
                            >
                                <img
                                    :src="row.image_url"
                                    :alt="row.nama_produk"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                            <div
                                v-else
                                class="h-11 w-11 rounded-lg border border-dashed bg-muted/40 flex items-center justify-center text-muted-foreground shrink-0"
                            >
                                <Package class="h-5 w-5 opacity-60" />
                            </div>
                        </div>
                    </template>

                    <!-- Product Name & SKU -->
                    <template #cell:nama_produk="{ row }">
                        <div class="space-y-1">
                            <div class="font-semibold text-sm leading-snug text-foreground">
                                {{ row.nama_produk }}
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono bg-muted px-1.5 py-0.5 rounded text-muted-foreground border">
                                    SKU: {{ row.sku || 'N/A' }}
                                </span>
                                <span
                                    v-if="row.sn"
                                    class="text-[11px] text-muted-foreground"
                                >
                                    SN: {{ row.sn }}
                                </span>
                            </div>
                        </div>
                    </template>

                    <!-- Category -->
                    <template #cell:kategori="{ row }">
                        <Badge
                            v-if="row.kategori"
                            variant="secondary"
                            class="font-normal"
                        >
                            {{ row.kategori.nama_kategori }}
                        </Badge>
                        <span v-else class="text-xs text-muted-foreground">-</span>
                    </template>

                    <!-- Brand -->
                    <template #cell:brand="{ row }">
                        <Badge
                            v-if="row.brand"
                            variant="outline"
                            class="font-normal"
                        >
                            {{ row.brand.nama_brand }}
                        </Badge>
                        <span v-else class="text-xs text-muted-foreground">-</span>
                    </template>

                    <!-- Type -->
                    <template #cell:tipe_produk="{ row }">
                        <Badge
                            :variant="row.tipe_produk === 'service' ? 'default' : 'secondary'"
                            class="capitalize text-xs"
                        >
                            {{ row.tipe_produk || 'physical' }}
                        </Badge>
                    </template>

                    <!-- Dimensions & Weight -->
                    <template #cell:dimensi="{ row }">
                        <div class="text-xs text-muted-foreground space-y-0.5">
                            <div v-if="row.berat">
                                <span class="font-medium text-foreground">{{ row.berat }}</span> g
                            </div>
                            <div v-if="row.panjang || row.lebar || row.tinggi">
                                {{ row.panjang || 0 }}×{{ row.lebar || 0 }}×{{ row.tinggi || 0 }} cm
                            </div>
                            <span
                                v-if="!row.berat && !row.panjang && !row.lebar && !row.tinggi"
                                class="text-muted-foreground/60"
                            >
                                -
                            </span>
                        </div>
                    </template>

                    <!-- Row Actions -->
                    <template #actions="{ row }">
                        <div class="flex items-center justify-end gap-1.5">
                            <Button
                                variant="ghost"
                                size="sm"
                                class="h-8 w-8 p-0"
                                title="View details"
                                @click.stop="openDetailModal(row)"
                            >
                                <Eye class="h-4 w-4 text-muted-foreground" />
                            </Button>
                            <Button
                                variant="ghost"
                                size="sm"
                                class="h-8 w-8 p-0"
                                title="Edit product"
                                @click.stop="openEditModal(row)"
                            >
                                <Pencil class="h-4 w-4" />
                            </Button>
                            <Button
                                variant="ghost"
                                size="sm"
                                class="h-8 w-8 p-0"
                                title="Delete product"
                                @click.stop="openDeleteModal(row)"
                            >
                                <Trash2 class="h-4 w-4 text-destructive" />
                            </Button>
                        </div>
                    </template>
                </DataTable>

                <div
                    v-if="productsList.length === 0"
                    class="text-center py-12 text-muted-foreground"
                >
                    <Package class="h-10 w-10 mx-auto mb-3 opacity-30" />
                    <p class="font-medium">No products found</p>
                    <p class="text-xs text-muted-foreground/80 mt-1">
                        Try modifying your search query or filters.
                    </p>
                </div>
            </Card>
        </div>

        <!-- Slideover Form based on Filament ProdukResource layout -->
        <Slideover
            :open="showSlideover"
            size="xl"
            :title="selectedProduct ? 'Edit Product' : 'Create New Product'"
            @update:open="showSlideover = $event"
        >
            <form @submit.prevent="submitForm" class="space-y-6 pb-6">
                <!-- 3 Columns Layout (2 Main Columns Left + 1 Sidebar Column Right) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- LEFT COLUMN (Span 2): Product Information & Dimensions -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Section 1: Product Information -->
                        <Card class="p-5 border space-y-4 shadow-sm">
                            <div class="flex items-center gap-2 pb-2 border-b">
                                <Info class="h-4 w-4 text-primary" />
                                <h3 class="font-semibold text-sm">Product Information</h3>
                            </div>

                            <FormField
                                label="Product Name"
                                name="nama_produk"
                                :error="form.errors.nama_produk"
                                required
                            >
                                <Input
                                    v-model="form.nama_produk"
                                    placeholder="Enter complete product name..."
                                />
                            </FormField>

                            <FormField
                                label="Full Description"
                                name="deskripsi"
                                :error="form.errors.deskripsi"
                            >
                                <Textarea
                                    v-model="form.deskripsi"
                                    rows="5"
                                    placeholder="Describe product specifications, features, or internal notes..."
                                />
                            </FormField>
                        </Card>

                        <!-- Section 2: Dimensions & Weight -->
                        <Card class="p-5 border space-y-4 shadow-sm">
                            <div class="flex items-center gap-2 pb-2 border-b">
                                <Truck class="h-4 w-4 text-primary" />
                                <h3 class="font-semibold text-sm">Dimensions & Weight</h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                <FormField
                                    label="Weight (g)"
                                    name="berat"
                                    :error="form.errors.berat"
                                >
                                    <Input
                                        v-model="form.berat"
                                        type="number"
                                        min="0"
                                        placeholder="0"
                                    />
                                </FormField>

                                <FormField
                                    label="Length (cm)"
                                    name="panjang"
                                    :error="form.errors.panjang"
                                >
                                    <Input
                                        v-model="form.panjang"
                                        type="number"
                                        min="0"
                                        placeholder="0"
                                    />
                                </FormField>

                                <FormField
                                    label="Width (cm)"
                                    name="lebar"
                                    :error="form.errors.lebar"
                                >
                                    <Input
                                        v-model="form.lebar"
                                        type="number"
                                        min="0"
                                        placeholder="0"
                                    />
                                </FormField>

                                <FormField
                                    label="Height (cm)"
                                    name="tinggi"
                                    :error="form.errors.tinggi"
                                >
                                    <Input
                                        v-model="form.tinggi"
                                        type="number"
                                        min="0"
                                        placeholder="0"
                                    />
                                </FormField>
                            </div>
                        </Card>

                        <!-- Section 3: Settings & Warranty -->
                        <Card class="p-5 border space-y-4 shadow-sm">
                            <div class="flex items-center gap-2 pb-2 border-b">
                                <Box class="h-4 w-4 text-primary" />
                                <h3 class="font-semibold text-sm">Type & Warranty</h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <FormField
                                    label="Product Type"
                                    name="tipe_produk"
                                    :error="form.errors.tipe_produk"
                                    required
                                >
                                    <Select v-model="form.tipe_produk">
                                        <option value="physical">Physical Product</option>
                                        <option value="service">Service</option>
                                    </Select>
                                </FormField>

                                <FormField
                                    label="Serial Number (SN)"
                                    name="sn"
                                    :error="form.errors.sn"
                                >
                                    <Input
                                        v-model="form.sn"
                                        placeholder="Optional SN tracking"
                                    />
                                </FormField>

                                <FormField
                                    label="Warranty"
                                    name="garansi"
                                    :error="form.errors.garansi"
                                >
                                    <Input
                                        v-model="form.garansi"
                                        placeholder="e.g. 1 Year Official"
                                    />
                                </FormField>
                            </div>

                            <div class="flex flex-wrap gap-6 pt-2 border-t">
                                <label class="flex items-center gap-2.5 text-sm font-medium cursor-pointer">
                                    <Checkbox v-model="form.is_sellable" />
                                    <span>Available for Sale (Sellable)</span>
                                </label>
                                <label class="flex items-center gap-2.5 text-sm font-medium cursor-pointer">
                                    <Checkbox v-model="form.is_purchasable" />
                                    <span>Available for Purchase (Purchasable)</span>
                                </label>
                            </div>
                        </Card>
                    </div>

                    <!-- RIGHT SIDEBAR COLUMN (Span 1): Media & Organization -->
                    <div class="space-y-6">
                        <!-- Media Upload Section -->
                        <Card class="p-5 border space-y-4 shadow-sm">
                            <div class="flex items-center gap-2 pb-2 border-b">
                                <ImageIcon class="h-4 w-4 text-primary" />
                                <h3 class="font-semibold text-sm">Media Gallery</h3>
                            </div>

                            <div class="space-y-2">
                                <MultiImageUpload
                                    v-model="form.images"
                                    :produk-id="selectedProduct?.id"
                                    :existing-images="selectedProduct?.images || []"
                                    :max-images="10"
                                />
                                <p class="text-[11px] text-muted-foreground">
                                    Upload up to 10 photos in PNG, JPG, or WebP format (max 5MB each).
                                </p>
                            </div>
                        </Card>

                        <!-- Organization Section -->
                        <Card class="p-5 border space-y-4 shadow-sm">
                            <div class="flex items-center gap-2 pb-2 border-b">
                                <Tag class="h-4 w-4 text-primary" />
                                <h3 class="font-semibold text-sm">Organization</h3>
                            </div>

                            <FormField
                                label="SKU (Stock Code)"
                                name="sku"
                                :error="form.errors.sku"
                            >
                                <Input
                                    v-model="form.sku"
                                    placeholder="Leave empty for auto-generation"
                                />
                                <p class="text-[11px] text-muted-foreground mt-1">
                                    Auto-generated if left empty when category or brand is selected.
                                </p>
                            </FormField>

                            <FormField
                                label="Category"
                                name="kategori_id"
                                :error="form.errors.kategori_id"
                            >
                                <Select v-model="form.kategori_id">
                                    <option value="">Select Category</option>
                                    <option
                                        v-for="k in kategoris"
                                        :key="k.id"
                                        :value="k.id"
                                    >
                                        {{ k.nama_kategori }}
                                    </option>
                                </Select>
                            </FormField>

                            <FormField
                                label="Brand"
                                name="brand_id"
                                :error="form.errors.brand_id"
                            >
                                <Select v-model="form.brand_id">
                                    <option value="">Select Brand</option>
                                    <option
                                        v-for="b in brands"
                                        :key="b.id"
                                        :value="b.id"
                                    >
                                        {{ b.nama_brand }}
                                    </option>
                                </Select>
                            </FormField>
                        </Card>
                    </div>
                </div>

                <!-- Sticky Actions Footer -->
                <div class="flex justify-end gap-3 pt-4 border-t sticky bottom-0 bg-background py-3">
                    <Button
                        type="button"
                        variant="outline"
                        @click="closeSlideover"
                    >
                        Cancel
                    </Button>
                    <Button type="submit" :loading="form.processing">
                        {{ selectedProduct ? 'Save Changes' : 'Create Product' }}
                    </Button>
                </div>
            </form>
        </Slideover>

        <!-- Product Details View Modal -->
        <Dialog
            :open="showDetailModal"
            @update:open="showDetailModal = $event"
            class="max-w-xl"
        >
            <div v-if="selectedProduct" class="space-y-6">
                <div class="flex items-start justify-between border-b pb-4">
                    <div>
                        <h2 class="text-lg font-bold">{{ selectedProduct.nama_produk }}</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs font-mono bg-muted px-2 py-0.5 rounded border">
                                SKU: {{ selectedProduct.sku }}
                            </span>
                            <Badge variant="outline" class="capitalize text-xs">
                                {{ selectedProduct.tipe_produk || 'physical' }}
                            </Badge>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-muted-foreground text-xs block">Category</span>
                        <span class="font-medium">{{ selectedProduct.kategori?.nama_kategori || '-' }}</span>
                    </div>
                    <div>
                        <span class="text-muted-foreground text-xs block">Brand</span>
                        <span class="font-medium">{{ selectedProduct.brand?.nama_brand || '-' }}</span>
                    </div>
                    <div>
                        <span class="text-muted-foreground text-xs block">Weight</span>
                        <span class="font-medium">{{ selectedProduct.berat ? selectedProduct.berat + ' g' : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-muted-foreground text-xs block">Dimensions (L×W×H)</span>
                        <span class="font-medium">
                            {{ selectedProduct.panjang || 0 }}×{{ selectedProduct.lebar || 0 }}×{{ selectedProduct.tinggi || 0 }} cm
                        </span>
                    </div>
                </div>

                <div v-if="selectedProduct.deskripsi">
                    <span class="text-muted-foreground text-xs block mb-1">Description</span>
                    <div
                        class="p-3 bg-muted/30 rounded-lg text-sm leading-relaxed whitespace-pre-wrap border"
                        v-html="selectedProduct.deskripsi"
                    />
                </div>

                <div class="flex justify-end pt-2 border-t">
                    <Button variant="outline" @click="closeDetailModal">Close</Button>
                </div>
            </div>
        </Dialog>

        <!-- Delete Confirmation Modal -->
        <Dialog
            :open="showDeleteModal"
            @update:open="showDeleteModal = $event"
            class="max-w-md"
        >
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-destructive">Delete Product</h2>
                <p class="text-sm text-muted-foreground">
                    Are you sure you want to permanently delete
                    <strong class="text-foreground">{{ selectedProduct?.nama_produk }}</strong>? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-2 pt-4 border-t">
                    <Button variant="outline" @click="closeDeleteModal">
                        Cancel
                    </Button>
                    <Button
                        variant="destructive"
                        @click="deleteProduct"
                        :loading="isDeleting"
                    >
                        Delete Product
                    </Button>
                </div>
            </div>
        </Dialog>
    </AppLayout>
</template>
