import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { checkoutApi } from "@/lib/api/checkoutApi";
import { CreateSessionRequest, CreateSessionResponse } from "@/types/checkout";

export const checkoutSessionKeys = {
    all: ['checkout-session'] as const,
    get: (sessionId: string) => [...checkoutSessionKeys.all, 'get', sessionId] as const,
}

export const useCheckoutSession = (sessionId: string) => {
  return useQuery({
    queryKey: checkoutSessionKeys.get(sessionId),
    queryFn: async () => {
      const response = await checkoutApi.getSession(sessionId)
      return response.data
    },
    enabled: typeof window !== "undefined" && !!sessionId && !!localStorage.getItem("user_token"),
    staleTime: 0,
    refetchOnMount: "always",
  })
}

export const useCreateCheckoutSession = () => {
    const queryClient = useQueryClient()
    
    return useMutation({
        mutationFn: async (data: CreateSessionRequest) => {
            const response = await checkoutApi.createSession(data)
            return response.data
        },
        onSuccess: (data: CreateSessionResponse) => {
            queryClient.setQueryData(checkoutSessionKeys.get(data.session_id), data)
        }
    })
}
