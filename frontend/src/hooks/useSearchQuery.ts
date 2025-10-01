import { useQuery } from '@tanstack/react-query'
import { searchApi } from '@/lib/api/searchApi'
import { SearchResponse, FilterResponse } from '@/types/search'
import { useSearchParams } from 'next/navigation'

export const useSearchQuery = (query: string, filters?: any, sorting?: string, page?: number, size?: number) => {
  return useQuery<SearchResponse>({
    queryKey: ['search', query, filters, sorting, page, size],
    queryFn: async () => {
      const response = await searchApi.search(query, filters, sorting, page, size)
      return response.data
    },
  })
}

export const useFilterQuery = (filters?: any, page = 1, size = 12) => {
  return useQuery<FilterResponse>({
    queryKey: ['filter', filters, page, size],
    queryFn: async () => {
      const response = await searchApi.filter(filters, page, size)
      return response.data
    },
    enabled: !!filters,
  })
}

export const useCategoryProducts = (
  category_slug: string,
) => {
  const searchParams = useSearchParams()
  return useQuery<FilterResponse>({
    queryKey: ['category-products', category_slug, searchParams.toString()],
    queryFn: async () => {
      const response = await searchApi.getCategoryProducts(category_slug, searchParams.toString())
      return response.data
    },
    enabled: !!category_slug,
  })
}
