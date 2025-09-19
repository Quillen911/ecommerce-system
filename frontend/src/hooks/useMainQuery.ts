import { useQuery, UseQueryOptions } from '@tanstack/react-query'
import { mainApi } from '@/lib/api/mainApi'
import { MainData, CategoryResponse } from '@/types/main'

export const useMainData = () => {
  return useQuery<MainData>({
    queryKey: ['main-data'],
    queryFn: async () => {
      const response = await mainApi.getMainData()
      return response.data.data
    },
  })
}

export const useCategoryProducts = (
  category_slug: string,
  options?: Omit<UseQueryOptions<CategoryResponse>, 'queryKey' | 'queryFn'>
) => {
  return useQuery<CategoryResponse>({
    queryKey: ['category-products', category_slug],
    queryFn: async () => {

      const response = await mainApi.getCategoryProducts(category_slug, {
        params: { game: category_slug }
      })
      return response.data.data
    },
    enabled: !!category_slug, // slug yoksa çalışmaz
    ...options,
  })
}