<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue'
import { ref } from 'vue'
import PageHeader from '@/components/layout/PageHeader.vue'
import Card from '@/components/ui/card.vue'
import DataTable from '@/components/tables/DataTable.vue'

const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0,
})

const columns = [
    { key: 'tanggal', label: 'Date', sortable: true },
    { key: 'keterangan', label: 'Description', sortable: false },
    { key: 'akun', label: 'Account', sortable: true },
    { key: 'debit', label: 'Debit', sortable: true },
    { key: 'kredit', label: 'Credit', sortable: true },
]

function handleSort() {}
function handlePageChange() {}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="Input Transaction"
                description="Record your accounting transactions."
                :breadcrumbs="[
                    { label: 'Accounting' },
                    { label: 'Input Transaction' }
                ]"
            />
            
            <Card class="p-6">
                <DataTable
                    :data="[]"
                    :columns="columns"
                    :pagination="pagination"
                    :loading="false"
                    @sort="handleSort"
                    @page-change="handlePageChange"
                />
            </Card>
        </div>
    </AppLayout>
</template>
