export interface CreateSessionRequest {
    bag_id?: number
    coupon_code?: string
}

export interface GetSessionResponse  {
    session_id: string
    expires_at: string
    status: string
    bag: Bag
    shipping_data: ShippingData
    billing_data: BillingData
    payment_data: PaymentData
    meta?: string
}

export interface CreateSessionResponse {
    session_id: string
    expires_at: string
    bag: Bag
}

export interface UpdateShippingRequest {
    session_id: string
    shipping_address_id: number
    billing_address_id?: number
    delivery_method: string
    notes?: string
}

export interface UpdateShippingResponse {
    session_id: string
    status: string
    shipping_data: ShippingData
    billing_data: BillingData
    bag: Bag
}

export interface CreatePaymentIntentRequest {
    session_id: string
    payment_method: string
    payment_method_id?: number
    provider: string
    card_alias?: string
    card_number?: string
    card_holder_name?: string
    expire_month?: string
    expire_year?: string
    cvv?: string
    save_card?: boolean
    installment?: number
    requires_3ds?: boolean
}

//SessionResponse
export interface Bag {
    items: BagSnapshot[]
    totals: Total
    applied_campaign?: AppliedCampaign
}

export interface BagSnapshot {
    bag_item_id: number
    store_id: number
    variant_size_id: number
    product_id: number
    product_title: string
    quantity: number
    unit_price_cents: number
    total_price_cents: number
}

export interface Total {
    total_cents: number
    cargo_cents: number
    discount_cents: number
    final_cents: number
}

export interface AppliedCampaign {
    campaign_id?: number
    name?: string
}

//UpdateShippingResponse
export interface ShippingData {
    shipping_address_id: number
    delivery_method: string
    notes?: string
}

export interface BillingData {
    billing_address_id?: number
}

//CreatePaymentIntentResponse
export interface CreatePaymentIntentResponse {
    session_id: string
    status: string
    payment_data: PaymentData
}

export interface PaymentData {
    provider: string
    method: string
    payment_method_id?: number
    installment?: number
    intent: Intent
    status: string
    save_card?: boolean
    new_card_payload?: NewCardPayload
}

export interface Intent {
    provider: string
    payment_id: string
    conversation_id: string
    payment_transaction_id: {
        [key: string]: string;
    };
    amount_cents: number
    currency: string
    status: string
    requires_3ds: boolean
    three_ds_html?: string
}

export interface NewCardPayload {
    card_alias: string
    last4: string
}





