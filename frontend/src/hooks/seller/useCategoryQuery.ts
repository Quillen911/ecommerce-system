import { useQuery } from "@tanstack/react-query"
import { CategoryApi } from "@/lib/api/seller/categoryApi"

export const categoryKeys = {
    all: ['category'] as const,
    children: (id: number, sellerId?: number) => [...categoryKeys.all, 'children', id, sellerId] as const,
}

export const useCategory = (id: number, sellerId?: number) => {
    return useQuery({
        queryKey: categoryKeys.children(id, sellerId),
        queryFn: async () => {
            const response = await CategoryApi.children(id)
            return response.data
        },
        enabled: !!id && !!sellerId,
        staleTime: 5 * 60 * 1000,
        retry: 1,
    })
}