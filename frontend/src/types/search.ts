import { Category, Product } from "./main"

export interface SearchResponse {
  total: number
  page: number
  size: number
  query: string
  products: Product[]
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
