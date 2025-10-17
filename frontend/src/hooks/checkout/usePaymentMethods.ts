import { useMutation, useQueryClient } from "@tanstack/react-query"

import { checkoutApi } from "@/lib/api/checkoutApi"
import type {
  CreatePaymentIntentRequest,
  CreatePaymentIntentResponse,
} from "@/types/checkout"

type PaymentIntentApiError = {
  errors?: Record<string, string[]>
}

export const createPaymentIntentKeys = {
  all: ["create-payment-intent"] as const,
  get: (sessionId: string) => [...createPaymentIntentKeys.all, "get", sessionId] as const,
}

export const useCreatePaymentIntent = () => {
  const queryClient = useQueryClient()

  return useMutation<
    CreatePaymentIntentResponse,
    PaymentIntentApiError,
    CreatePaymentIntentRequest
  >({
    mutationFn: async (data) => {
      const response = await checkoutApi.createPaymentIntent(data)
      return response.data
    },
    onSuccess: (data) => {
      queryClient.setQueryData(createPaymentIntentKeys.get(data.session_id), data)
    },
  })
}
