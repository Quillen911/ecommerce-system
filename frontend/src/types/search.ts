import { Product, ProductVariant } from "./seller/product"
import { Category } from "./seller/category"

export interface SearchResponse {
  total: number
  page: number
  size: number
  query: string
  products: Product[]
}

export interface FilterResponse {
  products: ProductWithVariant[]
  filters: Record<string, any>
  categories: Category[]
  total: number
  pagination: {
    page: number
    size: number
  }
}

export interface ProductWithVariant {
  variant: ProductVariant
  product: Product
}

