import axios from 'axios'
import { User } from '@/types/user'
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

        const token = localStorage.getItem('token')
        if(token) {
            config.headers = {
                ...config.headers,
                Authorization: `Bearer ${token}`,
            } as any
            
        }
    }
    return config
});

export interface LoginRequest {
    email: string
    password: string
}

export interface LoginResponse {
    success: boolean
    message: string
    data: {
        token: string
        user: User
    }
}

export interface RegisterRequest {
    firstName: string
    lastName: string
    username: string
    email: string
    password: string
    password_confirmation: string
}

export interface RegisterResponse {
    success: boolean
    message: string
    data: {
        user: User
        token: string
    }
}

export interface MeResponse {
    success: boolean
    message: string
    data: User
    
}

export interface LogoutResponse {
    success: boolean
    message: string
}

export interface ProfileResponse {
    success: boolean
    message: string
    data: {
        user: User
    }
}

export interface UpdateProfileRequest {
    first_name?: string
    last_name?: string
    username?: string
    email?: string
    phone?: string
}

export interface UpdateProfileResponse {
    success: boolean
    message: string
    data: {
        user: User
    }
}

export const authApi = {
    csrf: () => csrfApi.get('/sanctum/csrf-cookie'),
    login: (data: LoginRequest) => api.post<LoginResponse>('/login', data),
    register: (data: RegisterRequest) => api.post<RegisterResponse>('/register', data),
    me: () => api.get<MeResponse>('/me'),
    profile: () => api.get<ProfileResponse>('/account/profile'),
    updateProfile: (data: UpdateProfileRequest) => api.put<UpdateProfileResponse>('/account/profile', data),
    logout: () => api.post<LogoutResponse>('/logout'),
}   