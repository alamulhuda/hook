# Inertia.js Patterns Reference

## Page Component

```vue
<script setup>
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
  items: Array,
  filters: Object,
})

defineOptions({ layout: AppLayout })
</script>
```

## Layout Setup

```vue
<!-- resources/js/layouts/AppLayout.vue -->
<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const showingSidebar = ref(false)
</script>

<template>
  <div>
    <nav>...</nav>
    <main>
      <slot />
    </main>
  </div>
</template>
```

## Form with VeeValidate + Zod

```vue
<script setup>
import { useForm } from '@inertiajs/vue3'
import { toTypedSchema } from '@vee-validate/zod'
import { useForm as useVeeForm } from 'vee-validate'
import { toRaw } from 'vue'

const schema = toTypedSchema(zod.object({
  name: zod.string().min(2),
  qty: zod.number().min(1),
}))

const { values, errors, handleSubmit } = useVeeForm({
  validationSchema: schema,
})

const form = useForm({ values })
const submit = handleSubmit((values) => {
  form.post('/route', { data: toRaw(values) })
})
</script>
```

## Confirm Dialog Before Action

```vue
<script setup>
function deleteItem(id) {
  if (confirm('Yakin hapus?')) {
    router.delete(`/items/${id}`)
  }
}
</script>
```

## Reload Only Specific Props

```vue
<script setup>
function refresh() {
  router.reload({ only: ['items'] })
}
</script>
```

## Inertia Link with Active State

```vue
<NavLink :href="route('dashboard')" :active="route().current('dashboard')">
  Dashboard
</NavLink>
```

## MediaPicker Component

Image upload/dropzone with preview:

```vue
<script setup>
import MediaPicker from '@/components/forms/MediaPicker.vue'

const image = ref(null)
</script>

<template>
  <MediaPicker
    v-model="image"
    label="Product Image"
    accept="image/*"
    :max-size="2"
  />
</template>
```

## MapPicker Component

Location picker with Leaflet + OpenStreetMap:

```vue
<script setup>
import MapPicker from '@/components/forms/MapPicker.vue'

const location = ref(null)
</script>

<template>
  <MapPicker
    v-model="location"
    label="Store Location"
    placeholder="Search for a location..."
    height="250px"
  />
</template>
```

Location object shape: `{ lat: number, lng: number }`
