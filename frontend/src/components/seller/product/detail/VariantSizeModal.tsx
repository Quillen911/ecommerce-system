'use client'

import { useMemo, useState } from 'react'
import { useForm } from 'react-hook-form'
import { useCreateVariantSize, useUpdateVariantSize, useDeleteVariantSize, normalizeError } from '@/hooks/seller/useProductQuery'
import type { ProductVariant, VariantSize } from '@/types/seller/product'
import { toast } from 'sonner'

type SizeFormValues = {
  size_option_id: number
  price_cents: number
  on_hand: number
  reserved?: number | null
  warehouse_id?: number | null
  min_stock_level?: number | null
}

type VariantSizeModalProps = {
  productId: number
  variant: ProductVariant
  onClose: () => void
  onUpdated: () => Promise<void> | void
}

type ApiFieldErrors = Record<string, string>

const normalize = (value: FormDataEntryValue | null): number | undefined => {
  if (value === null || value === '') return undefined
  const parsed = Number(value)
  return Number.isNaN(parsed) ? undefined : parsed
}

const toNumber = (value: number | undefined | null, fallback: number): number =>
  value != null ? Number(value) : fallback

const toNumberOrNull = (value: number | undefined | null, fallback: number | null): number | null =>
  value != null ? Number(value) : fallback

const extractErrors = (error: unknown): { message: string; fields: ApiFieldErrors } => {
  const axiosLike = error as { response?: { data?: { message?: string; errors?: Record<string, string[]> } } }
  const payload = axiosLike.response?.data
  const fields: ApiFieldErrors = {}

  if (payload?.errors) {
    Object.entries(payload.errors).forEach(([key, messages]) => {
      fields[key] = messages.join(' ')
    })
  }

  return {
    message: normalizeError(error),
    fields,
  }
}

