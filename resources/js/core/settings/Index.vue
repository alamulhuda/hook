<script setup lang="ts">
import { ref } from 'vue'
import AppLayout from '@/components/layout/AppLayout.vue'
import PageHeader from '@/components/layout/PageHeader.vue'
import Card from '@/components/ui/card.vue'
import Button from '@/components/ui/button.vue'
import Input from '@/components/ui/input.vue'
import Label from '@/components/ui/label.vue'

const profile = ref({
    nama_toko: 'Toko Hook',
    alamat: '',
    telepon: '',
    email: '',
})

function save() {
    // Save profile logic
}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="Settings"
                description="Manage your application settings."
                :breadcrumbs="[
                    { label: 'Settings' }
                ]"
            />
            
            <Card class="p-6">
                <form @submit.prevent="save" class="space-y-6">
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold">Company Profile</h3>
                        <p class="text-sm text-muted-foreground">Update your company information.</p>
                    </div>
                    
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="nama_toko">Store Name</Label>
                            <Input id="nama_toko" v-model="profile.nama_toko" />
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="telepon">Phone</Label>
                            <Input id="telepon" v-model="profile.telepon" />
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input id="email" v-model="profile.email" type="email" />
                    </div>
                    
                    <div class="space-y-2">
                        <Label for="alamat">Address</Label>
                        <textarea id="alamat" v-model="profile.alamat" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" />
                    </div>
                    
                    <div class="flex justify-end gap-2">
                        <Button type="submit">Save Changes</Button>
                    </div>
                </form>
            </Card>
        </div>
    </AppLayout>
</template>
