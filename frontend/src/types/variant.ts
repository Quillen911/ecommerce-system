import { Product } from "./seller/product"

export interface ProductDetailResponse {
    data: Product
    selected_variant_id: number
    all_variants: {
        id: number
        slug: string
        thumbnail: string
    }[]
    similar_products?: Product[] 
}