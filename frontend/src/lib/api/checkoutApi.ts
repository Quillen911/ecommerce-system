import axios from 'axios'
import { 
    CreateSessionRequest, 
    CreateSessionResponse, 
    GetSessionResponse, 
    UpdateShippingRequest, 
    UpdateShippingResponse, 
    CreatePaymentIntentRequest, 
    CreatePaymentIntentResponse 
} from '@/types/checkout'

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

export const checkoutApi = {
    createSession: (data: CreateSessionRequest) => api.post<CreateSessionResponse>('checkout/session', data),
    getSession: (sessionId: string) => api.get<GetSessionResponse>(`checkout/session/${sessionId}`),
    updateShipping: (data: UpdateShippingRequest) => api.post<UpdateShippingResponse>(`checkout/shipping`, data),
    createPaymentIntent: (data: CreatePaymentIntentRequest) => api.post<CreatePaymentIntentResponse>(`checkout/payment-intent`, data),
}
