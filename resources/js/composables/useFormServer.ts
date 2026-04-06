import axios from 'axios'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            window.location.href = '/app/login'
        }
        return Promise.reject(error)
    }
)

export default axios

export function useForm<T extends Record<string, any>>(initialData?: T) {
    const data = ref<T>({} as T)
    const errors = ref<Record<string, string>>({})
    const processing = ref(false)
    
    if (initialData) {
        Object.assign(data.value, initialData)
    }
    
    function reset() {
        data.value = {} as T
        errors.value = {}
    }
    
    function setError(key: string, value: string) {
        errors.value[key] = value
    }
    
    function clearErrors() {
        errors.value = {}
    }
    
    async function submit(url: string, method: 'get' | 'post' | 'put' | 'delete' = 'post') {
        processing.value = true
        clearErrors()
        
        try {
            const response = await axios({
                method,
                url,
                data: data.value,
            })
            return response.data
        } catch (error: any) {
            if (error.response?.data?.errors) {
                errors.value = error.response.data.errors
            }
            throw error
        } finally {
            processing.value = false
        }
    }
    
    return {
        data,
        errors,
        processing,
        reset,
        setError,
        clearErrors,
        submit,
    }
}
