import { UserAddress } from "./userAddress";

export interface Order {
  id: number;
  user_id: number;
  bag_id: number;
  user_shipping_address_id: number | null;
  user_billing_address_id: number | null;
  campaign_id: number | null;
  campaign_info: string | null;
  order_number: string;
  subtotal_cents: number;
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
  deleted_at: string | null;
}

export interface OrderItem {
  id: number;
  order_id: number;
  product_id: number;
  variant_size_id: number;
  store_id: number;

  product_title: string;
  product_category_title: string | null;

  selected_options: number | null;

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
  order_number: string;
}

export interface OrderDetail {
    order: OrderItem[];
    userShippingAddress: UserAddress;
    userBillingAddress: UserAddress;
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
