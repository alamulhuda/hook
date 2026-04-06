<script setup lang="ts">
import { computed } from 'vue'
import { cn } from '@/lib/utils'

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
</script>

<template>
    <div :class="cn('space-y-4', props.class)">
        <Breadcrumbs v-if="breadcrumbs" :items="breadcrumbs" class="mb-2" />
        
        <div>
            <h1 class="text-3xl font-bold tracking-tight">{{ title }}</h1>
            <p v-if="description" class="mt-2 text-muted-foreground">
                {{ description }}
            </p>
        </div>
        
        <slot name="actions" />
    </div>
</template>
