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
    { key: 'reference', label: 'Reference', sortable: true },
    { key: 'tipe', label: 'Type', sortable: true },
    { key: 'catatan', label: 'Note', sortable: false },
    { key: 'status', label: 'Status', sortable: true },
]

function handleSort() {}
function handlePageChange() {}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="Stock Adjustment"
                description="Manage inventory stock adjustments."
                :breadcrumbs="[
                    { label: 'Inventory', href: '/app/admin/inventory' },
                    { label: 'Stock Adjustment' }
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
