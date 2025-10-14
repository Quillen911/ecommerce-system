// Root payload
export interface OrderItemsResponse {
  data: OrderItem[];
}

export interface Order {
  id: number;
  user_id: number;
  bag_id: number;
  user_shipping_address_id: number;
  user_billing_address_id: number;
  campaign_id: number;
  campaign_info: string;
  order_number: string;
  sub_total_cents: number;
  discount_cents: number;
  tax_total_cents: number;
  cargo_price_cents: number;
  campaign_price_cents: number;
  grand_total_cents: number;
  currency: string;
  status: string;
  refunded_at: string | null;
  created_at: string;
  updated_at: string;
}

export interface OrderItem {
  id: number;
  order_id: number;
  product_id: number;
  variant_size_id: number;
  store_id: number;

  product_title: string;
  product_category_title: string | null;

  selected_options: unknown | null;

  size_name: string | null;
  color_name: string | null;

  quantity: number;
  refunded_quantity: number;

  price_cents: number;
  discount_price_cents: number;
  paid_price_cents: number;

  tax_rate: number;
  tax_amount_cents: number;

  payment_transaction_id: string;
  status: string;
  refunded_price_cents: number;
  payment_status: string;

  refunded_at: string | null;
  created_at: string;
  updated_at: string;

  product: Product;
}

export interface Product {
  id: number;
  store_id: number;
  title: string;
  slug: string;
  category_id: number;
  variants: ProductVariant[];
}

export interface ProductVariant {
  id: number;
  slug: string;
  color_name: string;
  color_code: string;
  price_cents: number;
  images: ProductImage[];
  sizes: VariantSize[];
}

export interface ProductImage {
  id: number;
  product_variant_id: number;
  image: string;
}

export interface VariantSize {
  id: number;
  size_option: SizeOption;
  price_cents: number;
}

export interface SizeOption {
  id: number;
  attribute_id: number;
  value: string;
}
