<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/components/layout/AppLayout.vue'
import PageHeader from '@/components/layout/PageHeader.vue'
import Card from '@/components/ui/card.vue'
import Button from '@/components/ui/button.vue'
import Input from '@/components/ui/input.vue'
import Label from '@/components/ui/label.vue'

const props = defineProps<{
    settings: {
        general: Record<string, any>;
        transaction_prefix: Record<string, any>;
    };
}>();

const form = useForm({
    general: {
        app_name: props.settings.general.app_name || 'Hook ERP',
        language: props.settings.general.language || 'id',
        timezone: props.settings.general.timezone || 'Asia/Jakarta',
        currency: props.settings.general.currency || 'IDR',
        currency_locale: props.settings.general.currency_locale || 'id-ID',
        date_format: props.settings.general.date_format || 'DD/MM/YYYY',
        decimal_precision: props.settings.general.decimal_precision ?? 0,
        default_tax_rate: props.settings.general.default_tax_rate ?? 0,
    },
    transaction_prefix: {
        purchase_order: props.settings.transaction_prefix.purchase_order || 'PO',
        sales_manual: props.settings.transaction_prefix.sales_manual || 'PJ',
        sales_pos: props.settings.transaction_prefix.sales_pos || 'POS',
        trade_in: props.settings.transaction_prefix.trade_in || 'TT',
    }
});

function save() {
    form.put(route('app.settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            // Success handled by Inertia flash messages globally if configured
        }
    });
}
</script>

