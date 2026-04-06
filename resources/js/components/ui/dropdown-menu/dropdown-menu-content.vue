<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, type HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
    open?: boolean
    class?: string
}>()

const emit = defineEmits<{
    'update:open': [value: boolean]
}>()

const isOpen = ref(props.open ?? false)

function close() {
    isOpen.value = false
    emit('update:open', false)
}
</script>

<template>
    <Teleport to="body">
        <div v-if="isOpen" class="relative z-50">
            <div class="fixed inset-0" @click="close" />
            <div :class="cn('fixed z-50 min-w-[8rem] overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-lg', props.class)">
                <slot />
            </div>
        </div>
    </Teleport>
</template>
