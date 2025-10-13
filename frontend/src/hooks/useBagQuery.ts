import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query"
import { bagApi } from "@/lib/api/bagApi"
import {
  BagStoreRequest,
  BagUpdateRequest,
  GetBagItems,
} from "@/types/bag"

export const bagKeys = {
  all: ["bags"] as const,
  index: (userId?: number) => [...bagKeys.all, "index", userId] as const,
  detail: (id: number, userId?: number) =>
    [...bagKeys.all, "detail", id, userId] as const,
}

export const useBagIndex = (userId?: number) =>
  useQuery<GetBagItems>({
    queryKey: bagKeys.index(userId),
    queryFn: async () => {
      try {
        const response = await bagApi.index()
        return response.data.data
      } catch (error: any) {
        if (error.response?.data?.message === "Sepet Boş!") {
          return {
            products: [],
            totals: {
              total_cents: 0,
              cargo_cents: 0,
              discount_cents: 0,
              final_cents: 0
            },
            applied_campaign: null,
            campaigns: []
          }
        }
        throw error
      }
    },
    enabled: !!userId,
  })

export const useBagStore = (userId?: number) => {
  const queryClient = useQueryClient()

  return useMutation({
    mutationFn: async (payload: BagStoreRequest) => {
      const response = await bagApi.store(payload)
      return response.data.data
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: bagKeys.index(userId) })
    },
  })
}

export const useBagUpdate = (userId?: number) => {
  const queryClient = useQueryClient()

  return useMutation({
    mutationFn: async ({ id, data }: { id: number; data: BagUpdateRequest }) => {
      const response = await bagApi.update(id, data)
      return response.data
    },
    onSuccess: (_, { id }) => {
      queryClient.invalidateQueries({ queryKey: bagKeys.detail(id, userId) })
      queryClient.invalidateQueries({ queryKey: bagKeys.index(userId) })
    },
  })
}

export const useBagDestroy = (userId?: number) => {
  const queryClient = useQueryClient()

  return useMutation({
    mutationFn: async (id: number) => {
      const response = await bagApi.destroy(id)
      return response.data
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: bagKeys.index(userId) })
    },
  })
}

export const useBagCampaignSelect = (userId?: number) => {
  const queryClient = useQueryClient()

  return useMutation({
    mutationFn: async (campaignId: number) => {
      const response = await bagApi.selectCampaign(campaignId)
      return response.data.data
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: bagKeys.index(userId) })
    },
  })
}

export const useBagCampaignUnselect = (userId?: number) => {
  const queryClient = useQueryClient()

  return useMutation({
    mutationFn: async () => {
      const response = await bagApi.unselectCampaign()
      return response.data.data
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: bagKeys.index(userId) })
    },
  })
}

