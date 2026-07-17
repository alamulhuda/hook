<script setup lang="ts">
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import {
    Package,
    Search,
    CheckCircle2,
    XCircle,
    SlidersHorizontal,
    Sparkles,
    ArrowRight,
    Shield,
    ChevronRight,
    Layers,
} from 'lucide-vue-next'
import AppLayout from '@/components/layout/AppLayout.vue'
import PageHeader from '@/components/layout/PageHeader.vue'
import Card from '@/components/ui/card.vue'
import CardContent from '@/components/ui/card-content.vue'
import Button from '@/components/ui/button.vue'
import Input from '@/components/ui/input.vue'
import ModuleDetailModal from './ModuleDetailModal.vue'

interface ChangelogItem {
    version: string
    date: string
    changes: string[]
}

interface ModuleItem {
    id: string
    name: string
    version: string
    publisher: string
    description: string
    changelog: ChangelogItem[]
    enabled: boolean
    directory: string
}

const props = defineProps<{
    modules: ModuleItem[]
}>()

const search = ref('')
const activeTab = ref<'all' | 'enabled' | 'disabled'>('all')

const selectedModule = ref<ModuleItem | null>(null)
const isModalOpen = ref(false)
const isProcessing = ref<Record<string, boolean>>({})

// Summary computed stats
const totalCount = computed(() => props.modules.length)
const enabledCount = computed(() => props.modules.filter(m => m.enabled).length)
const disabledCount = computed(() => props.modules.filter(m => !m.enabled).length)

// Filtered modules list
const filteredModules = computed(() => {
    return props.modules.filter(mod => {
        // Tab filter
        if (activeTab.value === 'enabled' && !mod.enabled) return false
        if (activeTab.value === 'disabled' && mod.enabled) return false

        // Search filter
        if (search.value.trim() !== '') {
            const query = search.value.toLowerCase()
            const matchesName = mod.name.toLowerCase().includes(query)
            const matchesDesc = mod.description.toLowerCase().includes(query)
            const matchesPub = mod.publisher.toLowerCase().includes(query)
            return matchesName || matchesDesc || matchesPub
        }

        return true
    })
})

function openDetails(mod: ModuleItem) {
    selectedModule.value = mod
    isModalOpen.value = true
}

