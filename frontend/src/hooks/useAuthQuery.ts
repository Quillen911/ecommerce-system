import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { authApi, LoginRequest, RegisterRequest } from '@/lib/api/authApi'

export const authKeys = {
  all: ['auth'] as const,
  me: () => [...authKeys.all, 'me'] as const,
}

export const useMe = () => {
  return useQuery({
    queryKey: authKeys.me(),
    queryFn: async () => {
      const response = await authApi.me()
      return response.data.data
    },
    enabled: typeof window !== 'undefined' && !!localStorage.getItem('token'),
    staleTime: 5 * 60 * 1000, 
    retry: 1,
  })
}

export const useLogin = () => {
  const queryClient = useQueryClient()
  
  return useMutation({
    mutationFn: async (data: LoginRequest) => {
      const response = await authApi.login(data)
      return response.data
    },
    onSuccess: (data: any) => {

      localStorage.setItem('token', data.data.token)

      queryClient.setQueryData(authKeys.me(), data.data.user)
    },
    onError: (error: any) => {
      // Error handling otomatik
    }
  })
}

export const useRegister = () => {
  const queryClient = useQueryClient()
  
  return useMutation({
    mutationFn: async (data: RegisterRequest) => {
      const response = await authApi.register(data)
      return response.data
    },
    onSuccess: (data: any) => {
      
      localStorage.setItem('token', data.data.token)

      queryClient.setQueryData(authKeys.me(), data.data.user)
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
      const response = await authApi.logout()
      return response.data
    },
    onSuccess: () => {

      localStorage.removeItem('token')

      queryClient.removeQueries({ queryKey: authKeys.all })
    },
    onError: (error: any) => {
      // Hata olsa bile local state'i temizle
      localStorage.removeItem('token')
      queryClient.removeQueries({ queryKey: authKeys.all })
    }
  })
}

export const useCsrf = () => {
  return useMutation({
    mutationFn: async () => {
      const response = await authApi.csrf()
      return response.data
    },
  })
}