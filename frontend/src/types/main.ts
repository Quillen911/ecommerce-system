import { Product } from "./seller/product"
import { Category } from "./seller/category"

export interface HomeCampaign {
  id?: number
  name: string
  store_id: number
  type: string
  discount_value: number | null
  description: string | null
  is_active: boolean
  starts_at: string
  ends_at: string | null
  store_name?: string
}

export interface MainData {
  products: Product[]
  categories: Category[]
  campaigns: HomeCampaign[]
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
  campaigns: HomeCampaign[]
}
