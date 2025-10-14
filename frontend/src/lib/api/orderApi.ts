import axios from "axios"
import { Order, OrderItemsResponse } from "@/types/order"

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
  withCredentials: true,
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem("user_token")
  if (token) {
    config.headers = {
      ...config.headers,
      Authorization: `Bearer ${token}`,
    } as any
  }
  return config
})

export const orderApi = {
  getOrders: () => api.get<Order[]>("/orders"),
  getOrderDetail: (id: number) =>
    api.get<OrderItemsResponse>(`/orders/${id}`),
}
