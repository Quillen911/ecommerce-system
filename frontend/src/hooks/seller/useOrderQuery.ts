import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query"
import { orderApi } from "@/lib/api/seller/orderApi"
import { SellerRefundItemRequest } from "@/types/seller/sellerOrder"

type ApiErrorBody = {
  message?: string
  errors?: Record<string, string[]>
}

const hasSellerSession = () =>
  typeof window !== 'undefined' && !!localStorage.getItem('seller_token')

export const normalizeError = (error: unknown): string => {
  const axiosLike = error as { response?: { data?: ApiErrorBody } }
  const payload = axiosLike.response?.data
  if (payload?.message) return payload.message
  if (payload?.errors) return Object.values(payload.errors).flat().join('\n')
  return 'Beklenmeyen bir hata oluştu.'
}

export const sellerOrderKeys = {
  all: ['orderItems'] as const,
  list: (sellerId?: number) => [...sellerOrderKeys.all, 'list', sellerId] as const,
  detail: (orderId: number, sellerId?: number) => [...sellerOrderKeys.all, 'detail', orderId, sellerId] as const,
}

export const useOrderList = (sellerId?: number) =>
  useQuery({
    queryKey: sellerOrderKeys.list(sellerId),
    queryFn: async () => {
      const { data } = await orderApi.index()
      return data
    },
    enabled: hasSellerSession(),
    staleTime: 5 * 60_000,
    retry: 1,
  })

export const useOrderDetail = (orderId: number, sellerId?: number) =>
  useQuery({
    queryKey: sellerOrderKeys.detail(orderId, sellerId),
    queryFn: async () => {
      const { data } = await orderApi.show(orderId)
      return data
    },
    enabled: !!orderId && hasSellerSession(),
    staleTime: 5 * 60_000,
    retry: 1,
  })

export const useOrderRefund = () => {
  const qc = useQueryClient();

  return useMutation({
    mutationFn: async ({ orderId, payload }: { orderId: number; payload: SellerRefundItemRequest }) => {
      await orderApi.refundOrderItem(orderId, payload);
    },
    onSuccess: (_data, { orderId }) => {
      qc.invalidateQueries({ queryKey: sellerOrderKeys.detail(orderId) });
    },
  });
};

export const useOrderConfirm = () => {
  const qc = useQueryClient();

  return useMutation({
    mutationFn: async ({ orderId }: { orderId: number }) => {
      await orderApi.confirmOrderItem(orderId);
    },
    onSuccess: (_data, { orderId }) => {
      qc.invalidateQueries({ queryKey: sellerOrderKeys.detail(orderId) });
    },
  });
};

