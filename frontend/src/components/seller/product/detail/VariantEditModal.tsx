'use client'

import { useEffect } from 'react'
import { useForm } from 'react-hook-form'
import { toast } from 'sonner'
import {
  useCreateVariant,
  useUpdateVariant,
} from '@/hooks/seller/useProductQuery'
import type { ProductVariant } from '@/types/seller/product'

type VariantEditFormValues = {
  color_name: string
  color_code: string
  price_cents: number
  is_popular: boolean
  is_active: boolean
  create_size_option_id: number
  create_size_price_cents: number
  create_size_on_hand: number
  create_size_reserved: number
  create_size_min_stock: number
  create_size_warehouse_id: number | null
}

type VariantEditModalProps = {
  productId: number
  variant: ProductVariant | null
  onClose: () => void
  onUpdated: () => Promise<void> | void
}

export default function VariantEditModal({
  productId,
  variant,
  onClose,
  onUpdated,
}: VariantEditModalProps) {
  const isEdit = Boolean(variant?.id)

  const createVariant = useCreateVariant()
  const updateVariant = useUpdateVariant()

  const {
    register,
    handleSubmit,
    reset,
    watch,
    formState: { isSubmitting },
  } = useForm<VariantEditFormValues>({
    defaultValues: {
      color_name: variant?.color_name ?? '',
      color_code: variant?.color_code ?? '#000000',
      price_cents: variant?.price_cents ?? 0,
      is_popular: variant?.is_popular ?? false,
      is_active: variant?.is_active ?? true,
      create_size_option_id: 0,
      create_size_price_cents: variant?.price_cents ?? 0,
      create_size_on_hand: 0,
      create_size_reserved: 0,
      create_size_min_stock: 0,
      create_size_warehouse_id: null,
    },
  })

  useEffect(() => {
    reset({
      color_name: variant?.color_name ?? '',
      color_code: variant?.color_code ?? '#000000',
      price_cents: variant?.price_cents ?? 0,
      is_popular: variant?.is_popular ?? false,
      is_active: variant?.is_active ?? true,
      create_size_option_id: 0,
      create_size_price_cents: variant?.price_cents ?? 0,
      create_size_on_hand: 0,
      create_size_reserved: 0,
      create_size_min_stock: 0,
      create_size_warehouse_id: null,
    })
  }, [variant, reset])

  const onSubmit = async (values: VariantEditFormValues) => {
    if (isEdit && variant) {
      await updateVariant.mutateAsync(
        {
          productId,
          variantId: variant.id,
          id: variant.id,
          color_name: values.color_name,
          color_code: values.color_code,
          price_cents: values.price_cents,
          is_popular: values.is_popular,
          is_active: values.is_active,
        },
        {
          onSuccess: async () => {
            toast.success('Varyant güncellendi')
            await onUpdated()
          },
          onError: (error: unknown) => {
            toast.error(error instanceof Error ? error.message : 'Güncelleme başarısız.')
          },
        },
      )
    } else {
      await createVariant.mutateAsync(
        {
          productId,
          color_name: values.color_name,
          color_code: values.color_code,
          price_cents: values.price_cents,
          is_popular: values.is_popular ?? false,
          is_active: values.is_active ?? true,
          images: null,
          sizes: [
            {
              size_option_id: values.create_size_option_id,
              price_cents:
                values.create_size_price_cents > 0
                  ? values.create_size_price_cents
                  : values.price_cents,
              inventory: {
                on_hand: values.create_size_on_hand,
                reserved: values.create_size_reserved,
                warehouse_id: values.create_size_warehouse_id ?? null,
                min_stock_level: values.create_size_min_stock,
              },
            },
          ],
        },
        {
          onSuccess: async () => {
            toast.success('Varyant oluşturuldu')
            await onUpdated()
          },
          onError: (error: unknown) => {
            toast.error(error instanceof Error ? error.message : 'Oluşturma başarısız.')
          },
        },
      )
    }
  }

  const isBusy =
    isSubmitting ||
    createVariant.isPending ||
    updateVariant.isPending

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
      <div className="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl">
        <header className="mb-4 flex items-center justify-between">
          <div>
            <h3 className="text-lg font-semibold text-gray-900">
              {isEdit ? 'Varyantı Düzenle' : 'Yeni Varyant Ekle'}
            </h3>
            {isEdit && (
              <p className="text-xs text-gray-500">SKU: {variant?.sku ?? '—'}</p>
            )}
          </div>
          <button onClick={onClose} className="text-sm text-gray-400 hover:text-gray-600">
            Kapat
          </button>
        </header>

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <div>
            <label className="block text-xs font-medium text-gray-600">
              Renk Adı
            </label>
            <input
              type="text"
              className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
              {...register('color_name', { required: true })}
            />
          </div>

          <div>
            <label className="block text-xs font-medium text-gray-600">
              Renk Kodu (HEX)
            </label>
            <input
              type="text"
              className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
              {...register('color_code')}
            />
          </div>

          <div>
            <label className="block text-xs font-medium text-gray-600">
              Fiyat (kuruş)
            </label>
            <input
              type="number"
              className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
              {...register('price_cents', { required: true, valueAsNumber: true })}
            />
          </div>

          <div className="flex items-center gap-6">
            <label className="flex items-center gap-2 text-xs text-gray-600">
              <input type="checkbox" {...register('is_popular')} />
              Popüler
            </label>
            <label className="flex items-center gap-2 text-xs text-gray-600">
              <input type="checkbox" {...register('is_active')} />
              Aktif
            </label>
          </div>

          {!isEdit && (
            <div className="rounded-2xl border border-gray-200 bg-gray-50 p-4 space-y-3">
              <h4 className="text-xs font-semibold text-gray-700">
                İlk Beden Bilgileri
              </h4>
              <div className="grid gap-3 md:grid-cols-2">
                <div>
                  <label className="block text-[11px] font-medium text-gray-600">
                    Beden ID
                  </label>
                  <input
                    type="number"
                    className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                    {...register('create_size_option_id', { valueAsNumber: true })}
                  />
                </div>
                <div>
                  <label className="block text-[11px] font-medium text-gray-600">
                    Fiyat (kuruş)
                  </label>
                  <input
                    type="number"
                    className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                    {...register('create_size_price_cents', { valueAsNumber: true })}
                  />
                </div>
                <div>
                  <label className="block text-[11px] font-medium text-gray-600">
                    Stok (on hand)
                  </label>
                  <input
                    type="number"
                    className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                    {...register('create_size_on_hand', { valueAsNumber: true })}
                  />
                </div>
                <div>
                  <label className="block text-[11px] font-medium text-gray-600">
                    Reserved
                  </label>
                  <input
                    type="number"
                    className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                    {...register('create_size_reserved', { valueAsNumber: true })}
                  />
                </div>
                <div>
                  <label className="block text-[11px] font-medium text-gray-600">
                    Minimum stok
                  </label>
                  <input
                    type="number"
                    className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                    {...register('create_size_min_stock', { valueAsNumber: true })}
                  />
                </div>
                <div>
                  <label className="block text-[11px] font-medium text-gray-600">
                    Depo ID
                  </label>
                  <input
                    type="number"
                    className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                    {...register('create_size_warehouse_id', { valueAsNumber: true })}
                  />
                </div>
              </div>
            </div>
          )}

          <footer className="mt-4 flex justify-end gap-2 pt-2">
            <button
              type="submit"
              disabled={isBusy}
              className="rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black disabled:cursor-not-allowed disabled:opacity-70"
            >
              {isBusy ? 'Kaydediliyor...' : isEdit ? 'Güncelle' : 'Oluştur'}
            </button>
          </footer>
        </form>
      </div>
    </div>
  )
}
