'use client'
export const dynamic = 'force-dynamic'

import { useMemo, useEffect, useState } from 'react'
import { useParams, useRouter } from 'next/navigation'
import { toast } from 'sonner'
import ProductSummary from '@/components/seller/product/detail/ProductSummary'
import VariantTable from '@/components/seller/product/detail/VariantTable'
import VariantEditModal from '@/components/seller/product/detail/VariantEditModal'
import VariantSizeModal from '@/components/seller/product/detail/VariantSizeModal'
import VariantImageModal from '@/components/seller/product/detail/VariantImageModal'
import EmptyState from '@/components/ui/EmptyState'
import { useProductDetail, useDeleteVariant } from '@/hooks/seller/useProductQuery'
import type { Product, ProductVariant } from '@/types/seller/product'



export default function ProductDetailPage() {
  const router = useRouter()
  const params = useParams<{ id: string }>()


  const productId = Number(params.id)
  const { data, isLoading, refetch } = useProductDetail(productId)
  const product = data as Product | undefined
  const deleteVariantMutation = useDeleteVariant()
  const [activeVariant, setActiveVariant] = useState<ProductVariant | null>(null)
  const [activeModal, setActiveModal] = useState<'variant-edit' | 'variant-sizes' | 'variant-images' | null>(null)

  const handleCloseModal = () => {
    setActiveVariant(null)
    setActiveModal(null)
  }

  const handleVariantAction = (variant: ProductVariant | null, modal: typeof activeModal) => {
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

  const variants = useMemo(() => product?.variants ?? [], [product?.variants])

  const [showLoading, setShowLoading] = useState(true)

  useEffect(() => {
    if (!isLoading) setShowLoading(false)
  }, [isLoading])

  if (showLoading) {
    return (
      <div className="flex min-h-[240px] flex-col items-center justify-center gap-4 rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-10 text-center">
        <div className="h-10 w-10 animate-spin rounded-full border-2 border-gray-300 border-t-gray-800" />
        <p className="text-sm text-gray-600">Ürün detayları yükleniyor...</p>
      </div>
    )
  }

  if (!product)
    return (
      <EmptyState
        title="Ürün bulunamadı"
        description="Ürün listesine geri dönebirsin."
        actionLabel="Listeye dön"
        onAction={() => router.push('/seller/product')}
      />
    )

  return (
    <div className="space-y-10 p-3 sm:p-6 md:p-10">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-5">
        <button
          onClick={() => router.back()}
          className="rounded-lg border border-gray-300 px-4 py-2 text-sm sm:text-base text-gray-600 hover:bg-gray-100 transition"
        >
          ← Geri
        </button>
        <button
          onClick={() => handleVariantAction(null, 'variant-edit')}
          className="rounded-lg bg-gray-900 px-5 py-2.5 text-sm sm:text-base font-medium text-white hover:bg-black transition"
        >
          Varyant Ekle
        </button>
      </div>

      <div className="w-full">
        <ProductSummary product={product} />
      </div>

      <div className="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
        <VariantTable
          productId={productId}
          variants={variants}
          onEdit={(variant) => handleVariantAction(variant, 'variant-edit')}
          onManageSizes={(variant) => handleVariantAction(variant, 'variant-sizes')}
          onManageImages={(variant) => handleVariantAction(variant, 'variant-images')}
          onDelete={handleVariantDelete}
        />
      </div>

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
          }}
        />
      )}
    </div>
  )
}
