<script setup lang="ts">
import { ref, computed } from 'vue'
import { GripVertical, Plus, Trash2 } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import Button from '@/components/ui/button.vue'

interface RepeaterItem {
    id: string
    [key: string]: any
}

const props = defineProps<{
    modelValue: RepeaterItem[]
    minItems?: number
    maxItems?: number
    class?: string
}>()

const emit = defineEmits<{
    'update:modelValue': [value: RepeaterItem[]]
}>()

const items = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val)
})

function addItem() {
    if (props.maxItems && items.value.length >= props.maxItems) return
    const newId = Math.random().toString(36).slice(2, 9)
    items.value = [...items.value, { id: newId }]
}

function removeItem(index: number) {
    if (items.value.length <= (props.minItems || 0)) return
    items.value = items.value.filter((_, i) => i !== index)
}

function moveItem(from: number, to: number) {
    if (to < 0 || to >= items.value.length) return
    const newItems = [...items.value]
    const [moved] = newItems.splice(from, 1)
    newItems.splice(to, 0, moved)
    items.value = newItems
}
</script>

<template>
    <div :class="cn('space-y-4', props.class)">
        <div class="space-y-2">
            <div
                v-for="(item, index) in items"
                :key="item.id"
                class="flex items-start gap-2 rounded-lg border bg-card p-4"
            >
                <div class="flex items-center gap-1 pt-6">
                    <Button
                        variant="ghost"
                        size="icon-sm"
                        :disabled="index === 0"
                        @click="moveItem(index, index - 1)"
                    >
                        <GripVertical class="h-4 w-4" />
                    </Button>
                </div>
                
                <div class="flex-1 space-y-3">
                    <slot :item="item" :index="index" />
                </div>
                
                <div class="flex items-center gap-1 pt-6">
                    <Button
                        variant="ghost"
                        size="icon-sm"
                        class="text-red-500 hover:text-red-600 hover:bg-red-50"
                        :disabled="items.length <= (minItems || 0)"
                        @click="removeItem(index)"
                    >
                        <Trash2 class="h-4 w-4" />
                    </Button>
                </div>
            </div>
        </div>
        
        <Button
            v-if="!maxItems || items.length < maxItems"
            type="button"
            variant="outline"
            size="sm"
            @click="addItem"
        >
            <Plus class="h-4 w-4 mr-2" />
            Add Item
        </Button>
    </div>
</template>
