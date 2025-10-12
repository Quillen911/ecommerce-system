import { useMutation, useQueryClient } from "@tanstack/react-query";
import { checkoutApi } from "@/lib/api/checkoutApi";
import { UpdateShippingRequest, UpdateShippingResponse } from "@/types/checkout";

export const updateShippingKeys = {
    all: ['update-shipping'] as const,
    get: (sessionId: string) => [...updateShippingKeys.all, 'get', sessionId] as const,
}

export const useUpdateShipping = () => {
    const queryClient = useQueryClient()
    
    return useMutation({
        mutationFn: async (data: UpdateShippingRequest) => {
            const response = await checkoutApi.updateShipping(data)
            return response.data
        },
        onSuccess: (data: UpdateShippingResponse) => {
            queryClient.setQueryData(updateShippingKeys.get(data.session_id), data)
        },
        onError: (error: any) => {
            //
        }
    })
}