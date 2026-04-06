<script setup lang="ts">
import { ref, inject, onMounted, onUnmounted, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Menu, Bell, Search, Sun, Moon, X, FileText, ShoppingCart, Package, Users } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import Button from '@/components/ui/button.vue'
import Input from '@/components/ui/input.vue'
import Badge from '@/components/ui/badge.vue'

const props = defineProps<{
    class?: string
}>()

// Get mobile menu toggle from AdminSidebar
const toggleMobileMenu = inject<() => void>('toggleMobileMenu')

const isDark = ref(false)
const showSearch = ref(false)
const showNotifications = ref(false)
const searchQuery = ref('')

// Mock notifications - replace with real data from backend
const notifications = ref([
    { id: 1, title: 'New Sale', message: 'Penjualan #1234 completed', time: '5 min ago', icon: ShoppingCart, read: false },
    { id: 2, title: 'Low Stock', message: 'iPhone 14 Pro below minimum', time: '1 hour ago', icon: Package, read: false },
    { id: 3, title: 'New User', message: 'Customer registered', time: '2 hours ago', icon: Users, read: true },
    { id: 4, title: 'Stock Opname', message: 'Monthly stock opname scheduled', time: '3 hours ago', icon: FileText, read: true },
    { id: 5, title: 'Payment Received', message: 'Payment for Invoice #5678', time: '5 hours ago', icon: ShoppingCart, read: true },
])

const unreadCount = notifications.value.filter(n => !n.read).length
const showAll = ref(false)
const displayNotifications = computed(() => {
    return showAll.value ? notifications.value : notifications.value.slice(0, 3)
})

function toggleDark() {
    isDark.value = !isDark.value
    document.documentElement.classList.toggle('dark', isDark.value)
    localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
}

function handleSearch() {
    if (searchQuery.value.trim()) {
        // Navigate to search results or filter current page
        console.log('Searching:', searchQuery.value)
        showSearch.value = false
        searchQuery.value = ''
    }
}

function markAllRead() {
    notifications.value.forEach(n => n.read = true)
}

function closeDropdowns(e: KeyboardEvent) {
    if (e.key === 'Escape') {
        showSearch.value = false
        showNotifications.value = false
    }
}

function handleClickOutside(event: MouseEvent) {
    const target = event.target as HTMLElement
    // Close dropdowns if clicking outside
    if (!target.closest('.search-dropdown')) {
        showSearch.value = false
    }
    if (!target.closest('.notifications-dropdown')) {
        showNotifications.value = false
    }
}

onMounted(() => {
    document.addEventListener('keydown', closeDropdowns)
    document.addEventListener('click', handleClickOutside)
    
    // Load theme from localStorage
    const savedTheme = localStorage.getItem('theme')
    if (savedTheme === 'dark') {
        isDark.value = true
        document.documentElement.classList.add('dark')
    }
})

onUnmounted(() => {
    document.removeEventListener('keydown', closeDropdowns)
    document.removeEventListener('click', handleClickOutside)
})
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
        
        <div class="flex items-center gap-1">
            <!-- Search Button + Dropdown -->
            <div class="relative">
                <Button
                    variant="ghost"
                    size="icon"
                    @click.stop="showSearch = !showSearch"
                >
                    <Search class="h-5 w-5" />
                </Button>
                
                <!-- Search Dropdown -->
                <div
                    v-if="showSearch"
                    class="search-dropdown absolute right-0 top-full mt-2 w-80 rounded-lg border bg-background shadow-lg p-4"
                >
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium">Search</span>
                        <Button variant="ghost" size="icon" class="h-6 w-6" @click="showSearch = false">
                            <X class="h-4 w-4" />
                        </Button>
                    </div>
                    <form @submit.prevent="handleSearch" class="flex gap-2">
                        <Input
                            v-model="searchQuery"
                            placeholder="Search products, transactions..."
                            class="flex-1"
                        />
                        <Button type="submit" size="sm">Go</Button>
                    </form>
                </div>
            </div>
            
            <!-- Dark Mode Toggle -->
            <Button
                variant="ghost"
                size="icon"
                @click="toggleDark"
            >
                <Sun v-if="isDark" class="h-5 w-5 rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0" />
                <Moon v-else class="h-5 w-5 rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100" />
            </Button>
            
            <!-- Notifications Button + Dropdown -->
            <div class="relative">
                <Button
                    variant="ghost"
                    size="icon"
                    class="relative"
                    @click.stop="showNotifications = !showNotifications"
                >
                    <Bell class="h-5 w-5" />
                    <span
                        v-if="unreadCount > 0"
                        class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-destructive text-[10px] font-medium text-destructive-foreground"
                    >
                        {{ unreadCount }}
                    </span>
                </Button>
                
                <!-- Notifications Dropdown -->
                <div
                    v-if="showNotifications"
                    class="notifications-dropdown absolute right-0 top-full mt-2 w-80 rounded-lg border bg-background shadow-lg"
                >
                    <div class="flex items-center justify-between p-4 border-b">
                        <span class="text-sm font-medium">Notifications</span>
                        <Button variant="ghost" size="sm" @click="markAllRead">
                            Mark all read
                        </Button>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        <div
                            v-for="notification in displayNotifications"
                            :key="notification.id"
                            class="flex items-start gap-3 p-4 border-b last:border-0 hover:bg-muted/50 transition-colors cursor-pointer"
                            :class="{ 'bg-primary/5': !notification.read }"
                        >
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-muted">
                                <component :is="notification.icon" class="h-4 w-4" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium">{{ notification.title }}</p>
                                <p class="text-xs text-muted-foreground truncate">{{ notification.message }}</p>
                            </div>
                            <span class="text-xs text-muted-foreground whitespace-nowrap">
                                {{ notification.time }}
                            </span>
                        </div>
                        <div v-if="displayNotifications.length === 0" class="p-4 text-center text-sm text-muted-foreground">
                            No notifications
                        </div>
                    </div>
                    <div v-if="notifications.length > 3" class="p-2 border-t">
                        <button
                            class="block w-full text-center text-sm text-primary hover:underline"
                            @click="showAll = !showAll"
                        >
                            {{ showAll ? 'Show less' : `View all (${notifications.length})` }}
                        </button>
                    </div>
                </div>
            </div>
            
            <slot name="user" />
        </div>
    </header>
</template>
