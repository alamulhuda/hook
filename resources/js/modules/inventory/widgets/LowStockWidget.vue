<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from '@/lib/axios'
import Badge from '@/components/ui/badge.vue'
import SectionCard from '@/components/layout/SectionCard.vue'
import Button from '@/components/ui/button.vue'
import { AlertTriangle, Loader2 } from 'lucide-vue-next'

interface LowStockItem {
    id: number
    name: string
    sku: string
    stock: number
    minimum: number
}

const items = ref<LowStockItem[]>([])
const loading = ref(true)
const error = ref(false)

onMounted(async () => {
    try {
        const res = await axios.get('/app/modules/inventory/widgets/low-stock')
        items.value = res.data.data
    } catch {
        error.value = true
    } finally {
        loading.value = false
    }
})
</script>

<template>
    <SectionCard variant="card">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <AlertTriangle class="h-4 w-4 text-destructive" />
                <h3 class="font-semibold">Low Stock Alert</h3>
            </div>
            <Button variant="ghost" size="sm" as="a" href="/app/modules/inventory/stock-opname">
                View all
            </Button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-8">
            <Loader2 class="h-5 w-5 animate-spin text-muted-foreground" />
        </div>

        <!-- Error -->
        <p v-else-if="error" class="text-sm text-muted-foreground text-center py-4">
            Unable to load stock data.
        </p>

        <!-- Empty -->
        <p v-else-if="items.length === 0" class="text-sm text-muted-foreground text-center py-4">
            🎉 All products are well-stocked.
        </p>

        <!-- Data -->
        <div v-else class="space-y-3">
            <div
                v-for="item in items"
                :key="item.id"
                class="flex items-center justify-between py-2 border-b last:border-0"
            >
                <div>
                    <p class="font-medium text-sm">{{ item.name }}</p>
                    <p class="text-xs text-muted-foreground">{{ item.sku }}</p>
                </div>
                <Badge variant="destructive" class="shrink-0">
                    {{ item.stock }} / {{ item.minimum }} min
                </Badge>
            </div>
        </div>
    </SectionCard>
</template>
