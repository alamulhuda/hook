<script setup lang="ts">
import { computed, type HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

export interface CardProps extends /* @vue-ignore */ HTMLAttributes<HTMLDivElement> {}

const props = defineProps<CardProps>()

const computedClass = computed(() =>
    cn(
        'rounded-lg border bg-card text-card-foreground shadow-sm',
        props.class
    )
)
</script>

<template>
    <div :class="computedClass">
        <slot />
    </div>
</template>
