import {Product } from "./seller/product"
import { Category } from "./seller/category"

export interface MainData {
  products: Product[]
  categories: Category[]
}
  

export interface CategoryResponse {
  products: {
    products: Product[]
    results: any
    total: number
    page: number
    size: number
  }
  filters: {
    category_ids: number[]
  }
  categories: Category[]
}