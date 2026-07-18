<script setup lang="ts">
import { ref, computed, watch, nextTick } from 'vue'
import { cn } from '@/lib/utils'
import { formatCurrency, formatNumber } from '@/lib/utils'

const props = defineProps<{
    modelValue: number | string | null
    placeholder?: string
    autofillValue?: number | string | null
    disabled?: boolean
    class?: string
    currency?: string
    hideCurrency?: boolean
    decimalScale?: number
}>()

const emit = defineEmits<{
    'update:modelValue': [value: number | null]
}>()

const inputValue = ref(props.modelValue !== null && props.modelValue !== undefined && props.modelValue !== '' ? String(props.modelValue) : '')
const isFocused = ref(false)
const actualTypedValue = ref('')

watch(() => props.modelValue, (val) => {
    if (!isFocused.value) {
        inputValue.value = val !== null && val !== undefined && val !== '' ? String(val) : ''
    }
})

const displayValue = computed(() => {
    if (isFocused.value) {
        return inputValue.value
    }
    if (!inputValue.value) return ''
    const num = parseFloat(String(inputValue.value).replace(/[^\d.-]/g, ''))
    if (isNaN(num)) return ''
    return props.hideCurrency 
        ? formatNumber(num)
        : formatCurrency(num, props.currency || 'IDR')
})

function handleInput(e: Event) {
    const target = e.target as HTMLInputElement
    const inputEvent = e as InputEvent
    
    // Hanya ambil angka
    let rawValue = target.value.replace(/[^\d]/g, '')
    let valueToEmit: number | null = rawValue ? parseFloat(rawValue) : null
    actualTypedValue.value = rawValue
    
    // Auto-fill logic similar to highlighted text in browser URL bar
    if (
        inputEvent.inputType?.startsWith('insert') && 
        props.autofillValue !== undefined && 
        props.autofillValue !== null
    ) {
        const autofillStr = String(props.autofillValue)
        if (rawValue && autofillStr.startsWith(rawValue) && rawValue !== autofillStr) {
            const typedLength = rawValue.length
            inputValue.value = autofillStr
            
            nextTick(() => {
                target.setSelectionRange(typedLength, autofillStr.length)
            })
            
            // Keep parent value as only what user actually typed
            emit('update:modelValue', valueToEmit)
            return
        }
    }

    inputValue.value = rawValue
    emit('update:modelValue', valueToEmit)
}

function handleKeydown(e: KeyboardEvent) {
    const hasSuggestion = 
        props.autofillValue !== undefined && 
        props.autofillValue !== null && 
        inputValue.value === String(props.autofillValue) &&
        actualTypedValue.value !== String(props.autofillValue);

    if ((e.key === 'ArrowRight' || e.key === 'Tab') && props.autofillValue !== undefined && props.autofillValue !== null) {
        if (!inputValue.value) {
            if (e.key === 'ArrowRight') e.preventDefault()
            const rawValue = String(props.autofillValue)
            actualTypedValue.value = rawValue
            inputValue.value = rawValue
            emit('update:modelValue', parseFloat(rawValue))
        } else if (hasSuggestion) {
            if (e.key === 'ArrowRight') e.preventDefault()
            const rawValue = String(props.autofillValue)
            actualTypedValue.value = rawValue
            inputValue.value = rawValue
            emit('update:modelValue', parseFloat(rawValue))
        }
    }
}

function handleBlur() {
    isFocused.value = false
    // Revert visual value to actual typed value if suggestion was not accepted
    inputValue.value = actualTypedValue.value
}

function handleFocus() {
    isFocused.value = true
    actualTypedValue.value = inputValue.value
}

const computedPlaceholder = computed(() => {
    if (!inputValue.value && props.autofillValue !== undefined && props.autofillValue !== null) {
        const num = parseFloat(String(props.autofillValue).replace(/[^\d.-]/g, ''))
        if (!isNaN(num)) {
            return props.hideCurrency 
                ? formatNumber(num)
                : formatCurrency(num, props.currency || 'IDR')
        }
    }
    return props.placeholder
})
</script>

<template>
    <div :class="cn('relative', props.class)">
        <input
            :value="displayValue"
            :placeholder="computedPlaceholder"
            :disabled="disabled"
            type="text"
            autocomplete="off"
            data-lpignore="true"
            data-1p-ignore
            :class="cn('flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm font-mono ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50', 
                       { 'pr-16': !hideCurrency })"
            @input="handleInput"
            @keydown="handleKeydown"
            @focus="handleFocus"
            @blur="handleBlur"
        />
        <span v-if="!hideCurrency" class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-muted-foreground">
            {{ currency || 'IDR' }}
        </span>
    </div>
</template>
