import axios from "axios"
import { Order, OrderItemsResponse } from "@/types/order"

const api = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
})

api.interceptors.request.use((config) => {
    const token = localStorage.getItem("seller_token")
    if (token) {
        config.headers = {
            ...config.headers,
            Authorization: `Bearer ${token}`,
        } as any
    }
    return config
})

export const orderApi = {
    index: () => api.get<Order[]>('/seller/order'),
    show: (id: number) => api.get<OrderItemsResponse>(`/seller/order/${id}`),
    confirmOrderItem: (id: number) => api.post(`/seller/order/${id}/confirm`),
    refundOrderItem: (id: number) => api.post(`/seller/order/${id}/refund`),
}
