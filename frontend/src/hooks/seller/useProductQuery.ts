import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { ProductApi } from '@/lib/api/seller/productApi'
import {
  BulkProductResponse,
  BulkProductStoreRequest,
  DestroyProductResponse,
  DestroyProductVariantImageResponse,
  DestroyProductVariantResponse,
  DestroyProductVariantSizeResponse,
  Product,
  ReorderProductVariantImageRequest,
  ReorderProductVariantImageResponse,
  StoreProductResponse,
  StoreProductVariantImageRequest,
  StoreProductVariantImageResponse,
  StoreProductVariantRequest,
  StoreProductVariantResponse,
  StoreProductVariantSizeRequest,
  StoreProductVariantSizeResponse,
  UpdateProductRequest,
  UpdateProductResponse,
  UpdateProductVariantRequest,
  UpdateProductVariantResponse,
  UpdateProductVariantSizeRequest,
  UpdateProductVariantSizeResponse,
} from '@/types/seller/product'

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

export const productKeys = {
  all: ['products'] as const,
  list: (sellerId?: number) => [...productKeys.all, 'list', sellerId] as const,
  detail: (productId: number, sellerId?: number) =>
    [...productKeys.all, 'detail', productId, sellerId] as const,
  variants: (productId: number) => [...productKeys.detail(productId), 'variants'] as const,
  variant: (productId: number, variantId: number) =>
    [...productKeys.variants(productId), variantId] as const,
  variantSizes: (productId: number, variantId: number) =>
    [...productKeys.variant(productId, variantId), 'sizes'] as const,
  variantImages: (productId: number, variantId: number) =>
    [...productKeys.variant(productId, variantId), 'images'] as const,
  bulkStore: (sellerId?: number) => [...productKeys.all, 'bulkStore', sellerId] as const,
}

/* PRODUCTS */

export const useProductList = (sellerId?: number) =>
  useQuery({
    queryKey: productKeys.list(sellerId),
    queryFn: async () => {
      const { data } = await ProductApi.index()
      return data
    },
    enabled: hasSellerSession(),
    staleTime: 5 * 60_000,
    retry: 1,
  })

export const useProductDetail = (productId?: number, sellerId?: number) =>
  useQuery({
    queryKey: productId ? productKeys.detail(productId, sellerId) : ['products', 'detail', 'disabled'],
    queryFn: async () => {
      const { data } = await ProductApi.showById(String(productId))
      return data.data as Product
    },
    enabled: !!productId && hasSellerSession(),
    staleTime: 5 * 60_000,
    retry: 1,
  })

export const useCreateProduct = (sellerId?: number) => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async (formData: FormData) => {
      const { data } = await ProductApi.store(formData)
      return data as StoreProductResponse
    },
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: productKeys.list(sellerId) })
    },
  })
}

export const useUpdateProduct = (productId: number, sellerId?: number) => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async (payload: UpdateProductRequest) => {
      const { data } = await ProductApi.update(productId, payload)
      return data as UpdateProductResponse
    },
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: productKeys.detail(productId, sellerId) })
      qc.invalidateQueries({ queryKey: productKeys.list(sellerId) })
    },
  })
}

export const useDeleteProduct = (sellerId?: number) => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async (productId: number) => {
      const { data } = await ProductApi.destroy(productId)
      return data as DestroyProductResponse
    },
    onSuccess: (_, productId) => {
      qc.invalidateQueries({ queryKey: productKeys.detail(productId, sellerId) })
      qc.invalidateQueries({ queryKey: productKeys.list(sellerId) })
    },
  })
}

export const useBulkCreateProduct = (sellerId?: number) => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async (payload: BulkProductStoreRequest) => {
      const { data } = await ProductApi.bulkStore(payload)
      return data as BulkProductResponse
    },
    onSuccess: () => qc.invalidateQueries({ queryKey: productKeys.list(sellerId) }),
  })
}

/* VARIANTS */

type StoreVariantVariables = StoreProductVariantRequest & { productId: number }
type UpdateVariantVariables = UpdateProductVariantRequest & { productId: number; variantId: number }
type DeleteVariantVariables = { productId: number; variantId: number }

export const useCreateVariant = () => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async ({ productId, ...payload }: StoreVariantVariables) => {
      const { data } = await ProductApi.storeVariant(productId, payload)
      return { response: data as StoreProductVariantResponse, productId }
    },
    onSuccess: ({ productId }) => {
      qc.invalidateQueries({ queryKey: productKeys.variants(productId) })
      qc.invalidateQueries({ queryKey: productKeys.detail(productId) })
    },
  })
}

