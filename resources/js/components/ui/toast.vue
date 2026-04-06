<script setup lang="ts">
import { computed } from 'vue'
import { cva } from 'class-variance-authority'
import { cn } from '@/lib/utils'

const toastVariants = cva(
    'group pointer-events-auto relative flex w-full items-center justify-between space-x-4 overflow-hidden rounded-md border p-6 pr-8 shadow-lg transition-all data-[swipe=cancel]:translate-x-0 data-[swipe=end]:translate-x-[var(--radix-toast-swipe-end-x)] data-[swipe=move]:translate-x-[var(--radix-toast-swipe-move-x)] data-[swipe=move]:transition-none data-[state=open]:animate-in data-[state=closed]:animate-out data-[swipe=end]:animate-out data-[state=closed]:fade-out-80 data-[state=closed]:slide-out-to-right-full data-[state=open]:slide-in-from-bottom-full',
    {
        variants: {
            variant: {
                default: 'border bg-background text-foreground',
                destructive: 'destructive group border-red-500 bg-red-500 text-white',
                success: 'border-green-500 bg-green-500 text-white',
            },
        },
        defaultVariants: {
            variant: 'default',
        },
    }
)

const props = defineProps<{
    variant?: 'default' | 'destructive' | 'success'
    class?: string
}>()
</script>

<template>
    <div :class="cn(toastVariants({ variant: props.variant }), props.class)">
        <div class="flex-1">
            <slot />
        </div>
        <slot name="action" />
    </div>
</template>
