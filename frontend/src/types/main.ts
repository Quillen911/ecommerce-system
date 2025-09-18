export interface MainData {
    products: {
        current_page: number
        data: Product[]
        first_page_url: string
        from: number
        last_page: number
        last_page_url: string
        links: Array<{
            url: string | null
            label: string
            page: number | null
            active: boolean
        }>
        next_page_url: string | null
        path: string
        per_page: number
        prev_page_url: string | null
        to: number
        total: number
    }
    categories: Category[]
    campaigns: Campaign[]
}

export interface Product {
    id: number
    store_id: number
    store_name: string
    title: string
    slug: string
    category_id: number
    author: string
    description: string
    meta_title: string
    meta_description: string
    list_price: number
    list_price_cents: number
    stock_quantity: number
    is_published: boolean
    sold_quantity: number
    images: string[]
    created_at: string
    updated_at: string
    deleted_at: string | null
    category: Category
}


export interface Category {
    id: number
    category_title: string
    category_slug: string
    created_at: string
    updated_at: string
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