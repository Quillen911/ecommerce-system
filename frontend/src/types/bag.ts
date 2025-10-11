import { VariantInventory, VariantSize, ProductVariantImage } from "./seller/product"

export interface BagItem {
  id: number
  bag_id: number
  variant_size_id: number
  product_title: string
  quantity: number
  unit_price_cents: number
  store_id: number
  created_at: string
  updated_at: string
  sizes: VariantSizeInBag
}

export interface VariantSizeInBag {
  id: number
  product_variant_id: number
  size_option_id: number
  sku: string
  price_cents: number
  is_active: boolean
  created_at: string
  updated_at: string
  product_variant: ProductVariantInBag
  inventory: VariantInventory
}

export interface ProductVariantInBag {
  id: number
  product_id: number
  sku: string
  slug: string
  color_name: string
  color_code: string
  is_active: boolean
  is_popular: boolean
  created_at: string
  updated_at: string
  variant_images: ProductVariantImage[]
}

export interface BagDiscountItem {
  bag_item_id: number
  product_id: number
  quantity: number
  unit_price_cents: number
  line_total_cents: number
  discount_cents: number
  discount: number
  discounted_total_cents: number
  discounted_total: number
}

export interface BagTotals {
  total_cents: number
  total: number
  cargo_cents: number
  cargo: number
  discount_cents: number
  discount: number
  final_cents: number
  final: number
}

export interface BagCampaign {
  id: number
  name: string
  type: string
  description: string | null
  discount_cents: number
  discount: number
  ends_at: string | null
  items?: BagDiscountItem[]
}

export interface GetBagItems {
  products: BagItem[]
  totals: BagTotals
  applied_campaign: BagCampaign | null
  campaigns: BagCampaign[]
}

export interface BagStoreRequest {
  variant_size_id: number
  quantity?: number | null
}

export interface BagUpdateRequest {
  quantity: number
}

export interface BagUpdateResponse {
  message: string
  data: {
    id: number
    bag_id: number
    variant_size_id: number
    product_title: string
    unit_price_cents: number
    store_id: number
    quantity: number
  }
}

export interface BagDestroyResponse {
  message: string
  data: Record<string, never>
}
