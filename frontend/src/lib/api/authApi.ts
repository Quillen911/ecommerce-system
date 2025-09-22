import axios from 'axios'
import { 
    LoginRequest, 
    LoginResponse, 
    RegisterRequest, 
    RegisterResponse, 
    MeResponse, 
    ProfileResponse, 
    UpdateProfileRequest, 
    UpdateProfileResponse, 
    LogoutResponse } from '@/types/user'

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
});

export const authApi = {
    csrf: () => csrfApi.get('/sanctum/csrf-cookie'),
    login: (data: LoginRequest) => api.post<LoginResponse>('/login', data),
    register: (data: RegisterRequest) => api.post<RegisterResponse>('/register', data),
    me: () => api.get<MeResponse>('/me'),
    profile: () => api.get<ProfileResponse>('/account/profile'),
    updateProfile: (data: UpdateProfileRequest) => api.put<UpdateProfileResponse>('/account/profile', data),
    logout: () => api.post<LogoutResponse>('/logout'),
}   