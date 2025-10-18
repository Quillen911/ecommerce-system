export type CampaignType = 'percentage' | 'fixed' | 'x_buy_y_pay';

export interface CampaignRelationItem {
  id: number;
  name: string;
  product_id?: number;
  category_id?: number;
}

export interface Campaign {
  id: number;
  store_id: number;
  name: string;
  description: string | null;
  code: string | null;
  type: CampaignType;
  discount_value: number | null;
  buy_quantity: number | null;
  pay_quantity: number | null;
  min_subtotal: number | null;
  usage_limit: number | null;
  per_user_limit: number | null;
  usage_count: number;
  is_active: boolean;
  starts_at: string | null;
  ends_at: string | null;
  products: CampaignRelationItem[];
  categories: CampaignRelationItem[];
}

export interface CampaignBasePayload {
  name: string;
  description?: string | null;
  code?: string | null;
  type: CampaignType;
  discount_value?: number | null;
  buy_quantity?: number | null;
  pay_quantity?: number | null;
  min_subtotal?: number | null;
  usage_limit?: number | null;
  per_user_limit?: number | null;
  is_active?: boolean;
  starts_at?: string | null;
  ends_at?: string | null;
  product_ids?: number[] | null;
  category_ids?: number[] | null;
}

export type CampaignCreatePayload = CampaignBasePayload;
export type CampaignUpdatePayload = Partial<CampaignBasePayload>;
