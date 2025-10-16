'use client'

import { useMemo, useState } from 'react'
import { useParams, useRouter } from 'next/navigation'
import { toast } from 'sonner'
import ProductSummary from '@/components/seller/product/detail/ProductSummary'
import VariantTable from '@/components/seller/product/detail/VariantTable'
import VariantEditModal from '@/components/seller/product/detail/VariantEditModal'
import VariantSizeModal from '@/components/seller/product/detail/VariantSizeModal'
import VariantImageModal from '@/components/seller/product/detail/VariantImageModal'
import LoadingState from '@/components/ui/LoadingState'
import EmptyState from '@/components/ui/EmptyState'
import {
  useProductDetail,
  useDeleteVariant,
} from '@/hooks/seller/useProductQuery'
import type { Product, ProductVariant } from '@/types/seller/product'

export default function ProductDetailPage() {
  const router = useRouter()
  const params = useParams<{ id: string }>()
  const productId = Number(params.id)
  const { data, isLoading, refetch } = useProductDetail(productId)
  const product = data as Product | undefined

  const deleteVariantMutation = useDeleteVariant()

  const [activeVariant, setActiveVariant] = useState<ProductVariant | null>(null)
  const [activeModal, setActiveModal] = useState<
    'variant-edit' | 'variant-sizes' | 'variant-images' | null
  >(null)

  const handleCloseModal = () => {
    setActiveVariant(null)
    setActiveModal(null)
  }

  const handleVariantAction = (
    variant: ProductVariant | null,
    modal: typeof activeModal,
  ) => {
    setActiveVariant(variant)
    setActiveModal(modal)
  }

  const handleVariantDelete = async (variant: ProductVariant) => {
    await deleteVariantMutation.mutateAsync(
      { productId: productId, variantId: variant.id },
      {
        onSuccess: () => {
          toast.success('Varyant silindi')
          refetch()
        },
        onError: (error: unknown) => {
          toast.error(error instanceof Error ? error.message : 'Silme başarısız oldu')
        },
      }
    )
  }

  const variants = useMemo(
    () => product?.variants ?? [],
    [product?.variants],
  )

  if (isLoading) {
    return <LoadingState label="Ürün detayları yükleniyor..." />
  }

  if (!product) {
    return (
      <EmptyState
        title="Ürün bulunamadı"
        description="Ürün listesine geri dönebirsin."
        actionLabel="Listeye dön"
        onAction={() => router.push('/seller/product')}
      />
    )
  }

  return (
    <div className="space-y-10">
      <div className="flex items-center justify-between">
        <button
          onClick={() => router.back()}
          className="rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-600 hover:bg-gray-100"
        >
          ← Geri
        </button>
        <button
          onClick={() => handleVariantAction(null, 'variant-edit')}
          className="rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
        >
          Varyant Ekle
        </button>
      </div>

      <ProductSummary product={product} />

      <VariantTable
        productId={productId}
        variants={variants}
        onEdit={(variant) => handleVariantAction(variant, 'variant-edit')}
        onManageSizes={(variant) => handleVariantAction(variant, 'variant-sizes')}
        onManageImages={(variant) => handleVariantAction(variant, 'variant-images')}
        onDelete={handleVariantDelete}
      />

      {activeModal === 'variant-edit' && (
        <VariantEditModal
          productId={productId}
          variant={activeVariant}
          onClose={handleCloseModal}
          onUpdated={async () => {
            await refetch()
            handleCloseModal()
          }}
        />
      )}

      {activeVariant && activeModal === 'variant-sizes' && (
        <VariantSizeModal
          productId={productId}
          variant={activeVariant}
          onClose={handleCloseModal}
          onUpdated={async () => {
            await refetch()
          }}
        />
      )}

      {activeVariant && activeModal === 'variant-images' && (
        <VariantImageModal
          productId={productId}
          variant={activeVariant}
          onClose={handleCloseModal}
          onUpdated={async () => {
            await refetch()
            handleCloseModal()
          }}
        />
      )}
    </div>
  )
}
