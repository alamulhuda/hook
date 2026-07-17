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
 * Helper to check if a module directory is in the enabledModules list.
 */
function isManifestEnabled(path: string, enabledModules?: string[]): boolean {
    if (!enabledModules || !Array.isArray(enabledModules)) {
        return true
    }
    const match = path.match(/\.\/modules\/([^/]+)\/routes\.ts/)
    if (!match) return true
    const dir = match[1].toLowerCase()
    return enabledModules.some(e => e.toLowerCase() === dir)
}

/**
 * Returns all nav groups from installed & enabled modules, merged for the sidebar.
 * Empty navItems arrays are skipped automatically.
 */
export function getModuleNavItems(enabledModules?: string[]): NavGroup[] {
    return Object.entries(moduleManifests)
        .filter(([path]) => isManifestEnabled(path, enabledModules))
        .flatMap(([, mod]) => mod.navItems ?? [])
        .filter((group) => group.children.length > 0)
}

/**
 * Returns all dashboard widgets from installed & enabled modules, sorted by `order`.
 */
export function getModuleDashboardWidgets(enabledModules?: string[]): DashboardWidget[] {
    return Object.entries(moduleManifests)
        .filter(([path]) => isManifestEnabled(path, enabledModules))
        .flatMap(([, mod]) => mod.dashboardWidgets ?? [])
        .sort((a, b) => (a.order ?? 99) - (b.order ?? 99))
}
