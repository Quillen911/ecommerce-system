import axios from "axios"
import { BulkProductResponse, 
    BulkProductStoreRequest, 
    DestroyProductResponse, 
    Product, 
    StoreProductRequest, 
    StoreProductResponse, 
    UpdateProductRequest, 
    UpdateProductResponse 
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
    store: (data: StoreProductRequest) =>
      api.post<StoreProductResponse>('/seller/product', data, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),
    show: (id: number) => api.get<Product>(`/seller/product/${id}`),
    update: (id: number, data: UpdateProductRequest) =>
      api.post<UpdateProductResponse>(`/seller/product/${id}?_method=PUT`, data, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),
    destroy: (id: number) => api.delete<DestroyProductResponse>(`/seller/product/${id}`),
    bulkStore: (data: BulkProductStoreRequest) =>
      api.post<BulkProductResponse>('/seller/product/bulk', data, {
        headers: { 'Content-Type': 'multipart/form-data' },
      }),
  }