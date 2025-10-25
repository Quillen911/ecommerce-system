import { InputHTMLAttributes } from "react"

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
  maxLength?: number
  pattern?: string
  inputMode?: InputHTMLAttributes<HTMLInputElement>["inputMode"]
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
    password_confirmation: string
}

export interface RegisterResponse {
    success: boolean
    message: string
    data: {
        token: string
        user: User
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

export interface ForgotPasswordRequest {
    email: string
}

export interface ForgotPasswordResponse {
    success: boolean
    message: string
}

export interface ResetPasswordRequest {
    email: string
    token: string
    password: string
    password_confirmation: string
}

export interface ResetPasswordResponse {
    success: boolean
    message: string
}

