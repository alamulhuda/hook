<script setup lang="ts">
import { ref, inject } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Menu, Bell, Search, Sun, Moon } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import Button from '@/components/ui/button.vue'
import Input from '@/components/ui/input.vue'

const props = defineProps<{
    class?: string
}>()

// Get mobile menu toggle from AdminSidebar
const toggleMobileMenu = inject<() => void>('toggleMobileMenu')

const isDark = ref(false)
const showSearch = ref(false)

function toggleDark() {
    isDark.value = !isDark.value
    document.documentElement.classList.toggle('dark', isDark.value)
    localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
}
</script>

<template>
    <header
        :class="cn(
            'sticky top-0 z-30 flex h-16 items-center gap-4 border-b bg-background px-4 md:px-6',
            props.class
        )"
    >
        <Button variant="ghost" size="icon" class="lg:hidden" @click="toggleMobileMenu">
            <Menu class="h-5 w-5" />
        </Button>
        
        <div class="flex-1" />
        
        <div class="flex items-center gap-2">
            <Button
                variant="ghost"
                size="icon"
                @click="showSearch = !showSearch"
            >
                <Search class="h-5 w-5" />
            </Button>
            
            <Button
                variant="ghost"
                size="icon"
                @click="toggleDark"
            >
                <Sun v-if="isDark" class="h-5 w-5 rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0" />
                <Moon v-else class="h-5 w-5 rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100" />
            </Button>
            
            <Button variant="ghost" size="icon" class="relative">
                <Bell class="h-5 w-5" />
                <span class="absolute right-1 top-1 h-2 w-2 rounded-full bg-red-500" />
            </Button>
            
            <slot name="user" />
        </div>
    </header>
</template>
