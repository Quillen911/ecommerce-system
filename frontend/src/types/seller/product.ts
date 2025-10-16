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
    image?: string | null
    image_url: string
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
    meta_title?: string | null
    variants: StoreProductVariantRequest[]
}
  
export interface StoreProductVariantRequest {
    color_name: string
    color_code: string
    price_cents: number
    is_popular: boolean
    is_active: boolean
    sizes: StoreProductVariantSizeRequest[]
    images: File[] | null
}

export interface StoreProductVariantResponse {
    data: ProductVariant
}

export interface UpdateProductVariantResponse {
    data: ProductVariant
}

export interface StoreProductVariantSizeRequest {
    size_option_id: number
    price_cents: number
    inventory: StoreProductVariantSizeInventoryRequest
}
  
export interface StoreProductVariantSizeResponse {
    data: VariantSize
}
export interface StoreProductVariantSizeInventoryRequest {
    warehouse_id: number | null
    on_hand: number
    reserved: number | null
    min_stock_level?: number | null
}
//Image

export interface StoreProductVariantImageRequest {
  image: File
}
export interface ReorderProductVariantImageRequest {
    images: {
        id: number
        sort_order: number
    }[]
}
export interface StoreProductVariantImageResponse {
    data: ProductVariantImage[]
}
export interface UpdateProductVariantImageRequest {
    images: File[] | null
}
export interface UpdateProductVariantImageResponse {
    data: ProductVariantImage[]
}
export interface DestroyProductVariantImageResponse {
    message: string
    data: boolean
}
export interface ReorderProductVariantImageResponse {
    message: string
    data: boolean
}
export interface StoreProductResponse {
    data: Product[]
}
export interface DestroyProductVariantSizeResponse {
    message: string
    data: boolean
}
export interface UpdateProductRequest {
    title?: string
    category_id?: number | null
    description?: string | null
    meta_description?: string | null
    meta_title?: string | null
    //variants?: UpdateProductVariantRequest[]
}

export interface UpdateProductVariantRequest {
    id: number
    color_name?: string
    color_code?: string
    price_cents?: number
    is_popular?: boolean
    is_active?: boolean
    images?: File[]
    sizes?: UpdateProductVariantSizeRequest[]
}

export interface UpdateProductVariantSizeRequest {
    id: number
    size_option_id?: number
    price_cents?: number
    inventory?: UpdateProductVariantSizeInventoryRequest
}

export interface UpdateProductVariantSizeResponse {
    data: VariantSize
}

export interface UpdateProductVariantSizeInventoryRequest {
    id: number
    warehouse_id?: number | null
    on_hand?: number
    reserved?: number | null
    min_stock_level?: number | null
}

export interface UpdateProductResponse {
    data: Product
}

export interface DestroyProductResponse {
    message: string
    data: boolean
}

export interface DestroyProductVariantResponse {
    message: string
    data: boolean
}

export interface BulkProductStoreRequest {
    products: BulkProductRequest[]
}
  
export interface BulkProductRequest {
    title: string
    category_id?: number | null
    description?: string | null
    meta_description?: string | null
    meta_title?: string | null
    variants?: UpdateProductVariantRequest[]
}

export interface BulkProductResponse {
    data: Product[]
}
  