import axios from "axios"
import { OrderItem } from "@/types/order"
import { SellerRefundItemRequest } from "@/types/seller/sellerOrder"

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
    index: () => api.get<OrderItem[]>('/seller/order'),
    show: (id: number) => api.get<OrderItem>(`/seller/order/${id}`),
    confirmOrderItem: (id: number) => api.post(`/seller/orderitem/${id}/confirm`),
    refundOrderItem: (id: number, payload: SellerRefundItemRequest) => api.post(`/seller/orderitem/${id}/refund`, payload),
}
