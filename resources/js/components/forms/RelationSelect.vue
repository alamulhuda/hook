<script setup lang="ts">
import { ref, computed, watch, type PropType } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import { ChevronDown, X, Loader2 } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import Button from '@/components/ui/button.vue'

interface Option {
    label: string
    value: string | number
    image?: string
}

const props = defineProps<{
    modelValue?: string | number | null
    options: Option[] | Promise<Option[]>
    placeholder?: string
    searchPlaceholder?: string
    emptyMessage?: string
    disabled?: boolean
    loading?: boolean
    searchable?: boolean
    class?: string
}>()

const emit = defineEmits<{
    'update:modelValue': [value: string | number | null]
}>()

const isOpen = ref(false)
const search = ref('')
const resolvedOptions = ref<Option[]>([])
const isLoading = ref(false)

const selectedOption = computed(() => {
    if (!props.modelValue) return null
    return resolvedOptions.value.find(opt => opt.value === props.modelValue)
})

const filteredOptions = computed(() => {
    if (!search.value) return resolvedOptions.value
    return resolvedOptions.value.filter(opt =>
        opt.label.toLowerCase().includes(search.value.toLowerCase())
    )
})

async function loadOptions() {
    if (Array.isArray(props.options)) {
        resolvedOptions.value = props.options
        return
    }
    
    isLoading.value = true
    try {
        resolvedOptions.value = await props.options
    } finally {
        isLoading.value = false
    }
}

const debouncedLoad = useDebounceFn(loadOptions, 300)

watch(() => props.options, () => {
    if (props.searchable) {
        debouncedLoad()
    } else {
        loadOptions()
    }
}, { immediate: true })

watch(search, () => {
    if (props.searchable && typeof props.options === 'function') {
        debouncedLoad()
    }
})

function select(option: Option) {
    emit('update:modelValue', option.value)
    isOpen.value = false
    search.value = ''
}

function clear() {
    emit('update:modelValue', null)
}
</script>

<template>
    <div :class="cn('relative', props.class)">
        <button
            type="button"
            class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
            :class="{ 'border-red-500 focus:ring-red-500': !!error }"
            :disabled="disabled || loading"
            @click="isOpen = !isOpen"
        >
            <span v-if="selectedOption" class="flex items-center gap-2">
                <img
                    v-if="selectedOption.image"
                    :src="selectedOption.image"
                    class="h-5 w-5 rounded-full object-cover"
                />
                {{ selectedOption.label }}
            </span>
            <span v-else class="text-muted-foreground">
                {{ placeholder || 'Select...' }}
            </span>
            <ChevronDown v-if="!loading" class="h-4 w-4 opacity-50" />
            <Loader2 v-else class="h-4 w-4 animate-spin" />
        </button>

        <Teleport to="body">
            <div
                v-if="isOpen"
                class="fixed inset-0 z-50"
                @click="isOpen = false"
            />
            <div
                v-if="isOpen"
                :class="cn(
                    'absolute z-50 mt-1 w-full rounded-md border bg-popover p-0 text-popover-foreground shadow-lg outline-none animate-in fade-in-0 zoom-in-95',
                    props.class
                )"
            >
                <div v-if="searchable" class="flex items-center border-b px-3">
                    <input
                        v-model="search"
                        type="text"
                        :placeholder="searchPlaceholder || 'Search...'"
                        class="flex h-10 w-full rounded-md bg-transparent py-3 text-sm outline-none placeholder:text-muted-foreground"
                        @click.stop
                    />
                </div>
                <div class="max-h-60 overflow-auto p-1">
                    <div v-if="isLoading" class="flex items-center justify-center py-6">
                        <Loader2 class="h-6 w-6 animate-spin" />
                    </div>
                    <div v-else-if="filteredOptions.length === 0" class="py-6 text-center text-sm text-muted-foreground">
                        {{ emptyMessage || 'No options found' }}
                    </div>
                    <button
                        v-for="option in filteredOptions"
                        :key="option.value"
                        type="button"
                        class="relative flex w-full cursor-default select-none items-center rounded-sm py-1.5 pl-8 pr-2 text-sm outline-none hover:bg-accent hover:text-accent-foreground"
                        :class="{ 'bg-accent': option.value === modelValue }"
                        @click="select(option)"
                    >
                        <span v-if="option.image" class="absolute left-2 flex h-5 w-5">
                            <img :src="option.image" class="rounded-full object-cover" />
                        </span>
                        {{ option.label }}
                    </button>
                </div>
            </div>
        </Teleport>
    </div>
</template>
