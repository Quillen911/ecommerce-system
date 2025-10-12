import { useMutation, useQueryClient } from "@tanstack/react-query";
import { checkoutApi } from "@/lib/api/checkoutApi";
import { CreatePaymentIntentRequest, CreatePaymentIntentResponse } from "@/types/checkout";

export const createPaymentIntentKeys = {
    all: ['create-payment-intent'] as const,
    get: (sessionId: string) => [...createPaymentIntentKeys.all, 'get', sessionId] as const,
}

export const useCreatePaymentIntent = () => {
    const queryClient = useQueryClient()
    
    return useMutation({
        mutationFn: async (data: CreatePaymentIntentRequest) => {
            const response = await checkoutApi.createPaymentIntent(data)
            return response.data
        },
        onSuccess: (data: CreatePaymentIntentResponse) => {
            queryClient.setQueryData(createPaymentIntentKeys.get(data.session_id), data)
        }
    })
}