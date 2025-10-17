import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { sellerCampaignApi } from '@/lib/api/seller/campaignApi';
import { CampaignCreatePayload, CampaignUpdatePayload } from '@/types/seller/campaign';

export const campaignKeys = {
  all: ['seller', 'campaigns'] as const,
  index: () => [...campaignKeys.all, 'index'] as const,
  detail: (id: number) => [...campaignKeys.all, 'detail', id] as const,
};

export const useCampaignIndex = () =>
  useQuery({
    queryKey: campaignKeys.index(),
    queryFn: async () => {
      const response = await sellerCampaignApi.index();
      return response.data.data;
    },
  });

export const useCampaignShow = (id?: number) =>
  useQuery({
    queryKey: campaignKeys.detail(id as number),
    queryFn: async () => {
      const response = await sellerCampaignApi.show(id as number);
      return response.data.data;
    },
    enabled: Number.isFinite(id),
  });

export const useCampaignStore = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (payload: CampaignCreatePayload) => {
      const response = await sellerCampaignApi.store(payload);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: campaignKeys.index() });
    },
  });
};

export const useCampaignUpdate = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ id, payload }: { id: number; payload: CampaignUpdatePayload }) => {
      console.log('payload', payload)
      const response = await sellerCampaignApi.update(id, payload);
      return response.data.data;
    },
    onSuccess: (_, { id }) => {
      queryClient.invalidateQueries({ queryKey: campaignKeys.index() });
      queryClient.invalidateQueries({ queryKey: campaignKeys.detail(id) });
    },
  });
};

export const useCampaignDestroy = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (id: number) => {
      const response = await sellerCampaignApi.destroy(id);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: campaignKeys.index() });
    },
  });
};
