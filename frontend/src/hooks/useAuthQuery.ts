import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { authApi } from '@/lib/api/authApi' 
import { LoginRequest, RegisterRequest } from '@/types/user'
import { useRouter } from 'next/navigation'

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
    enabled: typeof window !== 'undefined' && !!localStorage.getItem('user_token'),
    staleTime: 5 * 60 * 1000, 
    retry: 1,
  })
}

export const useProfile = () => {
  return useQuery({
    queryKey: [...authKeys.all, 'profile'],
    queryFn: async () => {
      const response = await authApi.profile()
      return response.data.data
    },
    enabled: typeof window !== 'undefined' && !!localStorage.getItem('user_token'),
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
      localStorage.setItem('user_token', data.data.token)
      document.cookie = `user_token=${data.data.token}; path=/; max-age=86400`
      queryClient.setQueryData(authKeys.me(), data.data.user)

      queryClient.removeQueries({ queryKey: ['addresses'] })
      queryClient.removeQueries({ queryKey: ['orders'] })
      queryClient.removeQueries({ queryKey: ['profile'] })
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
      localStorage.setItem('user_token', data.data.token)
      document.cookie = `user_token=${data.data.token}; path=/; max-age=86400`
      queryClient.setQueryData(authKeys.me(), data.data.user)
    },
    onError: (error: any) => {
      // Error handling otomatik
    }
  })
}

export const useLogout = () => {
  const queryClient = useQueryClient();
  const router = useRouter(); // <- hook içine taşıyabilirsin

  return useMutation({
    mutationFn: async () => authApi.logout(),
    onSuccess: () => {
      localStorage.removeItem('user_token');
      document.cookie = 'user_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT';
      queryClient.clear();            // tüm auth verilerini temizle
      router.push('/login');          // logout bittiğinde yönlendir
    },
    onError: () => {
      localStorage.removeItem('user_token');
      document.cookie = 'user_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT';
      queryClient.clear();
      router.push('/login');
    },
  });
}

export const useUpdateProfile = () => {
  const queryClient = useQueryClient()
  
  return useMutation({
    mutationFn: async (data: any) => {
      const response = await authApi.updateProfile(data)
      return response.data
    },
    onSuccess: (data: any) => {
      queryClient.setQueryData([...authKeys.all, 'profile'], data.data)
      queryClient.setQueryData(authKeys.me(), data.data.user)
    },
    onError: (error: any) => {
      // Error handling otomatik
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