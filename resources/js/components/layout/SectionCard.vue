<script setup lang="ts">
import { computed } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
    variant?: 'card' | 'flat' | 'bordered'
    class?: string
}>()

const computedClass = computed(() => {
    switch (props.variant) {
        case 'flat':
            return 'bg-muted/30'
        case 'bordered':
            return 'border bg-background'
        default:
            return 'border bg-card shadow-sm rounded-lg'
    }
})
</script>

<template>
    <div :class="cn('p-6', computedClass, props.class)">
        <slot />
    </div>
</template>
