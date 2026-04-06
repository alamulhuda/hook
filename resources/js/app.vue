<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Toaster } from '@/components/ui/toast/toaster'
import { useToast } from '@/composables/useToast'

const page = usePage()
const isDark = ref(false)

onMounted(() => {
    isDark.value = document.documentElement.classList.contains('dark')
})

function toggleDark() {
    isDark.value = !isDark.value
    document.documentElement.classList.toggle('dark', isDark.value)
    localStorage.setItem('theme', isDark.value ? 'dark' : 'light')
}
</script>

<template>
    <div class="min-h-screen bg-background text-foreground">
        <Toaster />
        <slot />
    </div>
</template>
