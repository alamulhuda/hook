<script setup lang="ts">
import { computed } from 'vue'
import { cn } from '@/lib/utils'
import Label from '@/components/ui/label.vue'
import FormDescription from '@/components/ui/form-description.vue'

const props = defineProps<{
    label?: string
    name: string
    error?: string
    required?: boolean
    class?: string
}>()
</script>

<template>
    <div :class="cn('space-y-2', props.class)">
        <Label v-if="label" :for="name" :required="required">
            {{ label }}
        </Label>
        <slot />
        <p v-if="error" class="text-sm text-red-500">{{ error }}</p>
        <FormDescription v-if="$slots.description">
            <slot name="description" />
        </FormDescription>
    </div>
</template>
