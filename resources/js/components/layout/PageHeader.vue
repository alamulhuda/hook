<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
    title: string
    description?: string
    class?: string
}>()

const headerTargetExists = ref(false)

onMounted(() => {
    headerTargetExists.value = !!document.getElementById('admin-header-title')
})
</script>

<template>
    <div v-if="!headerTargetExists || $slots.actions">
        <!-- Teleport Title & Subtitle to Top Header Bar (#admin-header-title in AdminHeader.vue) -->
        <Teleport v-if="headerTargetExists" to="#admin-header-title">
            <div class="flex flex-col justify-center min-w-0 pr-4 py-1">
                <h1 class="text-base md:text-lg font-bold tracking-tight text-foreground truncate leading-tight">
                    {{ title }}
                </h1>
                <p
                    v-if="description"
                    class="text-[11px] md:text-xs text-muted-foreground truncate hidden sm:block leading-snug mt-0.5"
                >
                    {{ description }}
                </p>
            </div>
        </Teleport>

        <!-- Fallback if #admin-header-title does not exist (e.g. standalone page outside AppLayout) -->
        <div v-else class="mb-4">
            <h1 class="text-2xl font-bold tracking-tight">{{ title }}</h1>
            <p v-if="description" class="mt-1 text-sm text-muted-foreground">
                {{ description }}
            </p>
        </div>

        <!-- Actions Row -->
        <div
            v-if="$slots.actions"
            :class="cn('flex flex-col sm:flex-row sm:items-center justify-end gap-4 pb-1', props.class)"
        >
            <div class="flex items-center gap-2 shrink-0">
                <slot name="actions" />
            </div>
        </div>
    </div>
    <!-- Always teleport the title if target exists, even if we don't render the wrapper div -->
    <Teleport v-else-if="headerTargetExists" to="#admin-header-title">
        <div class="flex flex-col justify-center min-w-0 pr-4 py-1">
            <h1 class="text-base md:text-lg font-bold tracking-tight text-foreground truncate leading-tight">
                {{ title }}
            </h1>
            <p
                v-if="description"
                class="text-[11px] md:text-xs text-muted-foreground truncate hidden sm:block leading-snug mt-0.5"
            >
                {{ description }}
            </p>
        </div>
    </Teleport>
</template>
