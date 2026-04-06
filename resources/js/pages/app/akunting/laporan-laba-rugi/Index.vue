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
    { key: 'periode', label: 'Period', sortable: true },
    { key: 'total_penjualan', label: 'Total Sales', sortable: true },
    { key: 'total_beban', label: 'Total Expenses', sortable: true },
    { key: 'laba_rugi', label: 'Profit/Loss', sortable: true },
]

function handleSort() {}
function handlePageChange() {}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="Income Statement (Laba Rugi)"
                description="View your profit and loss report."
                :breadcrumbs="[
                    { label: 'Accounting' },
                    { label: 'Income Statement' }
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
