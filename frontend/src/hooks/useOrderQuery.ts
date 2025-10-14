import { useQuery } from "@tanstack/react-query";
import { orderApi } from "../lib/api/orderApi";

export const OrderKeys = {
    all: ["orders"] as const,
    index: (userId?: number) => [...OrderKeys.all, "index", userId] as const,
    detail: (id: number, userId?: number) => [...OrderKeys.all, "detail", id, userId] as const,
}

export const useOrder = (userId?: number) => {
    return useQuery({
        queryKey: OrderKeys.index(userId),
        queryFn: async () => {
            const response = await orderApi.getOrders()
            return response.data
        },
        enabled: !!userId,
    })
}

export const useOrderDetail = (id: number, userId?: number) => {
    return useQuery({
        queryKey: OrderKeys.detail(id, userId),
        queryFn: async () => {
            const response = await orderApi.getOrderDetail(id)
            return response.data
        },
        enabled: !!id && !!userId,
    })
}