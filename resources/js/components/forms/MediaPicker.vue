<script setup lang="ts">
import { ref, watch } from 'vue'
import { Upload, X, Image as ImageIcon } from 'lucide-vue-next'

const props = defineProps<{
    modelValue?: string | null
    label?: string
    accept?: string
    maxSize?: number // in MB
}>()

const emit = defineEmits<{
    'update:modelValue': [value: string | null]
}>()

const preview = ref<string | null>(props.modelValue || null)
const isDragging = ref(false)
const error = ref<string | null>(null)

watch(() => props.modelValue, (val) => {
    preview.value = val || null
})

function handleFileSelect(event: Event) {
    const input = event.target as HTMLInputElement
    if (input.files?.[0]) {
        processFile(input.files[0])
    }
}

function handleDrop(event: DragEvent) {
    isDragging.value = false
    const file = event.dataTransfer?.files?.[0]
    if (file) {
        processFile(file)
    }
}

function processFile(file: File) {
    error.value = null
    
    // Validate size
    if (props.maxSize && file.size > props.maxSize * 1024 * 1024) {
        error.value = `File too large. Max ${props.maxSize}MB`
        return
    }
    
    // Validate type
    if (props.accept && !file.type.match(props.accept.replace('*', '.*'))) {
        error.value = 'Invalid file type'
        return
    }
    
    // Create preview
    const reader = new FileReader()
    reader.onload = (e) => {
        preview.value = e.target?.result as string
        emit('update:modelValue', preview.value)
    }
    reader.readAsDataURL(file)
}

function removeImage() {
    preview.value = null
    emit('update:modelValue', null)
}
</script>

<template>
    <div class="space-y-2">
        <label v-if="label" class="text-sm font-medium">{{ label }}</label>
        
        <div
            v-if="!preview"
            class="relative border-2 border-dashed border-muted-foreground/25 rounded-lg p-6 transition-colors"
            :class="{ 'border-primary bg-primary/5': isDragging }"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop"
        >
            <input
                type="file"
                :accept="accept || 'image/*'"
                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                @change="handleFileSelect"
            />
            <div class="flex flex-col items-center justify-center text-center">
                <Upload class="h-8 w-8 text-muted-foreground mb-2" />
                <p class="text-sm text-muted-foreground">
                    Drag & drop image here, or click to browse
                </p>
                <p v-if="maxSize" class="text-xs text-muted-foreground mt-1">
                    Max size: {{ maxSize }}MB
                </p>
            </div>
        </div>
        
        <div v-else class="relative rounded-lg overflow-hidden border">
            <img
                :src="preview"
                alt="Preview"
                class="w-full h-40 object-cover"
            />
            <button
                type="button"
                class="absolute top-2 right-2 p-1 rounded-full bg-destructive text-destructive-foreground hover:bg-destructive/90"
                @click="removeImage"
            >
                <X class="h-4 w-4" />
            </button>
        </div>
        
        <p v-if="error" class="text-xs text-destructive">{{ error }}</p>
    </div>
</template>
