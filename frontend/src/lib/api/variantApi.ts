import axios from 'axios'
import { Product } from '@/types/main'

const api = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
})

export const variantApi = {
    getProductDetail: (slug: string) => api.get<{data: Product}>(`/variant/${slug}`),
}
