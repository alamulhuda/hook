// module-loader.ts
// Auto-discovers all module manifests from modules/*/routes.ts.
// Adding a new module = zero changes here — just create its routes.ts.
import type { Component } from 'vue'

// ─── Types ────────────────────────────────────────────────────────────────────

export interface NavChild {
    label: string
    to: string
    icon?: string
}

export interface NavGroup {
    label: string
    icon?: string
    children: NavChild[]
}

export interface DashboardWidget {
    /** Unique stable key for v-for and future enable/disable tracking */
    id: string
    /** Lazy-loaded Vue component — () => import('./widgets/Foo.vue') */
    component: () => Promise<Component>
    /** Lower = rendered first */
    order?: number
    /** Grid column span: 1 = half width, 2 = full width (on lg grid) */
    cols?: 1 | 2
}

// ─── Discovery ────────────────────────────────────────────────────────────────

// Eagerly load all module manifests
const moduleManifests = import.meta.glob<{
    navItems?: NavGroup[]
    dashboardWidgets?: DashboardWidget[]
}>('./modules/*/routes.ts', { eager: true })

/**
 * Returns all nav groups from installed modules, merged for the sidebar.
 * Empty navItems arrays are skipped automatically.
 */
export function getModuleNavItems(): NavGroup[] {
    return Object.values(moduleManifests)
        .flatMap((mod) => mod.navItems ?? [])
        .filter((group) => group.children.length > 0)
}

/**
 * Returns all dashboard widgets from installed modules, sorted by `order`.
 * Disabling a module = remove its routes.ts → widget disappears automatically.
 */
export function getModuleDashboardWidgets(): DashboardWidget[] {
    return Object.values(moduleManifests)
        .flatMap((mod) => mod.dashboardWidgets ?? [])
        .sort((a, b) => (a.order ?? 99) - (b.order ?? 99))
}
