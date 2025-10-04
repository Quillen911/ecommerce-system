import { Category } from "./category"

export interface Product {
    id: number
    store_id: number
    title: string
    slug: string
    category: Category
    description: string
    meta_title: string
    meta_description: string
    is_published: boolean
    variants: ProductVariant[]
    created_at: string
    updated_at: string
}

export interface ProductVariantImage {
    id: number
    product_variant_id: number
    image: string
    is_primary: boolean
    sort_order: number
}


export interface ProductVariant {
    id: number
    product_id: number
    sku: string
    slug: string
    color_name: string
    color_code: string
    price_cents: number
    is_popular: boolean
    is_active: boolean
    images: ProductVariantImage[]
    sizes: VariantSize[]
}

export interface VariantSize {
    id: number
    product_variant_id: number
    size_option_id: number
    size_option: AttributeOption
    sku: string
    price_cents: number
    is_active: boolean
    inventory: VariantInventory
}

export interface AttributeOption {
    id: number
    attribute_id: number
    value: string
    slug: string
}

export interface VariantInventory {
    id: number
    variant_size_id: number
    warehouse_id: number
    on_hand: number
    reserved: number
    available: number
    min_stock_level: number
}
    
    
export interface StoreProductRequest {
    title: string
    category_id?: number | null
    description?: string | null
    meta_description?: string | null
    total_sold_quantity?: number
    variants: StoreProductVariantRequest[]
}
  
export interface StoreProductVariantRequest {
    price: number
    stock_quantity: number
    images: File[]
    attributes: StoreProductVariantAttributeRequest[]
}
  
export interface StoreProductVariantAttributeRequest {
    attribute_id: number
    option_id?: number | null
}
  

export interface StoreProductResponse {
    message: string
    data: Product[]
}

export interface UpdateProductRequest {
    title?: string
    category_id?: number | null
    author?: string | null
    description?: string | null
    meta_description?: string | null
    total_sold_quantity?: number
  
    variants?: UpdateVariantRequest[]
}
  
export interface UpdateVariantRequest {
    price?: number
    stock_quantity?: number
    images?: File[]
    attributes?: UpdateVariantAttributeRequest[]
}
  
export interface UpdateVariantAttributeRequest {
    attribute_id?: number
    option_id?: number | null
}

export interface UpdateProductResponse {
    message: string
    data: Product
}

export interface DestroyProductResponse {
    message: string
    data: boolean
}

export interface BulkProductStoreRequest {
    products: BulkProductRequest[]
}
  
export interface BulkProductRequest {
    title: string
    category_id?: number | null
    total_sold_quantity?: number
}

export interface BulkProductResponse {
    message: string
    data: Product[]
}
  