<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'

interface BreadcrumbItem {
    label: string
    href?: string
}

const props = defineProps<{
    items?: BreadcrumbItem[]
    class?: string
}>()
</script>

<template>
    <nav :class="cn('flex', props.class)" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li class="inline-flex items-center">
                <Link
                    href="/app"
                    class="inline-flex items-center text-sm font-medium text-muted-foreground hover:text-foreground"
                >
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </Link>
            </li>
            <li v-for="(item, index) in items" :key="index" class="inline-flex items-center">
                <svg class="h-4 w-4 text-muted-foreground mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <Link
                    v-if="item.href && index !== items.length - 1"
                    :href="item.href"
                    class="ml-1 text-sm font-medium text-muted-foreground hover:text-foreground md:ml-2"
                >
                    {{ item.label }}
                </Link>
                <span
                    v-else
                    class="ml-1 text-sm font-medium text-foreground md:ml-2"
                >
                    {{ item.label }}
                </span>
            </li>
        </ol>
    </nav>
</template>
