<script setup lang="ts">
import { computed } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{ class?: string }>()
</script>

<template>
    <div :class="cn('flex flex-col space-y-1 p-2', props.class)" role="menu">
        <slot />
    </div>
</template>
