export interface InputProps {
    label?: string
    placeholder?: string
    value?: string
    onChange?: (value: string) => void
    type?: string
    required?: boolean
    disabled?: boolean
    error?: string
    autoComplete?: string
}

export interface User {
    id: number
    first_name: string
    last_name: string
    username: string
    email: string
    phone?: string
}

export interface LoginRequest {
    email: string
    password: string
}

export interface LoginResponse {
    token: string
    user: User
}

export interface RegisterRequest {
    first_name: string
    last_name: string
    username: string
    email: string
    password: string
}

export interface RegisterResponse {
    success: boolean
    message: string
    data: {
        token: string
        user: User
    }
}