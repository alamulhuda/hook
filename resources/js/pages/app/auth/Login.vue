<script setup lang="ts">
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/components/layout/AppLayout.vue'
import Button from '@/components/ui/button.vue'
import Input from '@/components/ui/input.vue'
import Card from '@/components/ui/card.vue'
import Label from '@/components/ui/label.vue'
import { Package } from 'lucide-vue-next'

const page = usePage()
const errors = ref<Record<string, string>>({})

const form = ref({
    email: '',
    password: '',
    remember: false,
})

// Get errors from flash or props
if (page.props.errors) {
    errors.value = page.props.errors as Record<string, string>
}

function submit() {
    router.post('/app/login', {
        email: form.value.email,
        password: form.value.password,
        remember: form.value.remember,
    }, {
        onError: (err) => {
            errors.value = err
        },
    })
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-muted/40 p-4">
        <div class="w-full max-w-md">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary text-primary-foreground mb-4">
                    <Package class="h-8 w-8" />
                </div>
                <h1 class="text-2xl font-bold">Haen Komputer</h1>
                <p class="text-muted-foreground mt-2">Sign in to your account</p>
            </div>
            
            <Card class="p-6">
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="admin@haen.id"
                            autocomplete="email"
                            :error="errors.email"
                        />
                        <p v-if="errors.email" class="text-sm text-destructive">{{ errors.email }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <Label for="password">Password</Label>
                        <Input
                            id="password"
                            v-model="form.password"
                            type="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            :error="errors.password"
                        />
                        <p v-if="errors.password" class="text-sm text-destructive">{{ errors.password }}</p>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm">
                            <input
                                type="checkbox"
                                v-model="form.remember"
                                class="rounded border-input"
                            />
                            Remember me
                        </label>
                    </div>
                    
                    <Button type="submit" class="w-full" size="lg">
                        Sign In
                    </Button>
                </form>
            </Card>
            
            <p class="text-center text-sm text-muted-foreground mt-6">
                &copy; {{ new Date().getFullYear() }} Haen Komputer
            </p>
        </div>
    </div>
</template>
