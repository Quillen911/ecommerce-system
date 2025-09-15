import axios from 'axios'

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
})

api.interceptors.request.use((config) => {
    if(typeof window !== 'undefined') {
        const token = localStorage.getItem('token')
        if(token) {
            config.headers = {
                ...config.headers,
                Authorization: `Bearer ${token}`,
            }
            
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
        user: {
            id: number
            first_name: string
            last_name: string
            username: string
            email: string
            phone: string
        }
    }
}

export interface MeResponse {
    success: boolean
    message: string
    data: {
        id: number
        first_name: string
        last_name: string
        username: string
        email: string
        phone: string
    }
}

export interface LogoutResponse {
    success: boolean
    message: string
}

export const authApi = {
    login: (data: LoginRequest) => api.post<LoginResponse>('/login', data),
    me: () => api.get<MeResponse>('/me'),
    logout: () => api.post<LogoutResponse>('/logout'),
}   