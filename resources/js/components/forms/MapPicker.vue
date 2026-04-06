<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { MapPin, X } from 'lucide-vue-next'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps<{
    modelValue?: { lat: number; lng: number } | null
    label?: string
    placeholder?: string
    height?: string
}>()

const emit = defineEmits<{
    'update:modelValue': [value: { lat: number; lng: number } | null]
}>()

const mapContainer = ref<HTMLDivElement | null>(null)
const map = ref<L.Map | null>(null)
const marker = ref<L.Marker | null>(null)
const searchQuery = ref('')
const searchResults = ref<any[]>([])
const showSearch = ref(false)

// Default center (Jakarta, Indonesia)
const defaultCenter: [number, number] = [-6.2088, 106.8456]

onMounted(() => {
    if (mapContainer.value) {
        map.value = L.map(mapContainer.value).setView(defaultCenter, 13)
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map.value)
        
        // If there's an initial value, add marker
        if (props.modelValue) {
            const { lat, lng } = props.modelValue
            setMarker(lat, lng)
            map.value.setView([lat, lng], 15)
        }
        
        // Click handler
        map.value.on('click', (e: L.LeafletMouseEvent) => {
            const { lat, lng } = e.latlng
            setMarker(lat, lng)
            emit('update:modelValue', { lat, lng })
        })
    }
})

function setMarker(lat: number, lng: number) {
    if (!map.value) return
    
    // Remove existing marker
    if (marker.value) {
        map.value.removeLayer(marker.value)
    }
    
    // Create custom icon
    const icon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="
            background: #3b82f6;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        "></div>`,
        iconSize: [24, 24],
        iconAnchor: [12, 12],
    })
    
    marker.value = L.marker([lat, lng], { icon }).addTo(map.value)
}

async function searchLocation() {
    if (!searchQuery.value.trim()) return
    
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery.value)}&limit=5`,
            { headers: { 'Accept': 'application/json' } }
        )
        const data = await response.json()
        searchResults.value = data
        showSearch.value = true
    } catch (error) {
        console.error('Search error:', error)
    }
}

function selectResult(result: any) {
    const lat = parseFloat(result.lat)
    const lng = parseFloat(result.lon)
    
    setMarker(lat, lng)
    map.value?.setView([lat, lng], 15)
    emit('update:modelValue', { lat, lng })
    
    showSearch.value = false
    searchQuery.value = ''
}

function clearLocation() {
    if (marker.value && map.value) {
        map.value.removeLayer(marker.value)
        marker.value = null
    }
    emit('update:modelValue', null)
}
</script>

<template>
    <div class="space-y-2">
        <label v-if="label" class="text-sm font-medium">{{ label }}</label>
        
        <!-- Search Input -->
        <div class="relative">
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <MapPin class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        :placeholder="placeholder || 'Search location...'"
                        class="w-full h-10 pl-10 pr-3 rounded-md border border-input bg-background text-sm"
                        @keyup.enter="searchLocation"
                    />
                </div>
                <button
                    v-if="modelValue"
                    type="button"
                    class="px-3 h-10 rounded-md border border-destructive text-destructive hover:bg-destructive/10"
                    @click="clearLocation"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>
            
            <!-- Search Results -->
            <div
                v-if="showSearch && searchResults.length > 0"
                class="absolute z-50 w-full mt-1 bg-background border rounded-md shadow-lg max-h-60 overflow-auto"
            >
                <button
                    v-for="result in searchResults"
                    :key="result.place_id"
                    type="button"
                    class="w-full px-3 py-2 text-left text-sm hover:bg-muted"
                    @click="selectResult(result)"
                >
                    {{ result.display_name }}
                </button>
            </div>
        </div>
        
        <!-- Map -->
        <div
            ref="mapContainer"
            class="rounded-lg border overflow-hidden"
            :style="{ height: height || '200px' }"
        />
        
        <!-- Coordinates Display -->
        <p v-if="modelValue" class="text-xs text-muted-foreground">
            {{ modelValue.lat.toFixed(6) }}, {{ modelValue.lng.toFixed(6) }}
        </p>
    </div>
</template>

<style>
.custom-marker {
    background: transparent;
    border: none;
}
</style>
