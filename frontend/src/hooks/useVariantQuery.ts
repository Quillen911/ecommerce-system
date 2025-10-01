import { useQuery } from '@tanstack/react-query'
import { variantApi } from '@/lib/api/variantApi'
import { Product } from '@/types/main'

export const useProductDetail = (slug: string) => {
    return useQuery<Product>({
        queryKey: ['product-detail', slug],
        queryFn: async () => {
            const response = await variantApi.getProductDetail(slug)
            return response.data.data
        }
    })
}