export const useUpdateVariant = () => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async ({ productId, variantId, ...payload }: UpdateVariantVariables) => {
      const { data } = await ProductApi.updateVariant(productId, variantId, payload)
      return { response: data as UpdateProductVariantResponse, productId, variantId }
    },
    onSuccess: ({ productId, variantId }) => {
      qc.invalidateQueries({ queryKey: productKeys.variant(productId, variantId) })
      qc.invalidateQueries({ queryKey: productKeys.detail(productId) })
    },
  })
}

export const useDeleteVariant = () => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async ({ productId, variantId }: DeleteVariantVariables) => {
      const { data } = await ProductApi.destroyVariant(productId, variantId)
      return { response: data as DestroyProductVariantResponse, productId }
    },
    onSuccess: ({ productId }) => {
      qc.invalidateQueries({ queryKey: productKeys.variants(productId) })
      qc.invalidateQueries({ queryKey: productKeys.detail(productId) })
    },
  })
}

/* VARIANT SIZES */

type StoreVariantSizeVariables = StoreProductVariantSizeRequest & { productId: number; variantId: number }
type UpdateVariantSizeVariables = UpdateProductVariantSizeRequest & {
  productId: number
  variantId: number
  sizeId: number
}
type DeleteVariantSizeVariables = { productId: number; variantId: number; sizeId: number }

export const useCreateVariantSize = () => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async ({ productId, variantId, ...payload }: StoreVariantSizeVariables) => {
      const { data } = await ProductApi.storeVariantSize(productId, variantId, payload)
      return { response: data as StoreProductVariantSizeResponse, productId, variantId }
    },
    onSuccess: ({ productId, variantId }) => {
      qc.invalidateQueries({ queryKey: productKeys.variantSizes(productId, variantId) })
      qc.invalidateQueries({ queryKey: productKeys.detail(productId) })
    },
  })
}

export const useUpdateVariantSize = () => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async ({ productId, variantId, sizeId, ...payload }: UpdateVariantSizeVariables) => {
      const { data } = await ProductApi.updateVariantSize(productId, variantId, sizeId, payload)
      return { response: data as UpdateProductVariantSizeResponse, productId, variantId }
    },
    onSuccess: ({ productId, variantId }) => {
      qc.invalidateQueries({ queryKey: productKeys.variantSizes(productId, variantId) })
      qc.invalidateQueries({ queryKey: productKeys.detail(productId) })
    },
  })
}

export const useDeleteVariantSize = () => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async ({ productId, variantId, sizeId }: DeleteVariantSizeVariables) => {
      const { data } = await ProductApi.destroyVariantSize(productId, variantId, sizeId)
      return { response: data as DestroyProductVariantSizeResponse, productId, variantId }
    },
    onSuccess: ({ productId, variantId }) => {
      qc.invalidateQueries({ queryKey: productKeys.variantSizes(productId, variantId) })
      qc.invalidateQueries({ queryKey: productKeys.detail(productId) })
    },
  })
}

/* VARIANT IMAGES */

type UploadVariantImageVariables = StoreProductVariantImageRequest & { productId: number; variantId: number, image: File }
type DeleteVariantImageVariables = { productId: number; variantId: number; imageId: number }
type ReorderVariantImageVariables = ReorderProductVariantImageRequest & { productId: number; variantId: number }

export const useUploadVariantImage = () => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async ({ productId, variantId, image }: UploadVariantImageVariables) => {
      const { data } = await ProductApi.storeVariantImage(productId, variantId, image)
      return { response: data as StoreProductVariantImageResponse, productId, variantId }
    },
    onSuccess: ({ productId, variantId }) => {
      qc.invalidateQueries({ queryKey: productKeys.variantImages(productId, variantId) })
      qc.invalidateQueries({ queryKey: productKeys.detail(productId) })
    },
  })
}

export const useDeleteVariantImage = () => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async ({ productId, variantId, imageId }: DeleteVariantImageVariables) => {
      const { data } = await ProductApi.destroyVariantImage(productId, variantId, imageId)
      return { response: data as DestroyProductVariantImageResponse, productId, variantId }
    },
    onSuccess: ({ productId, variantId }) => {
      qc.invalidateQueries({ queryKey: productKeys.variantImages(productId, variantId) })
      qc.invalidateQueries({ queryKey: productKeys.detail(productId) })
    },
  })
}

export const useReorderVariantImages = () => {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: async ({ productId, variantId, ...payload }: ReorderVariantImageVariables) => {
      const { data } = await ProductApi.reorderVariantImage(productId, variantId, payload)
      return { response: data as ReorderProductVariantImageResponse, productId, variantId }
    },
    onSuccess: ({ productId, variantId }) => {
      qc.invalidateQueries({ queryKey: productKeys.variantImages(productId, variantId) })
    },
  })
}
