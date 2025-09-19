import axios from 'axios'
import { Category, CategoryResponse, MainData } from '@/types/main'
const api = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
})

export const mainApi = {
    getMainData: () => api.get<{message: string, data: MainData}>('/main'),
    getCategoryData: (category_slug: string) => api.get<{message: string, data: Category}>(`/main/${category_slug}`),
    getCategoryProducts: (category_slug: string, params?: any) => api.get<{message: string, data: CategoryResponse}>(`/main/${category_slug}`, { params }),
}
