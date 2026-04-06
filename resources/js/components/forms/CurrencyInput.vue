<script setup lang="ts">
import { ref, computed, watch, type PropType } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import { cn } from '@/lib/utils'
import Input from '@/components/ui/input.vue'
import Button from '@/components/ui/button.vue'
import { formatCurrency } from '@/lib/utils'

const props = defineProps<{
    modelValue: number | string | null
    placeholder?: string
    disabled?: boolean
    class?: string
    currency?: string
    decimalScale?: number
}>()

const emit = defineEmits<{
    'update:modelValue': [value: number | null]
}>()

const inputValue = ref(props.modelValue ? String(props.modelValue) : '')
const isFocused = ref(false)

watch(() => props.modelValue, (val) => {
    if (!isFocused.value) {
        inputValue.value = val ? String(val) : ''
    }
})

const formattedValue = computed(() => {
    if (!inputValue.value) return ''
    const num = parseFloat(inputValue.value.replace(/[^\d.-]/g, ''))
    if (isNaN(num)) return ''
    return formatCurrency(num, props.currency || 'IDR')
})

function handleInput(e: Event) {
    const target = e.target as HTMLInputElement
    inputValue.value = target.value
    emit('update:modelValue', parseFloat(inputValue.value) || null)
}

function handleBlur() {
    isFocused.value = false
    if (inputValue.value) {
        const num = parseFloat(inputValue.value.replace(/[^\d.-]/g, ''))
        if (!isNaN(num)) {
            inputValue.value = String(num)
        }
    }
}

function handleFocus() {
    isFocused.value = true
}
</script>

<template>
    <div :class="cn('relative', props.class)">
        <Input
            :value="isFocused ? inputValue : formattedValue"
            :placeholder="placeholder"
            :disabled="disabled"
            class="pr-16 font-mono"
            @input="handleInput"
            @focus="handleFocus"
            @blur="handleBlur"
        />
        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-muted-foreground">
            {{ currency || 'IDR' }}
        </span>
    </div>
</template>
