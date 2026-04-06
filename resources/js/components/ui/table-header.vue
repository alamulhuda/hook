<script setup lang="ts">
import { cn } from '@/lib/utils'
</script>

<template>
    <thead :class="cn('border-b', $attrs.class)">
        <slot />
    </thead>
</template>
