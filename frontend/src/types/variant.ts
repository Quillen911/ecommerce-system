import { Product, ProductVariant } from "./main"

export interface ProductDetailResponse {
    data: Product
    selected_variant_id: number
    all_variants: {
        id: number
        slug: string
        thumbnail: string
    }[]
}