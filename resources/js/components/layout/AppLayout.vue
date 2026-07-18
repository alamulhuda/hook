<script setup lang="ts">
import { ref, provide } from 'vue'
import { cn } from '@/lib/utils'
import AdminSidebar from './AdminSidebar.vue'
import AdminHeader from './AdminHeader.vue'

const props = defineProps<{ class?: string }>()

const hasSubNav = ref(false)
provide('setHasSubNav', (val: boolean) => {
    hasSubNav.value = val
})
</script>

<template>
    <div
        :class="cn(
            'flex min-h-screen w-full flex-col bg-muted/40',
            props.class
        )"
    >
        <AdminSidebar />
        <div
            :class="cn(
                'flex flex-col flex-1 transition-all duration-200',
                hasSubNav ? 'lg:pl-[276px]' : 'lg:pl-[68px]'
            )"
        >
            <AdminHeader />
            <main class="flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>
