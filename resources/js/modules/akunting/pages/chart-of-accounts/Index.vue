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
    { key: 'kode', label: 'Code', sortable: true },
    { key: 'nama', label: 'Name', sortable: true },
    { key: 'jenis', label: 'Type', sortable: true },
    { key: 'saldo', label: 'Balance', sortable: true },
]

function handleSort() {}
function handlePageChange() {}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="Chart of Accounts"
                description="Manage your chart of accounts for accounting."
                :breadcrumbs="[
                    { label: 'Accounting' },
                    { label: 'Chart of Accounts' }
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
