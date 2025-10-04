import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { bagApi } from '@/lib/api/bagApi'
import { BagStoreRequest, BagUpdateRequest } from '@/types/bag'

export const bagKeys = {
    all: ['bags'] as const,
    index: (userId?: number) => [...bagKeys.all, 'index', userId] as const,
    detail: (id: number, userId?: number) => [...bagKeys.all, 'detail', id, userId] as const,
}

export const useBagIndex = (userId?: number) => {
    return useQuery({
        queryKey: bagKeys.index(userId),
        queryFn: async () => {
            const response = await bagApi.index()
            return response.data.data
        },
        enabled: !!userId,
    })
}

export const useBagStore = (userId?: number) => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: async (data: BagStoreRequest) => {
            const response = await bagApi.store(data)
            return response.data.data
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: bagKeys.index(userId) })
        },
        onError: (error: any) => {
            // Error handling otomatik
        }
    })
}

export const useBagUpdate = (userId?: number) => {
    const queryClient = useQueryClient()
    
    return useMutation({
        mutationFn: async ({ id, data }: { id: number; data: BagUpdateRequest }) => {
            const response = await bagApi.update(id, data)
            return response.data.data
        },
        onSuccess: (_, { id }) => {
            queryClient.invalidateQueries({ queryKey: bagKeys.detail(id, userId) })
            queryClient.invalidateQueries({ queryKey: bagKeys.index(userId) })
        },
        onError: (error: any) => {
            // Error handling
        }
    })
}

export const useBagDestroy = (userId?: number) => {
    const queryClient = useQueryClient()
    
    return useMutation({
        mutationFn: async (id: number) => {
            const response = await bagApi.destroy(id)
            return response.data
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: bagKeys.index(userId) })
        },
        onError: (error: any) => {
            // Error handling otomatik
        }
    })
}