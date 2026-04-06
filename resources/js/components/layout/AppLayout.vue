<script setup lang="ts">
import { cn } from '@/lib/utils'
import AdminSidebar from './AdminSidebar.vue'
import AdminHeader from './AdminHeader.vue'

const props = defineProps<{ class?: string }>()
</script>

<template>
    <div
        :class="cn(
            'flex min-h-screen w-full flex-col bg-muted/40',
            props.class
        )"
    >
        <AdminSidebar />
        <div class="flex flex-col flex-1 lg:pl-[19rem]">
            <AdminHeader />
            <main class="flex-1 p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
