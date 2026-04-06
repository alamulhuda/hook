<script setup lang="ts">
import { cn } from '@/lib/utils'

const props = defineProps<{ class?: string }>()
</script>

<template>
    <div class="p-6 pt-0">
        <slot />
    </div>
</template>
