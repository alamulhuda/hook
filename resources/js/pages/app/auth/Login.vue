<script setup lang="ts">
import { ref, computed } from 'vue'
import { useForm, usePage, Link } from '@inertiajs/vue3'
import { Eye, EyeOff, Package } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import Button from '@/components/ui/button.vue'
import Input from '@/components/ui/input.vue'
import Label from '@/components/ui/label.vue'
import Card from '@/components/ui/card.vue'

const showPassword = ref(false)
const isLoading = ref(false)

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const page = usePage()

const errors = computed(() => page.props.errors || {})

function submit() {
    isLoading.value = true
    form.post('/app/login', {
        onFinish: () => {
            isLoading.value = false
        },
    })
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-muted/40 px-4">
        <div class="w-full max-w-md space-y-6">
            <div class="text-center space-y-2">
                <div class="flex justify-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-primary text-primary-foreground">
                        <Package class="h-8 w-8" />
                    </div>
                </div>
                <h1 class="text-2xl font-bold">Welcome back</h1>
                <p class="text-muted-foreground">Sign in to your account to continue</p>
            </div>
            
            <Card class="p-6">
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="name@example.com"
                            :error="!!errors.email"
                            required
                        />
                        <p v-if="errors.email" class="text-sm text-red-500">{{ errors.email }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <Label for="password">Password</Label>
                            <Link href="/app/forgot-password" class="text-sm text-primary hover:underline">
                                Forgot password?
                            </Link>
                        </div>
                        <div class="relative">
                            <Input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="Enter your password"
                                :error="!!errors.password"
                                required
                            />
                            <button
                                type="button"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                                @click="showPassword = !showPassword"
                            >
                                <Eye v-if="!showPassword" class="h-4 w-4" />
                                <EyeOff v-else class="h-4 w-4" />
                            </button>
                        </div>
                        <p v-if="errors.password" class="text-sm text-red-500">{{ errors.password }}</p>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <input
                            id="remember"
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300"
                        />
                        <Label for="remember" class="text-sm font-normal">Remember me</Label>
                    </div>
                    
                    <div v-if="errors.message" class="rounded-md bg-red-50 p-3 text-sm text-red-500">
                        {{ errors.message }}
                    </div>
                    
                    <Button type="submit" class="w-full" :loading="isLoading">
                        Sign in
                    </Button>
                </form>
            </Card>
            
            <p class="text-center text-sm text-muted-foreground">
                Don't have an account?
                <Link href="/app/register" class="text-primary hover:underline">
                    Sign up
                </Link>
            </p>
        </div>
    </div>
</template>
