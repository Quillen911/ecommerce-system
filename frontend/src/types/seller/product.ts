import { Category } from "./category"

export interface ProductImage {
    id: number
    product_id: number
    image: string
    is_primary: boolean
    sort_order: number
}

export interface ProductVariantImage {
    id: number
    product_variant_id: number
    image: string
    is_primary: boolean
    sort_order: number
}

export interface ProductVariantAttribute {
    id: number
    attribute_id: number
    code: string
    name: string
    value: string
    slug: string
}

export interface ProductVariant {
    id: number
    sku: string
    price: number
    price_cents: number
    stock_quantity: number
    is_popular: boolean
    images: ProductVariantImage[]
    attributes: ProductVariantAttribute[]
}

export interface Product {
    id: number
    title: string
    slug: string
    category_id: Category[]
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
}

export interface StoreProductRequest {
    title: string
    category_id?: number | null
    description?: string | null
    meta_description?: string | null
    list_price: number
    stock_quantity: number
    images?: File[] 
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
    list_price?: number
    stock_quantity?: number
    sold_quantity?: number
  
    images?: File[]  
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
    list_price: number
    stock_quantity: number
    images: File[]
}

export interface BulkProductResponse {
    message: string
    data: Product[]
}
  