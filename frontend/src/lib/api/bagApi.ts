import axios from "axios"
import {
  GetBagItems,
  BagStoreRequest,
  BagUpdateRequest,
  BagUpdateResponse,
  BagDestroyResponse,
  BagCampaign,
} from "@/types/bag"

type ResourceResponse<T> = { data: T }

const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
  withCredentials: true,
})

api.interceptors.request.use((config) => {
  if (typeof window !== "undefined") {
    const token = localStorage.getItem("user_token")
    if (token) {
      config.headers = {
        ...config.headers,
        Authorization: `Bearer ${token}`,
      } as any
    }
  }
  return config
})

export const bagApi = {
  index: () => api.get<ResourceResponse<GetBagItems>>("bags"),
  store: (payload: BagStoreRequest) =>
    api.post<ResourceResponse<GetBagItems>>("bags", payload),
  update: (id: number, payload: BagUpdateRequest) =>
    api.put<ResourceResponse<BagUpdateResponse>>(`bags/${id}`, payload),
  destroy: (id: number) =>
    api.delete<ResourceResponse<BagDestroyResponse>>(`bags/${id}`),

    selectCampaign: (campaignId: number) =>
    api.post<ResourceResponse<GetBagItems>>("bags/campaign", { campaign_id: campaignId }),
    unselectCampaign: () =>
    api.delete<ResourceResponse<GetBagItems>>("bags/campaign"),

}
