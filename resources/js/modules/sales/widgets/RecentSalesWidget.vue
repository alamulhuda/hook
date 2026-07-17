<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from '@/lib/axios'
import SectionCard from '@/components/layout/SectionCard.vue'
import Button from '@/components/ui/button.vue'
import { ShoppingCart, Loader2 } from 'lucide-vue-next'

interface SaleItem {
    id: number
    customer: string
    nota: string
    amount: string
    date: string
}

const props = defineProps<{
    from?: string
    to?: string
}>()

const items = ref<SaleItem[]>([])
const loading = ref(true)
const error = ref(false)

async function fetchData() {
    loading.value = true
    error.value = false
    try {
        const res = await axios.get('/app/modules/sales/widgets/recent-sales', {
            params: { from: props.from, to: props.to },
        })
        items.value = res.data.data
    } catch {
        error.value = true
    } finally {
        loading.value = false
    }
}

onMounted(fetchData)

// Re-fetch when parent date filter changes
defineExpose({ refetch: fetchData })
</script>

<template>
    <SectionCard variant="card">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <ShoppingCart class="h-4 w-4 text-primary" />
                <h3 class="font-semibold">Recent Sales</h3>
            </div>
            <Button variant="ghost" size="sm" as="a" href="/app/admin/transactions/penjualan">
                View all
            </Button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-8">
            <Loader2 class="h-5 w-5 animate-spin text-muted-foreground" />
        </div>

        <!-- Error -->
        <p v-else-if="error" class="text-sm text-muted-foreground text-center py-4">
            Unable to load sales data.
        </p>

        <!-- Empty -->
        <p v-else-if="items.length === 0" class="text-sm text-muted-foreground text-center py-4">
            No sales found for this period.
        </p>

        <!-- Data -->
        <div v-else class="space-y-3">
            <div
                v-for="item in items"
                :key="item.id"
                class="flex items-center justify-between py-2 border-b last:border-0"
            >
                <div>
                    <p class="font-medium text-sm">{{ item.customer }}</p>
                    <p class="text-xs text-muted-foreground">{{ item.nota }}</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-sm font-semibold">{{ item.amount }}</p>
                    <p class="text-xs text-muted-foreground">{{ item.date }}</p>
                </div>
            </div>
        </div>
    </SectionCard>
</template>
