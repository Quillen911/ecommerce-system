import axios from 'axios'
import { ProductDetailResponse } from '@/types/variant'

const api = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
})

export const variantApi = {
    getProductDetail: (slug: string) => api.get<ProductDetailResponse>(`/variant/${slug}`),
}
