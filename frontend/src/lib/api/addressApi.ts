import axios from 'axios'
import { AddressDestroyResponse, 
    AddressIndexResponse, 
    AddressShowResponse, 
    AddressStoreRequest, 
    AddressStoreResponse, 
    AddressUpdateRequest, 
    AddressUpdateResponse 
} from '@/types/userAddress'

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
})

api.interceptors.request.use((config) => {
    if(typeof window !== 'undefined') {
        const token = localStorage.getItem('token')
        if(token) {
            config.headers = {
                ...config.headers,
                Authorization: `Bearer ${token}`,
            } as any
        }
    }
    return config
})

export const addressApi = {
    index: () => api.get<AddressIndexResponse>('account/addresses'),
    store: (data: AddressStoreRequest) => api.post<AddressStoreResponse>('account/addresses', data),
    show: (id: number) => api.get<AddressShowResponse>(`account/addresses/${id}`),
    update: (id: number, data: AddressUpdateRequest) => api.put<AddressUpdateResponse>(`account/addresses/${id}`, data),
    destroy: (id: number) => api.delete<AddressDestroyResponse>(`account/addresses/${id}`),
}