function quickToggle(mod: ModuleItem, e: Event) {
    e.stopPropagation()
    isProcessing.value[mod.id] = true
    router.post(`/settings/modules/${mod.id}/toggle`, {}, {
        onSuccess: () => {
            isProcessing.value[mod.id] = false
            if (selectedModule.value?.id === mod.id) {
                // Update selected module status if modal is open
                selectedModule.value = {
                    ...selectedModule.value,
                    enabled: !selectedModule.value.enabled
                }
            }
        },
        onFinish: () => {
            isProcessing.value[mod.id] = false
        }
    })
}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-8 p-6 lg:p-8">
            <PageHeader
                title="Modules Management"
                description="Manage installed modular features outside the MVP core. Enable, disable, and inspect detailed changelogs."
                :breadcrumbs="[
                    { label: 'Settings', href: '/app/settings' },
                    { label: 'Modules Management' }
                ]"
            />

            <!-- Summary Stats Cards -->
            <div class="grid gap-6 sm:grid-cols-3">
                <Card class="p-6 transition-all hover:shadow-md border-l-4 border-l-primary">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Total Installed Modules</p>
                            <p class="mt-2 text-3xl font-bold tracking-tight">{{ totalCount }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <Layers class="h-6 w-6" />
                        </div>
                    </div>
                </Card>

                <Card class="p-6 transition-all hover:shadow-md border-l-4 border-l-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Active & Loaded</p>
                            <p class="mt-2 text-3xl font-bold tracking-tight text-green-600 dark:text-green-400">{{ enabledCount }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-500/10 text-green-600 dark:text-green-400">
                            <CheckCircle2 class="h-6 w-6" />
                        </div>
                    </div>
                </Card>

                <Card class="p-6 transition-all hover:shadow-md border-l-4 border-l-amber-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Currently Disabled</p>
                            <p class="mt-2 text-3xl font-bold tracking-tight text-amber-600 dark:text-amber-400">{{ disabledCount }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
                            <XCircle class="h-6 w-6" />
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Search and Filter Tabs -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 border-b pb-4">
                <div class="flex items-center gap-2 bg-muted/60 p-1 rounded-xl">
                    <button
                        v-for="tab in [
                            { id: 'all', label: 'All Modules', count: totalCount },
                            { id: 'enabled', label: 'Active', count: enabledCount },
                            { id: 'disabled', label: 'Disabled', count: disabledCount },
                        ]"
                        :key="tab.id"
                        @click="activeTab = tab.id as any"
                        class="px-4 py-2 rounded-lg text-xs font-semibold transition-all flex items-center gap-2"
                        :class="activeTab === tab.id ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'"
                    >
                        <span>{{ tab.label }}</span>
                        <span 
                            class="px-1.5 py-0.2 rounded-full text-[10px]"
                            :class="activeTab === tab.id ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground'"
                        >
                            {{ tab.count }}
                        </span>
                    </button>
                </div>

                <div class="relative w-full sm:w-72">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <Input
                        v-model="search"
                        placeholder="Search modules..."
                        class="pl-9 bg-background"
                    />
                </div>
            </div>

            <!-- Modules Grid -->
            <div v-if="filteredModules.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card 
                    v-for="mod in filteredModules" 
                    :key="mod.id"
                    class="group relative flex flex-col justify-between overflow-hidden transition-all hover:shadow-lg border cursor-pointer hover:border-primary/50"
                    @click="openDetails(mod)"
                >
                    <!-- Top accent line -->
                    <div 
                        class="absolute top-0 left-0 right-0 h-1 transition-all"
                        :class="mod.enabled ? 'bg-green-500' : 'bg-muted'"
                    />

                    <div class="p-6 space-y-4">
                        <!-- Card Header -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:scale-105 transition-transform">
                                    <Package class="h-5 w-5" />
                                </div>
                                <div>
                                    <h3 class="font-bold text-base tracking-tight leading-snug group-hover:text-primary transition-colors">
                                        {{ mod.name }}
                                    </h3>
                                    <p class="text-[11px] text-muted-foreground flex items-center gap-1 mt-0.5">
                                        <span>v{{ mod.version }}</span>
                                        <span>•</span>
                                        <span>{{ mod.publisher }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <span 
                                class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-semibold shrink-0"
                                :class="mod.enabled ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-muted text-muted-foreground'"
                            >
                                <span class="h-1.5 w-1.5 rounded-full" :class="mod.enabled ? 'bg-green-500 animate-pulse' : 'bg-muted-foreground'" />
                                {{ mod.enabled ? 'Active' : 'Disabled' }}
                            </span>
                        </div>

                        <!-- Description -->
                        <p class="text-xs text-muted-foreground line-clamp-2 leading-relaxed">
                            {{ mod.description }}
                        </p>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="px-6 py-3 border-t bg-muted/20 flex items-center justify-between gap-2 mt-auto">
                        <span class="text-[11px] font-medium text-primary flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                            Inspect details & changelog
                            <ChevronRight class="h-3.5 w-3.5" />
                        </span>

                        <Button
                            :variant="mod.enabled ? 'outline' : 'default'"
                            size="sm"
                            class="h-7 px-2.5 text-xs z-10"
                            :disabled="isProcessing[mod.id]"
                            @click="quickToggle(mod, $event)"
                        >
                            {{ mod.enabled ? 'Disable' : 'Enable' }}
                        </Button>
                    </div>
                </Card>
            </div>

            <!-- Empty state -->
            <Card v-else class="p-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-muted">
                    <SlidersHorizontal class="h-8 w-8 text-muted-foreground" />
                </div>
                <h3 class="mt-4 text-lg font-semibold">No modules found</h3>
                <p class="mt-1 text-sm text-muted-foreground">
                    Try adjusting your search query or tab filters.
                </p>
                <Button variant="outline" size="sm" class="mt-4" @click="search = ''; activeTab = 'all'">
                    Reset Filters
                </Button>
            </Card>

            <!-- Inspection Modal -->
            <ModuleDetailModal
                :open="isModalOpen"
                :module="selectedModule"
                @update:open="isModalOpen = $event"
            />
        </div>
    </AppLayout>
</template>
