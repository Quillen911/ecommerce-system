import axios from "axios"
import { BulkProductResponse, 
    BulkProductStoreRequest, 
    DestroyProductResponse, 
    Product,  
    StoreProductResponse, 
    UpdateProductRequest, 
    UpdateProductResponse,
    StoreProductVariantRequest,
    StoreProductVariantResponse,
    UpdateProductVariantRequest,
    UpdateProductVariantResponse,
    DestroyProductVariantResponse,
    StoreProductVariantSizeRequest,
    StoreProductVariantSizeResponse,
    UpdateProductVariantSizeRequest,
    UpdateProductVariantSizeResponse,
    StoreProductVariantImageRequest,
    StoreProductVariantImageResponse,
    DestroyProductVariantImageResponse,
    ReorderProductVariantImageRequest,
    DestroyProductVariantSizeResponse,
} from "@/types/seller/product"

const api = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
})

api.interceptors.request.use((config) => {
    if(typeof window !== 'undefined') {
        const token = localStorage.getItem('seller_token')
        if(token) {
            config.headers = {
                ...config.headers,
                Authorization: `Bearer ${token}`,
            } as any
        }
    }
    return config
})

export const ProductApi = {
    index: () => api.get<{ message: string; data: Product[] }>('/seller/product'),

    store: (formData: FormData) =>
      api.post<StoreProductResponse>('/seller/product', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),

    showById: (id: string) => api.get<{ data: Product }>(`/seller/product/${id}`),

    update: (id: number, data: UpdateProductRequest) =>
      api.post<UpdateProductResponse>(`/seller/product/${id}?_method=PUT`, data, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),

    destroy: (id: number) => api.delete<DestroyProductResponse>(`/seller/product/${id}`),

    bulkStore: (data: BulkProductStoreRequest) =>
      api.post<BulkProductResponse>('/seller/product/bulk', data, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),

    //variant
    storeVariant: (productId: number, data: StoreProductVariantRequest) =>
      api.post<StoreProductVariantResponse>(`/seller/product/${productId}/variants`, data),

    updateVariant: ( productId: number, variantId: number, data: UpdateProductVariantRequest) =>
      api.post<UpdateProductVariantResponse>(`/seller/product/${productId}/variants/${variantId}?_method=PUT`, data),

    destroyVariant: ( productId: number, variantId: number) => api.delete<DestroyProductVariantResponse>(`/seller/product/${productId}/variants/${variantId}`),

    storeVariantSize: ( productId: number, variantId: number, data: StoreProductVariantSizeRequest) =>
      api.post<StoreProductVariantSizeResponse>(`/seller/product/${productId}/variants/${variantId}/sizes`, data),

    updateVariantSize: ( productId: number, variantId: number, id: number, data: UpdateProductVariantSizeRequest) =>
      api.post<UpdateProductVariantSizeResponse>(`/seller/product/${productId}/variants/${variantId}/sizes/${id}?_method=PUT`, data),
    destroyVariantSize: ( productId: number, variantId: number, id: number) =>
      api.delete<DestroyProductVariantSizeResponse>(`/seller/product/${productId}/variants/${variantId}/sizes/${id}`),

    storeVariantImage: (productId: number, variantId: number, file: File) => {
      const formData = new FormData()
      formData.append('image', file)

      return api.post<StoreProductVariantImageResponse>(
        `/seller/product/${productId}/variants/${variantId}/images`,
        formData,
        {
          headers: { 'Content-Type': 'multipart/form-data' },
        },
      )
    },

    destroyVariantImage: ( productId: number, variantId: number, id: number) => api.delete<DestroyProductVariantImageResponse>(`/seller/product/${productId}/variants/${variantId}/images/${id}`),

    reorderVariantImage: (productId: number, variantId: number, data: ReorderProductVariantImageRequest) =>
      api.put(`/seller/product/${productId}/variants/${variantId}/images/reorder`, data),
  }