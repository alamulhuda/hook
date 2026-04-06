import type { PageProps as InertiaPageProps } from '@inertiajs/vue3'

declare module '@inertiajs/vue3' {
    interface PageProps extends InertiaPageProps {
        user: {
            id: number
            name: string
            email: string
            avatar?: string
        }
        permissions: string[]
        flash?: {
            success?: string
            error?: string
            warning?: string
            info?: string
        }
    }
}

declare module 'vue' {
    interface ComponentCustomProperties {
        $page: any
    }
}

export interface BreadcrumbItem {
    label: string
    href?: string
}

export interface TableColumn<T = any> {
    key: string
    label: string
    sortable?: boolean
    searchable?: boolean
    visible?: boolean
    width?: string
    align?: 'left' | 'center' | 'right'
    render?: (value: any, row: T) => any
}

export interface TableFilter {
    key: string
    label: string
    type: 'text' | 'select' | 'date' | 'range' | 'boolean'
    options?: { label: string; value: any }[]
    placeholder?: string
}

export interface Pagination {
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
}

export interface Sort {
    field: string
    direction: 'asc' | 'desc'
}

export interface ApiResponse<T = any> {
    data: T
    message?: string
    success: boolean
}

export interface FormField {
    name: string
    label: string
    type: 'text' | 'email' | 'password' | 'number' | 'textarea' | 'select' | 'checkbox' | 'radio' | 'date' | 'datetime' | 'file' | 'relation'
    placeholder?: string
    required?: boolean
    disabled?: boolean
    readonly?: boolean
    options?: { label: string; value: any }[]
    relation?: {
        model: string
        label_field: string
        value_field: string
    }
    validation?: string
    help_text?: string
    onChange?: string
}

export interface NavItem {
    label: string
    icon?: string
    href?: string
    badge?: string | number
    children?: NavItem[]
    permission?: string
}

export interface KanbanColumn {
    id: string | number
    title: string
    color?: string
    items: KanbanItem[]
}

export interface KanbanItem {
    id: string | number
    title: string
    description?: string
    column_id: string | number
    priority?: 'low' | 'medium' | 'high'
    assignee?: { id: number; name: string; avatar?: string }
    due_date?: string
    tags?: string[]
}

export interface CalendarEvent {
    id: string | number
    title: string
    start: string
    end?: string
    all_day?: boolean
    color?: string
    description?: string
    location?: string
    attendees?: { id: number; name: string }[]
}
