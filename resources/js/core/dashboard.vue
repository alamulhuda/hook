<script setup lang="ts">
import { ref, computed, defineAsyncComponent } from 'vue'
import { router } from '@inertiajs/vue3'
import { Package, DollarSign, Users, ShoppingCart, TrendingUp, ArrowUpRight, ArrowDownRight, Calendar } from 'lucide-vue-next'
import AppLayout from '@/components/layout/AppLayout.vue'
import Card from '@/components/ui/card.vue'
import CardContent from '@/components/ui/card-content.vue'
import Button from '@/components/ui/button.vue'
import PageHeader from '@/components/layout/PageHeader.vue'
import { getModuleDashboardWidgets } from '@/module-loader'

// ── Props (core data only — widgets fetch themselves) ──────────────────────────
const props = defineProps({
    stats: {
        type: Array,
        default: () => [],
    },
})

const iconMap: Record<string, any> = {
    DollarSign,
    ShoppingCart,
    Package,
    Users,
}

// ── Date filter ────────────────────────────────────────────────────────────────
const now = new Date()
const firstDayOfMonth = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0]

const dateFrom = ref(firstDayOfMonth)
const dateTo   = ref(now.toISOString().split('T')[0])

// Refs to widget components so we can call refetch() on date change
const widgetRefs = ref<Record<string, any>>({})

function applyFilter() {
    router.reload({
        data: { from: dateFrom.value, to: dateTo.value },
        only: ['stats'],
    })
    // Tell widgets that support date filtering to re-fetch
    Object.values(widgetRefs.value).forEach((ref) => ref?.refetch?.())
}

function resetFilter() {
    dateFrom.value = firstDayOfMonth
    dateTo.value = now.toISOString().split('T')[0]
    router.reload({ only: ['stats'] })
    Object.values(widgetRefs.value).forEach((ref) => ref?.refetch?.())
}

// ── Module widgets ─────────────────────────────────────────────────────────────
// Loaded at build time via module-loader — no hardcoding needed.
// Add / remove a module's routes.ts to include/exclude its widget.
const widgets = getModuleDashboardWidgets().map((w) => ({
    ...w,
    asyncComponent: defineAsyncComponent(w.component),
}))
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="Dashboard"
                description="Welcome back! Here's what's happening with your store."
            />

            <!-- Date Filter -->
            <div class="flex flex-wrap items-center gap-4 p-4 bg-card rounded-lg border">
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
                <Button size="sm" @click="applyFilter">Apply</Button>
                <Button variant="ghost" size="sm" @click="resetFilter">Reset</Button>
            </div>

            <!-- Stat Cards (core MVP — always visible) -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card v-for="stat in stats" :key="stat.title">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-muted">
                                <component :is="iconMap[stat.icon]" class="h-6 w-6 text-muted-foreground" />
                            </div>
                            <span
                                :class="[
                                    'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium',
                                    stat.trend === 'up'
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                        : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                ]"
                            >
                                <ArrowUpRight v-if="stat.trend === 'up'" class="h-3 w-3" />
                                <ArrowDownRight v-else class="h-3 w-3" />
                                {{ stat.change }}
                            </span>
                        </div>
                        <div class="mt-4">
                            <p class="text-2xl font-bold">{{ stat.value }}</p>
                            <p class="text-sm text-muted-foreground">{{ stat.title }}</p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Module widgets — auto-discovered, sortable by order, disable by removing module -->
            <div
                v-if="widgets.length > 0"
                class="grid gap-6 lg:grid-cols-2"
            >
                <div
                    v-for="widget in widgets"
                    :key="widget.id"
                    :class="widget.cols === 2 ? 'lg:col-span-2' : ''"
                >
                    <component
                        :is="widget.asyncComponent"
                        :ref="(el: any) => { if (el) widgetRefs[widget.id] = el }"
                        :from="dateFrom"
                        :to="dateTo"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
