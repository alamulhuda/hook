import { clsx, type ClassValue } from 'clsx'
import { twMerge } from 'tailwind-merge'
import { router } from '@inertiajs/vue3'

function getSettings() {
    return (router.page?.props?.settings as any)?.general || {}
}

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs))
}

export function formatCurrency(value: number | string, currency?: string): string {
    const num = typeof value === 'string' ? parseFloat(value) : value
    const settings = getSettings()
    const curr = currency ?? settings.currency ?? 'IDR'
    const locale = settings.currency_locale ?? 'id-ID'
    const decimals = settings.decimal_precision ?? 0
    
    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: curr,
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    }).format(num)
}

export function formatDate(date: string | Date, format = 'long'): string {
    const d = typeof date === 'string' ? new Date(date) : date
    const settings = getSettings()
    const locale = settings.currency_locale ?? 'id-ID' // fallback locale

    // if date_format is set to specific format like YYYY-MM-DD
    if (settings.date_format && format === 'short') {
        const dObj = new Date(d)
        const day = String(dObj.getDate()).padStart(2, '0')
        const month = String(dObj.getMonth() + 1).padStart(2, '0')
        const year = dObj.getFullYear()
        
        if (settings.date_format === 'MM/DD/YYYY') return `${month}/${day}/${year}`
        if (settings.date_format === 'YYYY-MM-DD') return `${year}-${month}-${day}`
        return `${day}/${month}/${year}` // default DD/MM/YYYY
    }

    const options: Intl.DateTimeFormatOptions = {
        short: { day: '2-digit', month: '2-digit', year: 'numeric' },
        long: { day: 'numeric', month: 'long', year: 'numeric' },
        full: { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' },
    }[format]
    
    return new Intl.DateTimeFormat(locale, options).format(d)
}

export function formatNumber(value: number | string, decimals?: number): string {
    const num = typeof value === 'string' ? parseFloat(value) : value
    const settings = getSettings()
    const locale = settings.currency_locale ?? 'id-ID'
    const dec = decimals ?? settings.decimal_precision ?? 0
    
    return new Intl.NumberFormat(locale, {
        minimumFractionDigits: dec,
        maximumFractionDigits: dec,
    }).format(num)
}

export function debounce<T extends (...args: Parameters<T>) => ReturnType<T>>(
    fn: T,
    delay: number
): (...args: Parameters<T>) => void {
    let timeoutId: ReturnType<typeof setTimeout>
    return (...args: Parameters<T>) => {
        clearTimeout(timeoutId)
        timeoutId = setTimeout(() => fn(...args), delay)
    }
}

export function truncate(str: string, length: number): string {
    if (str.length <= length) return str
    return str.slice(0, length) + '...'
}

export function getInitials(name: string): string {
    return name
        .split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .slice(0, 2)
}

export function slugify(str: string): string {
    return str
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '')
}
