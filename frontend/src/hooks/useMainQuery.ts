import { useQuery } from '@tanstack/react-query'
import { mainApi } from '@/lib/api/mainApi'
import { Category, MainData } from '@/types/main'

export const useMainData = () => {
    return useQuery<MainData>({
        queryKey: ['main-data'],
        queryFn: async () => {
            const response = await mainApi.getMainData()
            return response.data.data
        }
    })
}

export const useCategoryData = (category_slug: string) => {
    return useQuery<Category>({
        queryKey: ['category-data', category_slug],
        queryFn: async () => {
            const response = await mainApi.getCategoryData(category_slug)
            return response.data.data
        }
    })
}