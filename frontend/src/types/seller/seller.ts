export interface Seller {
    id: number
    name: string
    email: string
    password: string
    role: string
    status: boolean
    created_at: string
    updated_at: string
}

export interface LoginRequest {
    email: string
    password: string
}

export interface LoginResponse {
    token: string
    seller: Seller
}

export interface LogoutResponse {
    success: boolean
    message: string
}

export interface MySellerResponse {
    success: boolean
    message: string
    data: Seller
}