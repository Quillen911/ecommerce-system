import axios from 'axios'
import { 
    GetBagItems,
    BagStoreRequest, 
    BagStoreResponse,
    BagUpdateRequest,
    BagUpdateResponse,
    BagDestroyResponse 
} from '@/types/bag'

const api = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
})

api.interceptors.request.use((config) => {
    if(typeof window !== 'undefined') {
        const token = localStorage.getItem('user_token')
        if(token) {
            config.headers = {
                ...config.headers,
                Authorization: `Bearer ${token}`,
            } as any
        }
    }
    return config
})

export const bagApi = {
    index: () => api.get<GetBagItems>('bags'),
    store: (data: BagStoreRequest) => api.post<BagStoreResponse>('bags', data),
    update: (id: number, data: BagUpdateRequest) => api.put<BagUpdateResponse>(`bags/${id}`, data),
    destroy: (id: number) => api.delete<BagDestroyResponse>(`bags/${id}`),
}