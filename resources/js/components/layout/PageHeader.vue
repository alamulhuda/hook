<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { cn } from '@/lib/utils'
import Breadcrumbs from './Breadcrumbs.vue'

interface BreadcrumbItem {
    label: string
    href?: string
}

const props = defineProps<{
    title: string
    description?: string
    breadcrumbs?: BreadcrumbItem[]
    class?: string
}>()

const headerTargetExists = ref(false)

onMounted(() => {
    headerTargetExists.value = !!document.getElementById('admin-header-title')
})
</script>

<template>
    <div>
        <!-- Teleport Title & Subtitle to Top Header Bar (#admin-header-title in AdminHeader.vue) -->
        <Teleport v-if="headerTargetExists" to="#admin-header-title">
            <div class="flex flex-col justify-center min-w-0 pr-4 py-1">
                <h1 class="text-base md:text-lg font-bold tracking-tight text-foreground truncate leading-tight">
                    {{ title }}
                </h1>
                <p
                    v-if="description"
                    class="text-[11px] md:text-xs text-muted-foreground truncate hidden sm:block leading-snug mt-0.5"
                >
                    {{ description }}
                </p>
            </div>
        </Teleport>

        <!-- Fallback if #admin-header-title does not exist (e.g. standalone page outside AppLayout) -->
        <div v-else class="mb-4">
            <h1 class="text-2xl font-bold tracking-tight">{{ title }}</h1>
            <p v-if="description" class="mt-1 text-sm text-muted-foreground">
                {{ description }}
            </p>
        </div>

        <!-- Breadcrumbs & Actions Row -->
        <div
            v-if="breadcrumbs || $slots.actions"
            :class="cn('flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-1', props.class)"
        >
            <div>
                <Breadcrumbs v-if="breadcrumbs" :items="breadcrumbs" />
            </div>
            <div v-if="$slots.actions" class="flex items-center gap-2 shrink-0">
                <slot name="actions" />
            </div>
        </div>
    </div>
</template>
