export interface UserAddress {
    id: number
    user_id: number
    title: string
    first_name: string
    last_name: string
    phone: string
    address_line_1: string
    address_line_2: string
    district: string
    city: string
    postal_code: string
    country: string
    is_default: boolean
    is_active: boolean
    notes: string
}

export interface AddressIndexResponse {
    success: boolean
    message: string
    data: UserAddress[]
}

export interface AddressStoreRequest {
    title: string
    first_name: string
    last_name: string
    phone: string
    address_line_1: string
    address_line_2?: string
    district: string
    city: string
    postal_code?: string
    country: string
    is_default?: boolean
    is_active?: boolean
    notes?: string
}

export interface AddressStoreResponse {
    success: boolean
    message: string
    data: {
        UserAddress: UserAddress
    }
}

export interface AddressShowResponse {
    success: boolean
    message: string
    data: UserAddress
}

export interface AddressUpdateRequest {
    title?: string
    first_name?: string
    last_name?: string
    phone?: string
    address_line_1?: string
    address_line_2?: string
    district?: string
    city?: string
    postal_code?: string
    country?: string
    is_default?: boolean
    is_active?: boolean
    notes?: string
}

export interface AddressUpdateResponse {
    success: boolean
    message: string
    data: {
        UserAddress: UserAddress
    }
}

export interface AddressDestroyResponse {
    success: boolean
    message: string
    data: boolean
}