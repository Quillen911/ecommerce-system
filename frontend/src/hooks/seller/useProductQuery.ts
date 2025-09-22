import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { ProductApi } from "@/lib/api/seller/productApi";
import { BulkProductStoreRequest, StoreProductRequest, UpdateProductRequest } from "@/types/seller/product";

export const productKeys = {
    all: ['products'] as const,
    index: (sellerId?: number) => [...productKeys.all, 'index', sellerId] as const,
    detail: (id: number, sellerId?: number) => [...productKeys.all, 'detail', id, sellerId] as const,
    bulkStore: (sellerId?: number) => [...productKeys.all, 'bulkStore', sellerId] as const,
}

export const useIndexProduct = (sellerId?: number) => {
    return useQuery({
        queryKey: productKeys.index(sellerId),
        queryFn: async () => {
            const response = await ProductApi.index()
            return response.data
        },
        enabled: typeof window !== 'undefined' && !!localStorage.getItem('seller_token'),
        staleTime: 5 * 60 * 1000,
        retry: 1,
    })
}

export const useStoreProduct = (sellerId?: number) => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: async (data: StoreProductRequest) => {
            const response = await ProductApi.store(data)
            return response.data
        },
        onSuccess: (data: any) => {
            queryClient.invalidateQueries({ queryKey: productKeys.index(sellerId) })
        },
        onError: (error: any) => {
            // Error handling
        }
    })
}

export const useShowProduct = (id: number, sellerId?: number) => {
    return useQuery({
        queryKey: productKeys.detail(id, sellerId),
        queryFn: async () => {
            const response = await ProductApi.show(id)
            return response.data
        },
        enabled: typeof window !== 'undefined' && !!localStorage.getItem('seller_token'),
        staleTime: 5 * 60 * 1000,
        retry: 1,
    })
}

export const useUpdateProduct = (id: number, sellerId?: number) => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: async (data: UpdateProductRequest) => {
            const response = await ProductApi.update(id, data)
            return response.data
        },
        onSuccess: (data: any) => {
            queryClient.invalidateQueries({ queryKey: productKeys.detail(id, sellerId) })
            queryClient.invalidateQueries({ queryKey: productKeys.index(sellerId) })
        },
        onError: (error: any) => {
            // Error handling
        }
    })
}

export const useDestroyProduct = (id: number, sellerId?: number) => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: async () => {
            const response = await ProductApi.destroy(id)
            return response.data
        },
        onSuccess: (data: any) => {
            queryClient.invalidateQueries({ queryKey: productKeys.detail(id, sellerId) })
            queryClient.invalidateQueries({ queryKey: productKeys.index(sellerId) })
        },
        onError: (error: any) => {
            // Error handling
        }
    })
}

export const useBulkStoreProduct = (sellerId?: number) => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: async (data: BulkProductStoreRequest) => {
            const response = await ProductApi.bulkStore(data)
            return response.data
        },
        onSuccess: (data: any) => {
            queryClient.invalidateQueries({ queryKey: productKeys.index(sellerId) })
        },
        onError: (error: any) => {
            // Error handling
        }
    })
}
