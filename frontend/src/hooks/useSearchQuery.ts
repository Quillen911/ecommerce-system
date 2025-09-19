import { useQuery } from '@tanstack/react-query'
import { searchApi } from '@/lib/api/searchApi'
import { SearchResponse } from '@/types/search'

export const useSearchQuery = (query: string, filters?: any, sorting?: string, page?: number, size?: number) => {
    return useQuery<SearchResponse>({
        queryKey: ['search', query, filters, sorting, page, size],
        queryFn: async () => {
            const response = await searchApi.search(query, filters, sorting, page, size)
            return response.data
        },
    })
}
