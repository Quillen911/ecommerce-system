import { Category, Product, ProductVariant, Attribute } from "./main"
import { ProductImage, ProductVariantImage } from "./seller/product"

export interface SearchResponse {
  message: string
  data: {
    results: any
    total: number
    page: number
    size: number
    query: string
    products: Product[]
  }
}

export interface FilterResponse {
  products: Product[]
  filters: Record<string, any>
  categories: Category[]
  cat: Category
  total: number
  pagination: {
    page: number
    size: number
  }
}
