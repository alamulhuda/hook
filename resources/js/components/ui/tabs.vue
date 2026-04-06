<script setup lang="ts">
import { ref, watch } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const selectedTab = ref('tab-1')

const tabs = ref([
    { id: 'tab-1', label: 'Overview', content: 'Content 1' },
    { id: 'tab-2', label: 'Details', content: 'Content 2' },
    { id: 'tab-3', label: 'Settings', content: 'Content 3' },
])
</script>

<template>
    <div class="space-y-4">
        <div class="flex gap-4 border-b">
            <button
                v-for="tab in tabs"
                :key="tab.id"
                :class="[
                    'px-4 py-2 text-sm font-medium transition-colors hover:text-foreground',
                    selectedTab === tab.id
                        ? 'border-b-2 border-primary text-primary'
                        : 'text-muted-foreground'
                ]"
                @click="selectedTab = tab.id"
            >
                {{ tab.label }}
            </button>
        </div>
        <div v-for="tab in tabs" :key="tab.id" v-show="selectedTab === tab.id">
            <slot :name="tab.id" />
        </div>
    </div>
</template>
