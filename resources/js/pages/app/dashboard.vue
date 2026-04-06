<script setup lang="ts">
import { computed } from 'vue'
import { Package, DollarSign, Users, ShoppingCart, TrendingUp, ArrowUpRight, ArrowDownRight } from 'lucide-vue-next'
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

const stats = [
    {
        title: 'Total Revenue',
        value: 'Rp 125.500.000',
        change: '+12.5%',
        trend: 'up',
        icon: DollarSign,
    },
    {
        title: 'Total Sales',
        value: '1,234',
        change: '+8.2%',
        trend: 'up',
        icon: ShoppingCart,
    },
    {
        title: 'Products',
        value: '567',
        change: '-2.1%',
        trend: 'down',
        icon: Package,
    },
    {
        title: 'Customers',
        value: '89',
        change: '+5.7%',
        trend: 'up',
        icon: Users,
    },
]

const recentSales = [
    { id: 1, customer: 'PT Maju Jaya', product: 'iPhone 15 Pro', amount: 'Rp 21.500.000', date: '2024-01-15' },
    { id: 2, customer: 'Budi Santoso', product: 'MacBook Air M3', amount: 'Rp 18.900.000', date: '2024-01-14' },
    { id: 3, customer: 'CV Teknologi', product: 'iPad Pro 12.9"', amount: 'Rp 24.500.000', date: '2024-01-14' },
    { id: 4, customer: 'Andi Wijaya', product: 'AirPods Pro', amount: 'Rp 4.500.000', date: '2024-01-13' },
    { id: 5, customer: 'PT Sinar Mas', product: 'Mac Studio', amount: 'Rp 35.000.000', date: '2024-01-13' },
]

const lowStockItems = [
    { id: 1, name: 'iPhone 14 Pro Max', sku: 'IPP14PM-256-BLK', stock: 3, min: 10 },
    { id: 2, name: 'AirPods Pro 2', sku: 'APP2-USB-C', stock: 5, min: 15 },
    { id: 3, name: 'Apple Watch Ultra 2', sku: 'AWU2-49-TI', stock: 2, min: 5 },
    { id: 4, name: 'MacBook Pro 14"', sku: 'MBP14-M3-512', stock: 4, min: 8 },
]
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="Dashboard"
                description="Welcome back! Here's what's happening with your store."
            >
                <template #actions>
                    <Button>Download Report</Button>
                </template>
            </PageHeader>
            
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card v-for="stat in stats" :key="stat.title">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-muted">
                                <component :is="stat.icon" class="h-6 w-6 text-muted-foreground" />
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
