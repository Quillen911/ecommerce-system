import { create } from 'zustand'
import { User } from '@/types/user'
import { authApi } from '@/lib/api/authApi'

interface AuthStore {
    user: User | null
    loading: boolean
    error: string | null
    fieldErrors: Record<string, string> | null
    login: (email: string, password: string) => Promise<{ success: boolean }>
    register: (first_name: string, last_name: string, username: string, email: string, password: string, password_confirmation: string) => Promise<{ success: boolean }>
    logout: () => Promise<void>
    fetchUser: () => Promise<void>
}

export const useAuthStore = create<AuthStore>((set) => ({
    user: null,
    loading: false,
    error: null,
    fieldErrors: null,
  
    login: async (email, password) => {
      set({ loading: true, error: null, fieldErrors: null })
      try {
        await authApi.csrf()
        const res = await authApi.login({ email, password })
        localStorage.setItem('token', res.data.data.token)
        set({ user: res.data.data.user })
        return { success: true }
      } catch (err: any) {
        set({
          error: err.response?.data?.message || 'Login failed',
          fieldErrors: err.response?.data?.errors || null,
        })
        return { success: false }
      } finally {
        set({ loading: false })
      }
    },
  
    register: async (first_name, last_name, username, email, password, password_confirmation) => {
      set({ loading: true, error: null, fieldErrors: null })
      try {
        await authApi.csrf()
        const res = await authApi.register({ first_name, last_name, username, email, password, password_confirmation })
        localStorage.setItem('token', res.data.data.token)
        set({ user: res.data.data.user })
        return { success: true }
      } catch (err: any) {
        if (err.response?.status === 422) {
            set({ fieldErrors: err.response.data.errors })
        } else {
            set({ error: err.response?.data?.message || 'Register failed' })
        }
        return { success: false }
      } finally {
        set({ loading: false })
      }
    },
  
    logout: async () => {
      try {
        await authApi.logout()
      } catch (_) {}
      localStorage.removeItem('token')
      set({ user: null })
    },
  
    fetchUser: async () => {
      set({ loading: true })
      try {
        const res = await authApi.me()
        set({ user: res.data.data })
      } catch (_) {
        set({ user: null })
      } finally {
        set({ loading: false })
      }
    },
  }))
  
