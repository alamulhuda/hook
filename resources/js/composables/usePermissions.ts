import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function usePermissions() {
    const page = usePage()
    
    const permissions = computed(() => {
        return page.props.permissions || []
    })
    
    function can(permission: string): boolean {
        return permissions.value.includes(permission)
    }
    
    function canAny(permissionList: string[]): boolean {
        return permissionList.some(p => permissions.value.includes(p))
    }
    
    function canAll(permissionList: string[]): boolean {
        return permissionList.every(p => permissions.value.includes(p))
    }
    
    return {
        permissions,
        can,
        canAny,
        canAll,
    }
}
