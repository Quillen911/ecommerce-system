import { useQuery } from '@tanstack/react-query'
import { variantApi } from '@/lib/api/variantApi'
import { ProductDetailResponse } from '@/types/variant'

export const useProductDetail = (slug: string) => {
    return useQuery<ProductDetailResponse>({
        queryKey: ['product-detail', slug],
        queryFn: async () => {
            const response = await variantApi.getProductDetail(slug)
            return response.data
        }
    })
}