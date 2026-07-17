/**
 * Inventory Module — Nav Manifest + Dashboard Widgets
 * Consumed by module-loader.ts
 */
import type { NavGroup, DashboardWidget } from '../../module-loader'

export const navItems: NavGroup[] = [
    {
        label: 'Inventori',
        icon: 'archive-box',
        children: [
            { label: 'Stock Adjustment', to: '/app/modules/inventory/stock-adjustment', icon: 'adjustments-horizontal' },
            { label: 'Stock Opname', to: '/app/modules/inventory/stock-opname', icon: 'clipboard-document-list' },
            { label: 'Master Gudang', to: '/app/modules/inventory/master-data/gudang', icon: 'building-office' },
        ],
    },
]

export const dashboardWidgets: DashboardWidget[] = [
    {
        id: 'inventory-low-stock',
        component: () => import('./widgets/LowStockWidget.vue'),
        order: 20,
        cols: 1,
    },
]
