import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { addressApi } from '@/lib/api/addressApi'
import { AddressStoreRequest, AddressUpdateRequest } from '@/types/userAddress'

export const addressKeys = {
    all: ['addresses'] as const,
    index: (userId?: number) => [...addressKeys.all, 'index', userId] as const,
    detail: (id: number, userId?: number) => [...addressKeys.all, 'detail', id, userId] as const,
}

export const useUserAddressIndex = (userId?: number) => {
    return useQuery({
        queryKey: addressKeys.index(userId),
        queryFn: async () => {
            const response = await addressApi.index()
            return response.data.data
        },
        enabled: !!userId,
    })
}

export const useUserAddressStore = (userId?: number) => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: async (data: AddressStoreRequest) => {
            const response = await addressApi.store(data)
            return response.data.data
        },
        onSuccess: (data: any) => {
            queryClient.invalidateQueries({ queryKey: addressKeys.index(userId) })
        },
        onError: (error: any) => {
            // Error handling otomatik
        }
    })
}

export const useUserAddressShow = (id: number, userId?: number) => {
    return useQuery({
        queryKey: addressKeys.detail(id, userId),
        queryFn: async () => {
            const response = await addressApi.show(id)
            return response.data.data
        },
        enabled: !!id && !!userId
    })
}

export const useUserAddressUpdate = (userId?: number) => {
    const queryClient = useQueryClient()
    
    return useMutation({
        mutationFn: async ({ id, data }: { id: number; data: AddressUpdateRequest }) => {
            const response = await addressApi.update(id, data)
            return response.data.data
        },
        onSuccess: (_, { id }) => {
            queryClient.invalidateQueries({ queryKey: addressKeys.detail(id, userId) })
            queryClient.invalidateQueries({ queryKey: addressKeys.index(userId) })
        },
        onError: (error: any) => {
            // Error handling
        }
    })
}

export const useUserAddressDestroy = (userId?: number) => {
    const queryClient = useQueryClient()
    
    return useMutation({
        mutationFn: async (id: number) => {
            const response = await addressApi.destroy(id)
            return response.data
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: addressKeys.index(userId) })
        },
        onError: (error: any) => {
            // Error handling otomatik
        }
    })
}
