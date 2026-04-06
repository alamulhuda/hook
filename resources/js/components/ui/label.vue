<script setup lang="ts">
import { computed, type HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

export interface LabelProps extends /* @vue-ignore */ HTMLAttributes<HTMLLabelElement> {
    required?: boolean
}

const props = defineProps<LabelProps>()

const computedClass = computed(() => {
    return cn(
        'text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70',
        props.required && 'after:text-red-500 after:content-["*"] after:ml-0.5',
        props.class
    )
})
</script>

<template>
    <label :class="computedClass">
        <slot />
    </label>
</template>
