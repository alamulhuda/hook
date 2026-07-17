/**
 * Service Repair Module — Nav Manifest
 * Consumed by module-loader.ts → sidebar
 */
import type { NavGroup } from '../../module-loader'

export const navItems: NavGroup[] = [
    {
        label: 'Service & Repair',
        icon: 'wrench-screwdriver',
        children: [
            { label: 'Master Jasa', to: '/app/modules/service-repair/master-data/jasa', icon: 'cog-6-tooth' },
        ],
    },
]
