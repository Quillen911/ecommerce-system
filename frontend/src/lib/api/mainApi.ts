import axios from 'axios'
import { Category, MainData } from '@/types/main'
import { FilterResponse } from '@/types/search'
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
    getCategoryData: (category_slug: string) => api.get<{message: string, data: Category}>(`/category/${category_slug}`),
    getCategoryProducts: (category_slug: string, searchParams: string) => api.get<FilterResponse>(`/category/${category_slug}?${searchParams}`),
}
