import axios from 'axios'
import { SearchResponse } from '@/types/search'

const api = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
})

export const searchApi = {
    search: (query: string, filters?: any, sorting?: string, page?: number, size?: number) => api.get<SearchResponse>('/search', { params: { query, filters, sorting, page, size } }),
}

