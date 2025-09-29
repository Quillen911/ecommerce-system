import { ProductImage, ProductVariantImage } from "./seller/product"

export interface Attribute {
    id: number
    name: string
    code: string
  }
  
  export interface AttributeOption {
    id: number
    attribute_id: number
    value: string
    slug?: string | null
  }
  
  export interface MainData {
    products: Product[]
    categories: Category[]
    campaigns: Campaign[]
    attributes: Attribute[]
    attributeOptions: AttributeOption[]
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
    category: Category
    pagination: {
      page: number
      size: number
    }
  }

  export interface ProductAttribute {
    attribute_id: number
    code: string
    name: string
    value: string
    slug?: string | null
  }
  

  export interface ProductVariant {
    id: number
    sku: string
    price: number
    price_cents: number
    stock_quantity: number
    is_popular: boolean
    images: ProductVariantImage[]
    attributes: ProductAttribute[]
  }
  
  export interface Product {
    id: number
    title: string
    slug: string
    category_id: number
    category: Category
    description: string
    meta_title: string
    meta_description: string
    list_price: number
    list_price_cents: number
    stock_quantity: number
    sold_quantity: number
    is_published: boolean
    images: ProductImage[]
    variants: ProductVariant[]
    created_at: string
    updated_at: string
    gender?: string
}
  


export interface Category {
  id: number
  title: string
  slug: string
  parent_id: number | null
  parent?: ParentCategory | null    
  children?: Category[]       
}

export interface ParentCategory {
  id: number
  title: string
  slug: string
  parent_id: number | null
  parent?: Category | null    
  children?: Category[]       
}

export interface Campaign {
    id: number
    name: string
    store_id: number
    store_name: string
    description: string
    type: string
    is_active: boolean
    priority: number | null
    usage_limit: number
    usage_limit_for_user: number
    starts_at: string
    ends_at: string
    conditions: CampaignCondition[]
    discounts: CampaignDiscount[]
}
export interface CampaignCondition {
    id: number
    campaign_id: number
    condition_type: string
    condition_value: string | number
    operator: string
}
export interface CampaignDiscount {
    id: number
    campaign_id: number
    discount_type: string
    discount_value: {
        percentage?: number
        amount?: number
        x?: number
        y?: number
    }
}