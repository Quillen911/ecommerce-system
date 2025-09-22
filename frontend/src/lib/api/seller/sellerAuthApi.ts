import axios from 'axios'
import { LoginRequest, LoginResponse, LogoutResponse, MySellerResponse } from '@/types/seller/seller'

const api = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
})

const csrfApi = axios.create({
    baseURL: 'http://localhost:8000',
    withCredentials: true,
})

api.interceptors.request.use((config) => {
    if(typeof window !== 'undefined'){
        const token = localStorage.getItem('seller_token')

        if(token){
            config.headers = {
                ...config.headers,
                Authorization : `Bearer ${token}`
            } as any
        }
    }
    return config
})

export const SellerAuthApi = {
    csrf: () => csrfApi.get('/sanctum/csrf-cookie'),
    login: (data: LoginRequest) => api.post<LoginResponse>('/seller/login', data),
    mySeller: () => api.get<MySellerResponse>('/my-seller'),
    logout: () => api.post<LogoutResponse>('/seller-logout'),
}