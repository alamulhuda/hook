<script setup lang="ts">
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import {
    Package,
    CheckCircle2,
    XCircle,
    Download,
    Trash2,
    Clock,
    Shield,
    FileText,
    AlertTriangle,
    Sparkles,
} from 'lucide-vue-next'
import Dialog from '@/components/ui/dialog.vue'
import Button from '@/components/ui/button.vue'
import Badge from '@/components/ui/badge.vue'

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
    open: boolean
    module: ModuleItem | null
}>()

const emit = defineEmits<{
    'update:open': [value: boolean]
}>()

const showDeleteConfirm = ref(false)
const isProcessing = ref(false)

watch(() => props.open, (val) => {
    if (!val) {
        showDeleteConfirm.value = false
        isProcessing.value = false
    }
})

function close() {
    emit('update:open', false)
}

function toggleModule() {
    if (!props.module) return
    isProcessing.value = true
    router.post(`/settings/modules/${props.module.id}/toggle`, {}, {
        onSuccess: () => {
            isProcessing.value = false
        },
        onFinish: () => {
            isProcessing.value = false
        }
    })
}

function updateModule() {
    if (!props.module) return
    isProcessing.value = true
    router.post(`/settings/modules/${props.module.id}/update`, {}, {
        onSuccess: () => {
            isProcessing.value = false
        },
        onFinish: () => {
            isProcessing.value = false
        }
    })
}

function deleteModule() {
    if (!props.module) return
    isProcessing.value = true
    router.delete(`/settings/modules/${props.module.id}`, {
        onSuccess: () => {
            isProcessing.value = false
            showDeleteConfirm.value = false
            close()
        },
        onFinish: () => {
            isProcessing.value = false
        }
    })
}
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)" class="max-w-2xl p-0 overflow-hidden">
        <div v-if="module" class="flex flex-col max-h-[85vh]">
            <!-- Header -->
            <div class="p-6 border-b bg-gradient-to-r from-primary/10 via-background to-background relative">
                <div class="flex items-start gap-4 pr-8">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary text-primary-foreground shadow-sm">
                        <Package class="h-6 w-6" />
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-xl font-bold tracking-tight">{{ module.name }}</h2>
                            <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary">
                                v{{ module.version }}
                            </span>
                        </div>
                        <p class="text-xs text-muted-foreground flex items-center gap-1">
                            <Shield class="h-3 w-3 text-primary" />
                            Publisher: <span class="font-medium text-foreground">{{ module.publisher }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Body Content -->
            <div class="p-6 overflow-y-auto space-y-6 flex-1">
                <!-- Status & Quick Actions -->
                <div class="flex items-center justify-between p-4 rounded-xl border bg-card shadow-sm">
                    <div class="flex items-center gap-3">
                        <div 
                            class="flex h-10 w-10 items-center justify-center rounded-full"
                            :class="module.enabled ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'"
                        >
                            <CheckCircle2 v-if="module.enabled" class="h-5 w-5" />
                            <XCircle v-else class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm font-semibold">
                                {{ module.enabled ? 'Module is Active & Loaded' : 'Module is Currently Disabled' }}
                            </p>
                            <p class="text-xs text-muted-foreground">
                                {{ module.enabled ? 'All routes, resources, and widgets are operational.' : 'Routes and dashboard widgets are skipped.' }}
                            </p>
                        </div>
                    </div>
                    <Button 
                        :variant="module.enabled ? 'outline' : 'default'" 
                        size="sm"
                        :disabled="isProcessing"
                        @click="toggleModule"
                        class="gap-1.5"
                    >
                        <XCircle v-if="module.enabled" class="h-4 w-4 text-red-500" />
                        <CheckCircle2 v-else class="h-4 w-4 text-green-500" />
                        {{ module.enabled ? 'Disable Module' : 'Enable Module' }}
                    </Button>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <h3 class="text-sm font-semibold flex items-center gap-1.5 text-foreground">
                        <FileText class="h-4 w-4 text-primary" />
                        About This Module
                    </h3>
                    <p class="text-sm text-muted-foreground leading-relaxed pl-5.5 border-l-2 border-primary/20">
                        {{ module.description }}
                    </p>
                </div>

                <!-- Changelog Section -->
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold flex items-center gap-1.5 text-foreground">
                        <Clock class="h-4 w-4 text-primary" />
                        Changelog & Version History
                    </h3>

                    <div v-if="module.changelog && module.changelog.length > 0" class="space-y-4 pl-2">
                        <div 
                            v-for="(item, idx) in module.changelog" 
                            :key="item.version"
                            class="relative pl-6 pb-4 border-l last:border-l-0 last:pb-0"
                        >
                            <!-- Timeline dot -->
                            <div class="absolute -left-1.5 top-0.5 h-3 w-3 rounded-full bg-primary ring-4 ring-background" />
                            
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-foreground">Version {{ item.version }}</span>
                                <span class="text-xs text-muted-foreground">{{ item.date }}</span>
                            </div>
                            <ul class="space-y-1 mt-1.5">
                                <li 
                                    v-for="(change, cIdx) in item.changes" 
                                    :key="cIdx"
                                    class="text-xs text-muted-foreground flex items-start gap-2"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-muted-foreground/60 mt-1.5 shrink-0" />
                                    <span>{{ change }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div v-else class="text-xs text-muted-foreground italic pl-2">
                        No changelog entries available for this module yet.
                    </div>
                </div>

                <!-- Delete Confirmation Box -->
                <div v-if="showDeleteConfirm" class="p-4 rounded-xl border border-red-500/30 bg-red-500/5 space-y-3">
                    <div class="flex items-start gap-3">
                        <AlertTriangle class="h-5 w-5 text-red-500 shrink-0 mt-0.5" />
                        <div class="space-y-1">
                            <p class="text-sm font-semibold text-red-700 dark:text-red-400">Confirm Module Deletion</p>
                            <p class="text-xs text-muted-foreground">
                                Are you sure you want to permanently delete <strong>{{ module.name }}</strong>? This action will remove all files inside <code>app/Modules/{{ module.directory }}</code> and cannot be undone.
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-1">
                        <Button variant="outline" size="sm" @click="showDeleteConfirm = false">Cancel</Button>
                        <Button variant="destructive" size="sm" :disabled="isProcessing" @click="deleteModule">
                            Confirm Delete
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="p-4 border-t bg-muted/40 flex items-center justify-between">
                <Button 
                    variant="ghost" 
                    size="sm" 
                    class="text-red-600 hover:text-red-700 hover:bg-red-500/10 gap-1.5"
                    @click="showDeleteConfirm = true"
                    :disabled="isProcessing || showDeleteConfirm"
                >
                    <Trash2 class="h-4 w-4" />
                    Delete Module
                </Button>
                
                <div class="flex items-center gap-2">
                    <Button 
                        variant="outline" 
                        size="sm" 
                        class="gap-1.5"
                        @click="updateModule"
                        :disabled="isProcessing"
                    >
                        <Sparkles class="h-4 w-4 text-amber-500" />
                        Check for Updates
                    </Button>
                    <Button variant="secondary" size="sm" @click="close">
                        Close
                    </Button>
                </div>
            </div>
        </div>
    </Dialog>
</template>
