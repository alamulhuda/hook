<script setup lang="ts">
import { cn } from '@/lib/utils'

const props = defineProps<{ class?: string }>()
</script>

<template>
    <div
        :class="cn(
            'flex min-h-screen w-full flex-col bg-muted/40',
            props.class
        )"
    >
        <slot />
    </div>
</template>
