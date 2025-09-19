import { Product } from "./main"

export interface BagItem {
  id: number
  bag_id: number
  product_id: number
  product_title: string
  quantity: number
  store_id: number
  created_at: string
  updated_at: string
  product: Product
}

export interface BestCampaign {
  eligible_products: BagItem[]
  eligible_total: number
  description: string
  discount: number
  per_product_discount: {
    product: Product
    quantity: number
    discount: number
  }[]
  campaign_id: number
  store_id: number
  store_name: string
}

export interface BagIndexResponse {
  message: string
  data: {
    products: BagItem[]
    bestCampaign?: BestCampaign
    total: number
    cargoPrice: number
    discount: number
    finalPrice: number
  }
}

export interface BagStoreRequest {
  product_id: number
}

export interface BagStoreResponse {
  message: string
  data: {
    id: number
    bag_id: number
    product_id: number
    product_title: string
    quantity: number
    store_id: number
  }
}

export interface BagUpdateRequest {
  quantity: number
}

export interface BagUpdateResponse {
  message: string
  data: {
    id: number
    bag_id: number
    product_id: number
    product_title: string
    quantity: number
    store_id: number
  }
}

export interface BagDestroyResponse {
  message: string
  data: {}
}