export default function VariantSizeModal({
  productId,
  variant,
  onClose,
  onUpdated,
}: VariantSizeModalProps) {
  const [formError, setFormError] = useState<string | null>(null)
  const [fieldErrors, setFieldErrors] = useState<ApiFieldErrors>({})
  const [updateErrors, setUpdateErrors] = useState<Record<number, ApiFieldErrors>>({})
  const createSize = useCreateVariantSize()
  const updateSize = useUpdateVariantSize()
  const deleteSize = useDeleteVariantSize()
  const sizes = useMemo(() => variant.sizes ?? [], [variant.sizes])

  const {
    register,
    handleSubmit,
    reset,
    formState: { isSubmitting },
  } = useForm<SizeFormValues>({
    defaultValues: {
      size_option_id: 0,
      price_cents: 0,
      on_hand: 0,
      reserved: 0,
      warehouse_id: null,
      min_stock_level: 0,
    },
  })

  const handleCreate = async (values: SizeFormValues) => {
    setFormError(null)
    setFieldErrors({})

    try {
      await createSize.mutateAsync({
        productId,
        variantId: variant.id,
        size_option_id: Number(values.size_option_id),
        price_cents: Number(values.price_cents),
        inventory: [
          {
            on_hand: Number(values.on_hand),
            reserved: values.reserved != null ? Number(values.reserved) : 0,
            warehouse_id:
              values.warehouse_id != null
                ? Number(values.warehouse_id)
                : null,
            min_stock_level:
              values.min_stock_level != null ? Number(values.min_stock_level) : 0,
          },
        ],
      });

      reset()
      await onUpdated()
    } catch (error) {
      const { message, fields } = extractErrors(error)
      setFormError(message)
      setFieldErrors(fields)
    }
  }

  const handleUpdate = async (size: VariantSize, values: Partial<SizeFormValues>) => {
    setFormError(null)
    setUpdateErrors((prev) => ({ ...prev, [size.id]: {} }))

    const inventoryPayload = {
      on_hand: toNumber(values.on_hand, size.inventory?.on_hand ?? 0),
      reserved: toNumber(values.reserved, size.inventory?.reserved ?? 0),
      warehouse_id: toNumberOrNull(values.warehouse_id, size.inventory?.warehouse_id ?? null),
      min_stock_level: toNumber(values.min_stock_level, size.inventory?.min_stock_level ?? 0),
    } as NonNullable<VariantSize['inventory']>

    if (size.inventory?.id != null) {
      inventoryPayload.id = size.inventory.id
    }

    const payload = {
      productId,
      variantId: variant.id,
      sizeId: size.id,
      id: size.id,
      size_option_id:
        values.size_option_id != null ? Number(values.size_option_id) : size.size_option_id,
      price_cents:
        values.price_cents != null
          ? Number(values.price_cents)
          : size.price_cents ?? variant.price_cents ?? 0,
      inventory: inventoryPayload,
      is_active: size.is_active ?? true,
    }

    try {
      await updateSize.mutateAsync(payload)
      await onUpdated()
      toast.success('Size güncellendi')
    } catch (error) {
      const { message, fields } = extractErrors(error)
      setFormError(message)
      setUpdateErrors((prev) => ({ ...prev, [size.id]: fields }))
    }
  }

  const handleDelete = async (size: VariantSize) => {
    setFormError(null)

    try {
      await deleteSize.mutateAsync({
        productId,
        variantId: variant.id,
        sizeId: size.id,
      })
      await onUpdated()
    } catch (error) {
      const { message } = extractErrors(error)
      setFormError(message)
    }
  }

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
      <div className="flex w-full max-w-4xl max-h-[90vh] flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">
        <header className="flex items-center justify-between border-b border-gray-200 px-6 py-4">
          <div>
            <h3 className="text-lg font-semibold text-gray-900">Beden &amp; Stok Yönetimi</h3>
            <p className="text-xs text-gray-500">
              Varyant: {variant.color_name ?? '—'} | SKU: {variant.sku ?? '—'}
            </p>
          </div>
          <button onClick={onClose} className="text-sm text-gray-400 hover:text-gray-600">
            Kapat
          </button>
        </header>

        <div className="flex-1 overflow-y-auto px-6 py-4 space-y-6">
          {formError && (
            <div className="rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-600">
              {formError}
            </div>
          )}

          <section className="space-y-4 rounded-2xl border border-gray-200 bg-gray-50 p-4">
            <h4 className="text-sm font-semibold text-gray-800">Yeni Beden Ekle</h4>
            <form onSubmit={handleSubmit(handleCreate)} className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
              <div className="space-y-1">
                <label className="text-xs font-medium text-gray-600">Beden ID</label>
                <input
                  type="number"
                  className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                  {...register('size_option_id', { required: true, valueAsNumber: true })}
                />
                {fieldErrors['size_option_id'] && (
                  <p className="text-xs text-red-600">{fieldErrors['size_option_id']}</p>
                )}
              </div>

              <div className="space-y-1">
                <label className="text-xs font-medium text-gray-600">Fiyat (kuruş)</label>
                <input
                  type="number"
                  className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                  {...register('price_cents', { required: true, valueAsNumber: true })}
                />
                {fieldErrors['price_cents'] && (
                  <p className="text-xs text-red-600">{fieldErrors['price_cents']}</p>
                )}
              </div>

              <div className="space-y-1">
                <label className="text-xs font-medium text-gray-600">On hand</label>
                <input
                  type="number"
                  className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                  {...register('on_hand', { required: true, valueAsNumber: true })}
                />
                {fieldErrors['inventory.on_hand'] && (
                  <p className="text-xs text-red-600">{fieldErrors['inventory.on_hand']}</p>
                )}
              </div>

              <div className="space-y-1">
                <label className="text-xs font-medium text-gray-600">Reserved</label>
                <input
                  type="number"
                  className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                  {...register('reserved', { valueAsNumber: true })}
                />
                {fieldErrors['inventory.reserved'] && (
                  <p className="text-xs text-red-600">{fieldErrors['inventory.reserved']}</p>
                )}
              </div>

              <div className="space-y-1">
                <label className="text-xs font-medium text-gray-600">Depo ID</label>
                <input
                  type="number"
                  className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                  {...register('warehouse_id', { valueAsNumber: true })}
                />
                {fieldErrors['inventory.warehouse_id'] && (
                  <p className="text-xs text-red-600">{fieldErrors['inventory.warehouse_id']}</p>
                )}
              </div>

              <div className="space-y-1">
                <label className="text-xs font-medium text-gray-600">Min stok</label>
                <input
                  type="number"
                  className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                  {...register('min_stock_level', { valueAsNumber: true })}
                />
                {fieldErrors['inventory.min_stock_level'] && (
                  <p className="text-xs text-red-600">{fieldErrors['inventory.min_stock_level']}</p>
                )}
              </div>

              <div className="lg:col-span-3 md:col-span-2 flex justify-end">
                <button
                  type="submit"
                  disabled={isSubmitting || createSize.isPending}
                  className="rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black disabled:cursor-not-allowed disabled:opacity-70"
                >
                  {isSubmitting || createSize.isPending ? 'Ekleniyor...' : 'Ekle'}
                </button>
              </div>
            </form>
          </section>

          <section className="space-y-4">
            <h4 className="text-sm font-semibold text-gray-800">Mevcut Bedenler</h4>

            {!sizes.length && (
              <p className="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-6 text-center text-sm text-gray-500">
                Kayıtlı beden bulunmuyor.
              </p>
            )}

            {sizes.map((size) => {
              const errors = updateErrors[size.id] ?? {}
              return (
                <form
                  key={size.id}
                  onSubmit={(event) => {
                    event.preventDefault()
                    const formData = new FormData(event.currentTarget as HTMLFormElement)
                    handleUpdate(size, {
                      size_option_id: normalize(formData.get('size_option_id')),
                      price_cents: normalize(formData.get('price_cents')),
                      on_hand: normalize(formData.get('on_hand')),
                      reserved: normalize(formData.get('reserved')),
                      warehouse_id: normalize(formData.get('warehouse_id')),
                      min_stock_level: normalize(formData.get('min_stock_level')),
                    })
                  }}
                  className="grid gap-3 rounded-2xl border border-gray-200 bg-white p-3 lg:grid-cols-7 md:grid-cols-2"
                >
                  <div className="space-y-1">
                    <label className="text-[11px] font-medium text-gray-500">Beden ID</label>
                    <input
                      name="size_option_id"
                      defaultValue={size.size_option_id}
                      className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                    />
                    {errors['size_option_id'] && (
                      <p className="text-xs text-red-600">{errors['size_option_id']}</p>
                    )}
                  </div>

                  <div className="space-y-1">
                    <label className="text-[11px] font-medium text-gray-500">Fiyat (kuruş)</label>
                    <input
                      name="price_cents"
                      defaultValue={size.price_cents ?? 0}
                      className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                    />
                    {errors['price_cents'] && (
                      <p className="text-xs text-red-600">{errors['price_cents']}</p>
                    )}
                  </div>

                  <div className="space-y-1">
                    <label className="text-[11px] font-medium text-gray-500">On hand</label>
                    <input
                      name="on_hand"
                      defaultValue={size.inventory?.on_hand ?? 0}
                      className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                    />
                    {errors['inventory.on_hand'] && (
                      <p className="text-xs text-red-600">{errors['inventory.on_hand']}</p>
                    )}
                  </div>

                  <div className="space-y-1">
                    <label className="text-[11px] font-medium text-gray-500">Reserved</label>
                    <input
                      name="reserved"
                      defaultValue={size.inventory?.reserved ?? 0}
                      className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                    />
                    {errors['inventory.reserved'] && (
                      <p className="text-xs text-red-600">{errors['inventory.reserved']}</p>
                    )}
                  </div>

                  <div className="space-y-1">
                    <label className="text-[11px] font-medium text-gray-500">Depo ID</label>
                    <input
                      name="warehouse_id"
                      defaultValue={size.inventory?.warehouse_id ?? ''}
                      className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                    />
                    {errors['inventory.warehouse_id'] && (
                      <p className="text-xs text-red-600">{errors['inventory.warehouse_id']}</p>
                    )}
                  </div>

                  <div className="space-y-1">
                    <label className="text-[11px] font-medium text-gray-500">Min stok</label>
                    <input
                      name="min_stock_level"
                      defaultValue={size.inventory?.min_stock_level ?? 0}
                      className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
                    />
                    {errors['inventory.min_stock_level'] && (
                      <p className="text-xs text-red-600">{errors['inventory.min_stock_level']}</p>
                    )}
                  </div>

                  <div className="flex items-end gap-2">
                    <button
                      type="submit"
                      disabled={updateSize.isPending}
                      className="w-full rounded-xl bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-black disabled:cursor-not-allowed disabled:opacity-70"
                    >
                      Kaydet
                    </button>
                    <button
                      type="button"
                      disabled={deleteSize.isPending}
                      onClick={() => handleDelete(size)}
                      className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm font-medium text-gray-600 hover:border-gray-900 hover:text-gray-900 disabled:cursor-not-allowed disabled:opacity-70"
                    >
                      Sil
                    </button>
                  </div>
                </form>
              )
            })}
          </section>
        </div>
      </div>
    </div>
  )
}
