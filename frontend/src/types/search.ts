import { Product } from "./main"

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
  message: string
  data: {
    total: number
    page: number
    size: number
    filters: Record<string, any>
    products: Product[]
  }
}
