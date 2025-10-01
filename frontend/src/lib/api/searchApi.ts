import axios from 'axios'
import { SearchResponse, FilterResponse } from '@/types/search'

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  withCredentials: true,
})

export const searchApi = {
  search: (query: string, filters?: any, sorting?: string, page?: number, size?: number) =>
    api.get<SearchResponse>('/search', { params: { q: query, ...filters, sorting, page, size } }),

  filter: (filters?: any, page = 1, size = 12) =>
    api.get<FilterResponse>('/filter', { params: { ...filters, page, size } }),
  
  getCategoryProducts: (category_slug: string, searchParams: string ) => api.get<FilterResponse>(`/category/${category_slug}?${searchParams}`),
}
