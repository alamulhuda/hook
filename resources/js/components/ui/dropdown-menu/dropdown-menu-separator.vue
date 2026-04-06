<script setup lang="ts">
import { computed } from 'vue'
import { cn } from '@/lib/utils'

const separator = defineProps<{
    class?: string
}>()

const computedClass = computed(() =>
    cn('-mx-1 my-1 h-px bg-border', separator.class)
)
</script>

<template>
    <div :class="computedClass" role="separator" />
</template>
