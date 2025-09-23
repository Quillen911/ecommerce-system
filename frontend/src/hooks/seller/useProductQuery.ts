import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { ProductApi } from "@/lib/api/seller/productApi";
import { BulkProductStoreRequest, StoreProductRequest, UpdateProductRequest } from "@/types/seller/product";

export const productKeys = {
    all: ['products'] as const,
    index: (sellerId?: number) => [...productKeys.all, 'index', sellerId] as const,
    detail: (id: number, sellerId?: number) => [...productKeys.all, 'detail', id, sellerId] as const,
    slugDetail: (slug: string, sellerId?: number) => [...productKeys.all, 'slugDetail', slug, sellerId] as const,
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
        mutationFn: async (formData: FormData) => {
            const response = await ProductApi.store(formData)
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

export const useShowProductBySlug = (slug: string, sellerId?: number) => {
    return useQuery({
        queryKey: productKeys.slugDetail(slug, sellerId),
        queryFn: async () => {
            const response = await ProductApi.showBySlug(slug) // backend: /product/{product:slug}
            return response.data.data
        },
        enabled: !!slug && typeof window !== 'undefined' && !!localStorage.getItem('seller_token'),
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

export const useDestroyProduct = (sellerId?: number) => {
    const queryClient = useQueryClient()
  
    return useMutation({
      mutationFn: async (productId: number) => {
        const response = await ProductApi.destroy(productId)
        return response.data
      },
      onSuccess: (_, productId) => {
        queryClient.invalidateQueries({ queryKey: productKeys.detail(productId, sellerId) })
        queryClient.invalidateQueries({ queryKey: productKeys.index(sellerId) })
      },
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
