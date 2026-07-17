/**
 * Akunting Module — Nav Manifest
 * Consumed by module-loader.ts → sidebar
 */
import type { NavGroup } from '../../module-loader'

export const navItems: NavGroup[] = [
    {
        label: 'Akunting',
        icon: 'calculator',
        children: [
            { label: 'Input Transaksi', to: '/app/modules/akunting/input-transaksi', icon: 'pencil-square' },
            { label: 'Chart of Accounts', to: '/app/modules/akunting/chart-of-accounts', icon: 'list-bullet' },
            { label: 'Laporan Laba Rugi', to: '/app/modules/akunting/laporan-laba-rugi', icon: 'chart-bar' },
            { label: 'Laporan Neraca', to: '/app/modules/akunting/laporan-neraca', icon: 'scale' },
            { label: 'Akun Transaksi', to: '/app/admin/master-data/akun-transaksi', icon: 'banknotes' },
        ],
    },
]
