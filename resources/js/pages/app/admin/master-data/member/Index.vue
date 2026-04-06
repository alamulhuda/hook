<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue'
import { ref } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import { Plus, Search, Pencil, Trash2 } from 'lucide-vue-next'
import PageHeader from '@/components/layout/PageHeader.vue'
import Button from '@/components/ui/button.vue'
import Input from '@/components/ui/input.vue'
import Card from '@/components/ui/card.vue'
import DataTable from '@/components/tables/DataTable.vue'
import Dialog from '@/components/ui/dialog.vue'
import FormField from '@/components/forms/FormField.vue'
import Badge from '@/components/ui/badge.vue'

const page = usePage()

interface Member {
    id: number
    kode_member: string
    nama_member: string
    email: string | null
    no_hp: string
    alamat: string | null
    provinsi: string | null
    kota: string | null
    kecamatan: string | null
    image_url: string | null
}

const members = ref<Member[]>(page.props.members || [])
const isLoading = ref(false)
const showCreateModal = ref(false)
const showDeleteModal = ref(false)
const selectedMember = ref<Member | null>(null)
const searchQuery = ref('')

const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0,
})

const columns = [
    { key: 'kode_member', label: 'Code', sortable: true },
    { key: 'nama_member', label: 'Name', sortable: true },
    { key: 'no_hp', label: 'Phone', sortable: true },
    { key: 'email', label: 'Email', sortable: false },
    { key: 'kota', label: 'City', sortable: true },
]

const form = useForm({
    nama_member: '',
    email: '',
    no_hp: '',
    alamat: '',
    provinsi: '',
    kota: '',
    kecamatan: '',
    image_url: '',
})

function openCreateModal() {
    selectedMember.value = null
    form.reset()
    form.clearErrors()
    showCreateModal.value = true
}

function openEditModal(member: Member) {
    selectedMember.value = member
    form.nama_member = member.nama_member
    form.email = member.email || ''
    form.no_hp = member.no_hp
    form.alamat = member.alamat || ''
    form.provinsi = member.provinsi || ''
    form.kota = member.kota || ''
    form.kecamatan = member.kecamatan || ''
    form.image_url = member.image_url || ''
    showCreateModal.value = true
}

function openDeleteModal(member: Member) {
    selectedMember.value = member
    showDeleteModal.value = true
}

function handleSort(field: string, direction: 'asc' | 'desc') {}
function handlePageChange(page: number) {
    pagination.value.current_page = page
}
function handleRowClick(member: Member) {
    openEditModal(member)
}

function submitForm() {
    if (selectedMember.value) {
        form.put(`/app/admin/master-data/member/${selectedMember.value.id}`, {
            onSuccess: () => {
                showCreateModal.value = false
                form.reset()
            },
        })
    } else {
        form.post('/app/admin/master-data/member', {
            onSuccess: () => {
                showCreateModal.value = false
                form.reset()
            },
        })
    }
}

function deleteMember() {
    if (selectedMember.value) {
        form.delete(`/app/admin/master-data/member/${selectedMember.value.id}`, {
            onSuccess: () => {
                showDeleteModal.value = false
                selectedMember.value = null
            },
        })
    }
}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="Members"
                description="Manage your store members/customers."
                :breadcrumbs="[
                    { label: 'Master Data', href: '/app/admin/master-data' },
                    { label: 'Members' }
                ]"
            >
                <template #actions>
                    <Button @click="openCreateModal">
                        <Plus class="h-4 w-4 mr-2" />
                        Add Member
                    </Button>
                </template>
            </PageHeader>
            
            <Card class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="relative flex-1 max-w-sm">
                        <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input v-model="searchQuery" placeholder="Search members..." class="pl-10" />
                    </div>
                </div>
                
                <DataTable
                    :data="members"
                    :columns="columns"
                    :pagination="pagination"
                    :loading="isLoading"
                    @sort="handleSort"
                    @page-change="handlePageChange"
                    @row-click="handleRowClick"
                >
                    <template #actions="{ row }">
                        <div class="flex items-center gap-2">
                            <Button variant="ghost" size="sm" @click.stop="openEditModal(row)">
                                <Pencil class="h-4 w-4" />
                            </Button>
                            <Button variant="ghost" size="sm" @click.stop="openDeleteModal(row)">
                                <Trash2 class="h-4 w-4 text-destructive" />
                            </Button>
                        </div>
                    </template>
                </DataTable>
            </Card>
        </div>
        
        <Dialog :open="showCreateModal" @update:open="showCreateModal = $event" class="max-w-lg">
            <div class="space-y-4">
                <h2 class="text-lg font-semibold">
                    {{ selectedMember ? 'Edit Member' : 'Create Member' }}
                </h2>
                
                <form @submit.prevent="submitForm" class="space-y-4">
                    <FormField label="Nama Member" name="nama_member" required>
                        <Input v-model="form.nama_member" placeholder="Enter member name" />
                        <p v-if="form.errors.nama_member" class="text-sm text-red-500">{{ form.errors.nama_member }}</p>
                    </FormField>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <FormField label="No. HP" name="no_hp" required>
                            <Input v-model="form.no_hp" placeholder="08xxxxxxxxxx" />
                            <p v-if="form.errors.no_hp" class="text-sm text-red-500">{{ form.errors.no_hp }}</p>
                        </FormField>
                        <FormField label="Email" name="email">
                            <Input v-model="form.email" type="email" placeholder="email@example.com" />
                        </FormField>
                    </div>
                    
                    <FormField label="Alamat" name="alamat">
                        <textarea v-model="form.alamat" placeholder="Enter address" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" rows="3"></textarea>
                    </FormField>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <FormField label="Provinsi" name="provinsi">
                            <Input v-model="form.provinsi" placeholder="Provinsi" />
                        </FormField>
                        <FormField label="Kota" name="kota">
                            <Input v-model="form.kota" placeholder="Kota" />
                        </FormField>
                        <FormField label="Kecamatan" name="kecamatan">
                            <Input v-model="form.kecamatan" placeholder="Kecamatan" />
                        </FormField>
                    </div>
                    
                    <div class="flex justify-end gap-2 pt-4">
                        <Button type="button" variant="outline" @click="showCreateModal = false">Cancel</Button>
                        <Button type="submit" :loading="form.processing">
                            {{ selectedMember ? 'Update' : 'Create' }}
                        </Button>
                    </div>
                </form>
            </div>
        </Dialog>
        
        <Dialog :open="showDeleteModal" @update:open="showDeleteModal = $event" class="max-w-md">
            <div class="space-y-4">
                <h2 class="text-lg font-semibold">Delete Member</h2>
                <p class="text-muted-foreground">
                    Are you sure you want to delete <strong>{{ selectedMember?.nama_member }}</strong>?
                </p>
                <div class="flex justify-end gap-2 pt-4">
                    <Button variant="outline" @click="showDeleteModal = false">Cancel</Button>
                    <Button variant="destructive" @click="deleteMember" :loading="form.processing">Delete</Button>
                </div>
            </div>
        </Dialog>
    </AppLayout>
</template>
