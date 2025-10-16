import axios from 'axios';
import {
  Campaign,
  CampaignCreatePayload,
  CampaignUpdatePayload,
} from '@/types/seller/campaign';

const campaignApi = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  withCredentials: true,
});

campaignApi.interceptors.request.use((config) => {
  if (typeof window !== 'undefined') {
    const token = localStorage.getItem('seller_token');
    if (token) {
      config.headers = {
        ...config.headers,
        Authorization: `Bearer ${token}`,
      } as any;
    }
  }
  return config;
});

export const sellerCampaignApi = {
  index: () => campaignApi.get<{ data: Campaign[] }>('/seller/campaign'),
  show: (id: number) => campaignApi.get<{ data: Campaign }>(`/seller/campaign/${id}`),
  store: (payload: CampaignCreatePayload) =>
    campaignApi.post<{ data: Campaign }>('/seller/campaign', payload),
  update: (id: number, payload: CampaignUpdatePayload) =>
    campaignApi.put<{ data: Campaign }>(`/seller/campaign/${id}`, payload),
  destroy: (id: number) =>
    campaignApi.delete<{ message: string }>(`/seller/campaign/${id}`),
};
