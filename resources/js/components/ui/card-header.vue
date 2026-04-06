<script setup lang="ts">
import { cn } from '@/lib/utils'
</script>

<template>
    <div class="flex flex-col space-y-1.5 p-6">
        <slot name="header">
            <h3 class="font-semibold leading-none tracking-tight">
                <slot name="title" />
            </h3>
            <p class="text-sm text-muted-foreground">
                <slot name="description" />
            </p>
        </slot>
    </div>
</template>