<template>
    <AppLayout>
        <div class="flex-1 space-y-6 p-6">
            <PageHeader
                title="General Settings"
                description="Manage your global application configurations, regional formatting, and transaction prefixes."
                :breadcrumbs="[
                    { label: 'Settings' }
                ]"
            />
            
            <form @submit.prevent="save" class="space-y-6">
                <!-- Card 1: Application -->
                <Card class="p-6">
                    <div class="space-y-2 mb-6">
                        <h3 class="text-lg font-semibold">Application</h3>
                        <p class="text-sm text-muted-foreground">Basic application settings.</p>
                    </div>
                    
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="app_name">Application Name</Label>
                            <Input id="app_name" v-model="form.general.app_name" />
                            <div v-if="form.errors['general.app_name']" class="text-xs text-red-500">{{ form.errors['general.app_name'] }}</div>
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="language">Language</Label>
                            <select id="language" v-model="form.general.language" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                <option value="id">Bahasa Indonesia</option>
                                <option value="en">English</option>
                            </select>
                            <div v-if="form.errors['general.language']" class="text-xs text-red-500">{{ form.errors['general.language'] }}</div>
                        </div>

                        <div class="space-y-2 col-span-2">
                            <Label for="timezone">Timezone</Label>
                            <select id="timezone" v-model="form.general.timezone" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                <option value="Asia/Jakarta">WIB - Asia/Jakarta (GMT+7)</option>
                                <option value="Asia/Makassar">WITA - Asia/Makassar (GMT+8)</option>
                                <option value="Asia/Jayapura">WIT - Asia/Jayapura (GMT+9)</option>
                            </select>
                            <div v-if="form.errors['general.timezone']" class="text-xs text-red-500">{{ form.errors['general.timezone'] }}</div>
                        </div>
                    </div>
                </Card>

                <!-- Card 2: Regional & Currency -->
                <Card class="p-6">
                    <div class="space-y-2 mb-6">
                        <h3 class="text-lg font-semibold">Regional & Formatting</h3>
                        <p class="text-sm text-muted-foreground">Configure global currency, taxes, and date formats.</p>
                    </div>
                    
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="currency">Currency</Label>
                            <select id="currency" v-model="form.general.currency" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                <option value="IDR">IDR (Indonesian Rupiah)</option>
                                <option value="USD">USD (US Dollar)</option>
                                <option value="SGD">SGD (Singapore Dollar)</option>
                                <option value="MYR">MYR (Malaysian Ringgit)</option>
                            </select>
                            <div v-if="form.errors['general.currency']" class="text-xs text-red-500">{{ form.errors['general.currency'] }}</div>
                        </div>

                        <div class="space-y-2">
                            <Label for="currency_locale">Locale Format</Label>
                            <select id="currency_locale" v-model="form.general.currency_locale" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                <option value="id-ID">id-ID (Indonesian)</option>
                                <option value="en-US">en-US (English)</option>
                            </select>
                            <div v-if="form.errors['general.currency_locale']" class="text-xs text-red-500">{{ form.errors['general.currency_locale'] }}</div>
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="date_format">Date Format</Label>
                            <select id="date_format" v-model="form.general.date_format" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                                <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                                <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                            </select>
                            <div v-if="form.errors['general.date_format']" class="text-xs text-red-500">{{ form.errors['general.date_format'] }}</div>
                        </div>

                        <div class="space-y-2">
                            <Label for="decimal_precision">Decimal Precision</Label>
                            <select id="decimal_precision" v-model="form.general.decimal_precision" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                <option :value="0">0 (e.g. 1.000)</option>
                                <option :value="1">1 (e.g. 1.000,0)</option>
                                <option :value="2">2 (e.g. 1.000,00)</option>
                                <option :value="3">3 (e.g. 1.000,000)</option>
                            </select>
                            <div v-if="form.errors['general.decimal_precision']" class="text-xs text-red-500">{{ form.errors['general.decimal_precision'] }}</div>
                        </div>

                        <div class="space-y-2 col-span-2">
                            <Label for="default_tax_rate">Default Tax / PPN Rate (%)</Label>
                            <Input id="default_tax_rate" type="number" step="0.1" min="0" max="100" v-model="form.general.default_tax_rate" />
                            <p class="text-xs text-muted-foreground">Set to 0 to disable default tax calculation.</p>
                            <div v-if="form.errors['general.default_tax_rate']" class="text-xs text-red-500">{{ form.errors['general.default_tax_rate'] }}</div>
                        </div>
                    </div>
                </Card>

                <!-- Card 3: Transaction Number Prefix -->
                <Card class="p-6">
                    <div class="space-y-2 mb-6">
                        <h3 class="text-lg font-semibold">Transaction Number Prefixes</h3>
                        <p class="text-sm text-muted-foreground">Customize prefixes used for auto-generating transaction numbers.</p>
                    </div>
                    
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="purchase_order">Purchase Order (PO)</Label>
                            <div class="flex gap-2">
                                <Input id="purchase_order" v-model="form.transaction_prefix.purchase_order" class="uppercase" />
                                <div class="flex items-center px-3 py-2 bg-muted text-muted-foreground text-sm rounded-md border whitespace-nowrap">
                                    {{ form.transaction_prefix.purchase_order || 'PO' }}-202607-001
                                </div>
                            </div>
                            <div v-if="form.errors['transaction_prefix.purchase_order']" class="text-xs text-red-500">{{ form.errors['transaction_prefix.purchase_order'] }}</div>
                        </div>
                        
                        <div class="space-y-2">
                            <Label for="sales_manual">Sales (Manual/Backoffice)</Label>
                            <div class="flex gap-2">
                                <Input id="sales_manual" v-model="form.transaction_prefix.sales_manual" class="uppercase" />
                                <div class="flex items-center px-3 py-2 bg-muted text-muted-foreground text-sm rounded-md border whitespace-nowrap">
                                    {{ form.transaction_prefix.sales_manual || 'PJ' }}-202607-001
                                </div>
                            </div>
                            <div v-if="form.errors['transaction_prefix.sales_manual']" class="text-xs text-red-500">{{ form.errors['transaction_prefix.sales_manual'] }}</div>
                        </div>

                        <div class="space-y-2">
                            <Label for="sales_pos">Sales (POS)</Label>
                            <div class="flex gap-2">
                                <Input id="sales_pos" v-model="form.transaction_prefix.sales_pos" class="uppercase" />
                                <div class="flex items-center px-3 py-2 bg-muted text-muted-foreground text-sm rounded-md border whitespace-nowrap">
                                    {{ form.transaction_prefix.sales_pos || 'POS' }}-202607-001
                                </div>
                            </div>
                            <div v-if="form.errors['transaction_prefix.sales_pos']" class="text-xs text-red-500">{{ form.errors['transaction_prefix.sales_pos'] }}</div>
                        </div>

                        <div class="space-y-2">
                            <Label for="trade_in">Trade-In (Tukar Tambah)</Label>
                            <div class="flex gap-2">
                                <Input id="trade_in" v-model="form.transaction_prefix.trade_in" class="uppercase" />
                                <div class="flex items-center px-3 py-2 bg-muted text-muted-foreground text-sm rounded-md border whitespace-nowrap">
                                    {{ form.transaction_prefix.trade_in || 'TT' }}-202607-001
                                </div>
                            </div>
                            <div v-if="form.errors['transaction_prefix.trade_in']" class="text-xs text-red-500">{{ form.errors['transaction_prefix.trade_in'] }}</div>
                        </div>
                    </div>
                </Card>
                
                <div class="flex justify-end gap-2">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save Settings' }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
