<script setup lang="ts">
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Package, DollarSign, Users, ShoppingCart, TrendingUp, ArrowUpRight, ArrowDownRight, Calendar } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import AppLayout from '@/components/layout/AppLayout.vue'
import Card from '@/components/ui/card.vue'
import CardContent from '@/components/ui/card-content.vue'
import CardHeader from '@/components/ui/card-header.vue'
import CardTitle from '@/components/ui/card-title.vue'
import Badge from '@/components/ui/badge.vue'
import Button from '@/components/ui/button.vue'
import PageHeader from '@/components/layout/PageHeader.vue'
import SectionCard from '@/components/layout/SectionCard.vue'

const props = defineProps({
    stats: {
        type: Array,
        default: () => [],
    },
    recentSales: {
        type: Array,
        default: () => [],
    },
    lowStockItems: {
        type: Array,
        default: () => [],
    },
})

const iconMap = {
    DollarSign,
    ShoppingCart,
    Package,
    Users,
}

// Date filter
const dateFrom = ref('')  // Default: first day of current month
const dateTo = ref('')    // Default: today

// Set default dates on mount
const now = new Date()
const firstDayOfMonth = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0]
dateTo.value = now.toISOString().split('T')[0]
dateFrom.value = firstDayOfMonth

function applyFilter() {
    router.reload({
        data: {
            from: dateFrom.value,
            to: dateTo.value,
        },
        only: ['stats', 'recentSales'],
    })
}

function resetFilter() {
    dateFrom.value = firstDayOfMonth
    dateTo.value = now.toISOString().split('T')[0]
    router.reload({
        only: ['stats', 'recentSales'],
    })
}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="Dashboard"
                description="Welcome back! Here's what's happening with your store."
            />
            
            <!-- Date Filter -->
            <div class="flex items-center gap-4 p-4 bg-card rounded-lg border">
                <div class="flex items-center gap-2">
                    <Calendar class="h-4 w-4 text-muted-foreground" />
                    <span class="text-sm font-medium">Filter Tanggal:</span>
                </div>
                <div class="flex items-center gap-2">
                    <input
                        v-model="dateFrom"
                        type="date"
                        class="h-9 rounded-md border border-input bg-background px-3 py-1 text-sm"
                    />
                    <span class="text-muted-foreground">s/d</span>
                    <input
                        v-model="dateTo"
                        type="date"
                        class="h-9 rounded-md border border-input bg-background px-3 py-1 text-sm"
                    />
                </div>
                <Button size="sm" @click="applyFilter">
                    Apply
                </Button>
                <Button variant="ghost" size="sm" @click="resetFilter">
                    Reset
                </Button>
            </div>
            
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card v-for="stat in stats" :key="stat.title">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-muted">
                                <component :is="iconMap[stat.icon]" class="h-6 w-6 text-muted-foreground" />
                            </div>
                            <Badge :variant="stat.trend === 'up' ? 'success' : 'destructive'" class="flex items-center gap-1">
                                <ArrowUpRight v-if="stat.trend === 'up'" class="h-3 w-3" />
                                <ArrowDownRight v-else class="h-3 w-3" />
                                {{ stat.change }}
                            </Badge>
                        </div>
                        <div class="mt-4">
                            <p class="text-2xl font-bold">{{ stat.value }}</p>
                            <p class="text-sm text-muted-foreground">{{ stat.title }}</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
            
            <div class="grid gap-6 lg:grid-cols-2">
                <SectionCard variant="card">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold">Recent Sales</h3>
                        <Button variant="ghost" size="sm">View all</Button>
                    </div>
                    <div class="space-y-4">
                        <div
                            v-for="sale in recentSales"
                            :key="sale.id"
                            class="flex items-center justify-between py-2 border-b last:border-0"
                        >
                            <div>
                                <p class="font-medium">{{ sale.customer }}</p>
                                <p class="text-sm text-muted-foreground">{{ sale.product }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">{{ sale.amount }}</p>
                                <p class="text-sm text-muted-foreground">{{ sale.date }}</p>
                            </div>
                        </div>
                    </div>
                </SectionCard>
                
                <SectionCard variant="card">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold">Low Stock Alert</h3>
                        <Button variant="ghost" size="sm">View all</Button>
                    </div>
                    <div class="space-y-4">
                        <div
                            v-for="item in lowStockItems"
                            :key="item.id"
                            class="flex items-center justify-between py-2 border-b last:border-0"
                        >
                            <div>
                                <p class="font-medium">{{ item.name }}</p>
                                <p class="text-sm text-muted-foreground">{{ item.sku }}</p>
                            </div>
                            <Badge variant="destructive">
                                {{ item.stock }} left
                            </Badge>
                        </div>
                    </div>
                </SectionCard>
            </div>
        </div>
    </AppLayout>
</template>
