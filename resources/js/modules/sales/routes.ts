/**
 * Sales Module — Nav Manifest + Dashboard Widgets
 * Consumed by module-loader.ts
 */
import type { NavGroup, DashboardWidget } from '../../module-loader'

// Sales nav items (future: analytics, reports, etc.)
export const navItems: NavGroup[] = []

export const dashboardWidgets: DashboardWidget[] = [
    {
        id: 'sales-recent',
        component: () => import('./widgets/RecentSalesWidget.vue'),
        order: 10,
        cols: 1,
    },
]
