import { Category, Product } from "./main"

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
  category: Category
  pagination: {
    page: number
    size: number
  }
}
