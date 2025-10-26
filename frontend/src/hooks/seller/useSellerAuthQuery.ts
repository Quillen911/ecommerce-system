import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query"
import { SellerAuthApi } from "@/lib/api/seller/sellerAuthApi"
import { LoginRequest } from "@/types/seller/seller"

export const sellerAuthKeys = {
    all: ['sellerAuth'] as const,
    mySeller: () => [...sellerAuthKeys.all, 'mySeller'] as const,
}

export const useMySeller = () => {
    return useQuery({
        queryKey: sellerAuthKeys.mySeller(),
        queryFn: async () => {
            const response = await SellerAuthApi.mySeller()
            return response.data.data
        },
        enabled: typeof window !== 'undefined' && !!localStorage.getItem('seller_token'),
        staleTime: 5 * 60 * 1000,
        retry: 1,
    })
}

export const useLogin = () => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: async (data: LoginRequest) => {
            const response = await SellerAuthApi.login(data)
            return response.data
        },
        onSuccess: (data: any) => {
            localStorage.setItem('seller_token', data.data.token)
            // Cookie backend tarafından HttpOnly olarak set ediliyor
            queryClient.setQueryData(sellerAuthKeys.mySeller(), data.data.seller)
        },
        onError: (error: any) => {
            // Error handling otomatik
        }
    })
}

export const useLogout = () => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: async () => {
            const response = await SellerAuthApi.logout()
            return response.data
        },
        onSuccess: () => {
            localStorage.removeItem('seller_token')
            // Cookie backend tarafından siliniyor
            queryClient.removeQueries({ queryKey: sellerAuthKeys.all })
            queryClient.removeQueries()
        },
        onError: (error: any) => {
            localStorage.removeItem('seller_token')
            // Cookie backend tarafından siliniyor
            queryClient.removeQueries({ queryKey: sellerAuthKeys.all })
            queryClient.removeQueries()
        }
    })
}

export const useCsrf = () => {
    return useMutation({
      mutationFn: async () => {
        const response = await SellerAuthApi.csrf()
        return response.data
      },
    })
  }
