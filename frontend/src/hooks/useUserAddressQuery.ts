import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { addressApi } from '@/lib/api/addressApi'
import { AddressStoreRequest, AddressUpdateRequest } from '@/types/userAddress'

export const addressKeys = {
    all: ['addresses'] as const,
    index: () => [...addressKeys.all, 'index'] as const,
    detail: (id: number) => [...addressKeys.all, 'detail', id] as const,
}

export const useUserAddressIndex = () => {
    return useQuery({
        queryKey:addressKeys.index(),
        queryFn: async () => {
            const response = await addressApi.index()
            return response.data.data
        }
    })
}

export const useUserAddressStore = () => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: async (data: AddressStoreRequest) => {
            const response = await addressApi.store(data)
            return response.data.data
        },
        onSuccess: (data: any) => {
            queryClient.invalidateQueries({ queryKey: addressKeys.index() })
        },
        onError: (error: any) => {
            // Error handling otomatik
        }
    })
}

export const useUserAddressShow = (id: number) => {
    return useQuery({
        queryKey: addressKeys.detail(id),
        queryFn: async () => {
            const response = await addressApi.show(id)
            return response.data.data
        },
        enabled: !!id
    })
}

export const useUserAddressUpdate = () => {
    const queryClient = useQueryClient()
    
    return useMutation({
        mutationFn: async ({ id, data }: { id: number; data: AddressUpdateRequest }) => {
            const response = await addressApi.update(id, data)
            return response.data.data
        },
        onSuccess: (_, { id }) => {
            queryClient.invalidateQueries({ queryKey: addressKeys.detail(id) })
            queryClient.invalidateQueries({ queryKey: addressKeys.index() })
        },
        onError: (error: any) => {
            // Error handling
        }
    })
}

export const useUserAddressDestroy = () => {
    const queryClient = useQueryClient()
    
    return useMutation({
        mutationFn: async (id: number) => {
            const response = await addressApi.destroy(id)
            return response.data
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: addressKeys.index() })
        },
        onError: (error: any) => {
            // Error handling otomatik
        }
    })
}
