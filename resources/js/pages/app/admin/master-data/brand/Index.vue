<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue'
import { ref } from 'vue'
import { usePage, useForm, router } from '@inertiajs/vue3'
import { Plus, Search, Pencil, Trash2 } from 'lucide-vue-next'
import PageHeader from '@/components/layout/PageHeader.vue'
import Button from '@/components/ui/button.vue'
import Input from '@/components/ui/input.vue'
import Card from '@/components/ui/card.vue'
import DataTable from '@/components/tables/DataTable.vue'
import DropdownMenu from '@/components/ui/dropdown-menu/index.vue'
import DropdownMenuContent from '@/components/ui/dropdown-menu/dropdown-menu-content.vue'
import DropdownMenuItem from '@/components/ui/dropdown-menu/dropdown-menu-item.vue'
import DropdownMenuSeparator from '@/components/ui/dropdown-menu/dropdown-menu-separator.vue'
import DropdownMenuTrigger from '@/components/ui/dropdown-menu/dropdown-menu-item.vue'
import Dialog from '@/components/ui/dialog.vue'
import FormField from '@/components/forms/FormField.vue'

const page = usePage()

interface Brand {
    id: number
    nama: string
    deskripsi: string
}

const brands = ref<Brand[]>(page.props.brands || [])
const isLoading = ref(false)
const showCreateModal = ref(false)
const showDeleteModal = ref(false)
const selectedBrand = ref<Brand | null>(null)
const searchQuery = ref('')

const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0,
})

const columns = [
    { key: 'nama', label: 'Name', sortable: true },
    { key: 'deskripsi', label: 'Description', sortable: false },
]

const form = useForm({
    nama: '',
    deskripsi: '',
})

function openCreateModal() {
    selectedBrand.value = null
    form.reset()
    form.clearErrors()
    showCreateModal.value = true
}

function openEditModal(brand: Brand) {
    selectedBrand.value = brand
    form.nama = brand.nama
    form.deskripsi = brand.deskripsi
    showCreateModal.value = true
}

function openDeleteModal(brand: Brand) {
    selectedBrand.value = brand
    showDeleteModal.value = true
}

function handleSort(field: string, direction: 'asc' | 'desc') {}
function handlePageChange(page: number) {
    pagination.value.current_page = page
}
function handleRowClick(brand: Brand) {
    openEditModal(brand)
}

function submitForm() {
    if (selectedBrand.value) {
        form.put(`/app/admin/master-data/brand/${selectedBrand.value.id}`, {
            onSuccess: () => {
                showCreateModal.value = false
                form.reset()
            },
        })
    } else {
        form.post('/app/admin/master-data/brand', {
            onSuccess: () => {
                showCreateModal.value = false
                form.reset()
            },
        })
    }
}

function deleteBrand() {
    if (selectedBrand.value) {
        form.delete(`/app/admin/master-data/brand/${selectedBrand.value.id}`, {
            onSuccess: () => {
                showDeleteModal.value = false
                selectedBrand.value = null
            },
        })
    }
}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="Brands"
                description="Manage your product brands."
                :breadcrumbs="[
                    { label: 'Master Data', href: '/app/admin/master-data' },
                    { label: 'Brands' }
                ]"
            >
                <template #actions>
                    <Button @click="openCreateModal">
                        <Plus class="h-4 w-4 mr-2" />
                        Add Brand
                    </Button>
                </template>
            </PageHeader>
            
            <Card class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="relative flex-1 max-w-sm">
                        <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input v-model="searchQuery" placeholder="Search brands..." class="pl-10" />
                    </div>
                </div>
                
                <DataTable
                    :data="brands"
                    :columns="columns"
                    :pagination="pagination"
                    :loading="isLoading"
                    @sort="handleSort"
                    @page-change="handlePageChange"
                    @row-click="handleRowClick"
                />
            </Card>
        </div>
        
        <Dialog :open="showCreateModal" @update:open="showCreateModal = $event" class="max-w-md">
            <div class="space-y-4">
                <h2 class="text-lg font-semibold">
                    {{ selectedBrand ? 'Edit Brand' : 'Create Brand' }}
                </h2>
                
                <form @submit.prevent="submitForm" class="space-y-4">
                    <FormField label="Name" name="nama" required>
                        <Input v-model="form.nama" placeholder="Enter brand name" />
                        <p v-if="form.errors.nama" class="text-sm text-red-500">{{ form.errors.nama }}</p>
                    </FormField>
                    
                    <FormField label="Description" name="deskripsi">
                        <Input v-model="form.deskripsi" placeholder="Enter description" />
                    </FormField>
                    
                    <div class="flex justify-end gap-2 pt-4">
                        <Button type="button" variant="outline" @click="showCreateModal = false">Cancel</Button>
                        <Button type="submit" :loading="form.processing">
                            {{ selectedBrand ? 'Update' : 'Create' }}
                        </Button>
                    </div>
                </form>
            </div>
        </Dialog>
        
        <Dialog :open="showDeleteModal" @update:open="showDeleteModal = $event" class="max-w-md">
            <div class="space-y-4">
                <h2 class="text-lg font-semibold">Delete Brand</h2>
                <p class="text-muted-foreground">
                    Are you sure you want to delete <strong>{{ selectedBrand?.nama }}</strong>?
                </p>
                <div class="flex justify-end gap-2 pt-4">
                    <Button variant="outline" @click="showDeleteModal = false">Cancel</Button>
                    <Button variant="destructive" @click="deleteBrand" :loading="form.processing">Delete</Button>
                </div>
            </div>
        </Dialog>
    </AppLayout>
</template>
