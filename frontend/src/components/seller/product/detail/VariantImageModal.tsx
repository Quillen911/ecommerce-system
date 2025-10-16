'use client'

import { useEffect, useState } from 'react'
import { toast } from 'sonner'
import {
  useUploadVariantImage,
  useDeleteVariantImage,
  useReorderVariantImages,
} from '@/hooks/seller/useProductQuery'
import ProductImage from '@/components/ui/ProductImage'
import type { ProductVariant } from '@/types/seller/product'

type VariantImageModalProps = {
  productId: number
  variant: ProductVariant
  onClose: () => void
  onUpdated: () => Promise<void> | void
}

type LocalImage = {
  id: number
  url: string
  sort_order: number
}

const buildOrder = (images: ProductVariant['images']): LocalImage[] =>
  (images ?? [])
    .filter((img): img is NonNullable<typeof img> => Boolean(img?.id))
    .filter(
      (img, index, self) =>
        self.findIndex((candidate) => candidate.id === img.id) === index,
    )
    .map((img, index) => ({
      id: img.id,
      url: img.image ?? img.image_url ?? '',
      sort_order: img.sort_order ?? index + 1,
    }))
    .sort((a, b) => a.sort_order - b.sort_order)

export default function VariantImageModal({
  productId,
  variant,
  onClose,
  onUpdated,
}: VariantImageModalProps) {
  const uploadImage = useUploadVariantImage()
  const deleteImage = useDeleteVariantImage()
  const reorderImages = useReorderVariantImages()

  const [localOrder, setLocalOrder] = useState<LocalImage[]>(() =>
    buildOrder(variant.images),
  )

  useEffect(() => {
    setLocalOrder(buildOrder(variant.images))
  }, [variant.images])

const handleUpload: React.ChangeEventHandler<HTMLInputElement> = async (event) => {
  const file = event.target.files?.[0]
  if (!file) return

  try {
    await uploadImage.mutateAsync({
      productId,
      variantId: variant.id,
      image: file ,
    })
    toast.success('Görsel yüklendi')
    await onUpdated()
  } catch (error) {
    toast.error(error instanceof Error ? error.message : 'Yükleme başarısız.')
  } finally {
    event.target.value = ''
  }
}

  const handleDelete = async (imageId: number) => {
    await deleteImage.mutateAsync(
      { productId, variantId: variant.id, imageId },
      {
        onSuccess: async () => {
          toast.success('Görsel silindi')
          await onUpdated()
        },
        onError: (error: unknown) => {
          toast.error(
            error instanceof Error ? error.message : 'Silme başarısız oldu.',
          )
        },
      },
    )
  }

  const handleReorder = async (imageId: number, direction: 'up' | 'down') => {
    setLocalOrder((prev) => {
      const index = prev.findIndex((image) => image.id === imageId)
      if (index === -1) return prev

      const swapIndex = direction === 'up' ? index - 1 : index + 1
      if (swapIndex < 0 || swapIndex >= prev.length) return prev

      const next = [...prev]
      ;[next[index], next[swapIndex]] = [next[swapIndex], next[index]]

      reorderImages.mutate(
        {
          productId,
          variantId: variant.id,
          images: next.map((image, sortIndex) => ({
            id: image.id,
            sort_order: sortIndex + 1,
          })),
        },
        {
          onSuccess: async () => {
            toast.success('Sıralama güncellendi')
            onUpdated()
          },
          onError: (error: unknown) => {
            toast.error(
              error instanceof Error ? error.message : 'Sıralama yapılamadı.',
            )
          },
        },
      )

      return next
    })
  }

  const hasImages = localOrder.length > 0

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
      <div className="flex w-full max-w-3xl max-h-[90vh] flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">
        <header className="flex items-center justify-between border-b border-gray-200 px-6 py-4">
          <div>
            <h3 className="text-lg font-semibold text-gray-900">Varyant Görselleri</h3>
            <p className="text-xs text-gray-500">
              Varyant: {variant.color_name ?? '—'} | SKU: {variant.sku ?? '—'}
            </p>
          </div>
          <button onClick={onClose} className="text-sm text-gray-400 hover:text-gray-600">
            Kapat
          </button>
        </header>

        <div className="flex-1 overflow-y-auto px-6 py-4 space-y-6">
          <section>
            <label className="mb-2 block text-xs font-medium text-gray-600">
              Yeni görsel yükle
            </label>
            <input
              type="file"
              multiple
              accept="image/*"
              onChange={handleUpload}
              className="w-full rounded-xl border border-dashed border-gray-300 px-3 py-2 text-sm file:mr-4 file:rounded-lg file:border-none file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-black"
            />
          </section>

          {hasImages ? (
            <section className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
              {localOrder.map((image, index) => (
                <article
                  key={image.id}
                  className="flex flex-col rounded-2xl border border-gray-200 bg-gray-50 p-3"
                >
                  <ProductImage
                    product={image.url}
                    aspectRatio="square"
                    breakpoint="mobile"
                    config={{ width: 160, height: 160 }}
                    className="mb-3 h-32 w-full rounded-xl object-cover"
                    alt={`Varyant görseli ${index + 1}`}
                  />

                  <div className="flex items-center justify-between text-xs text-gray-500">
                    <span>Sıra: {index + 1}</span>
                    <div className="flex gap-1">
                      <button
                        disabled={index === 0 || reorderImages.isPending}
                        onClick={() => handleReorder(image.id, 'up')}
                        className="rounded-full border border-gray-200 px-2 py-1 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50"
                      >
                        ↑
                      </button>
                      <button
                        disabled={index === localOrder.length - 1 || reorderImages.isPending}
                        onClick={() => handleReorder(image.id, 'down')}
                        className="rounded-full border border-gray-200 px-2 py-1 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50"
                      >
                        ↓
                      </button>
                    </div>
                  </div>

                  <button
                    onClick={() => handleDelete(image.id)}
                    className="mt-3 rounded-xl bg-red-500 px-3 py-2 text-xs font-medium text-white hover:bg-red-600"
                  >
                    Sil
                  </button>
                </article>
              ))}
            </section>
          ) : (
            <div className="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-6 text-center text-sm text-gray-500">
              Görsel bulunmuyor. Yükleme alanından yeni görseller ekleyebilirsin.
            </div>
          )}
        </div>
      </div>
    </div>
  )
}
