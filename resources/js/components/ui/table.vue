<script setup lang="ts">
import { computed } from 'vue'
import { cn } from '@/lib/utils'

const table = cn('w-full caption-bottom text-sm')

defineProps<{ class?: string }>()

const computedClass = computed(() => cn(table))
</script>

<template>
    <div class="relative w-full overflow-auto">
        <table :class="computedClass">
            <slot />
        </table>
    </div>
</template>
