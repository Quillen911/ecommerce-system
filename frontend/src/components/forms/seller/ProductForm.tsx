'use client'

import { useEffect, useMemo, useState } from 'react'
import {
  useForm,
  useFieldArray,
  type Control,
  type FieldErrors,
} from 'react-hook-form'
import { toast } from 'sonner'
import {
  useCreateProduct,
  useUpdateProduct,
  normalizeError,
} from '@/hooks/seller/useProductQuery'
import type { Product, ProductVariant } from '@/types/seller/product'
import type { UpdateProductRequest } from '@/types/seller/product'

type InventoryForm = {
  id?: number | null
  on_hand: number
  reserved?: number | null
  warehouse_id?: number | null
  min_stock_level?: number | null
}

type VariantSizeForm = {
  id?: number | null
  size_option_id: number
  price_lira?: number | null
  inventory: InventoryForm
}

type CategoryOption = {
  id: number
  title: string
}

const CATEGORY_OPTIONS: CategoryOption[] = [
  { id: 4, title: 'Erkek Çocuk Jean' },
  { id: 5, title: 'Erkek Çocuk Keten' },
  { id: 6, title: 'Erkek Çocuk Eşofman Takım' },
  { id: 7, title: 'Kız Çocuk Jean' },
  { id: 8, title: 'Kız Çocuk Keten' },
  { id: 9, title: 'Kız Çocuk Eşofman Takım' },
]

type VariantForm = {
  id?: number | null
  color_name: string
  color_code?: string | null
  price_lira: number
  is_popular?: boolean
  images: FileList | null
  sizes: VariantSizeForm[]
}

type ProductFormValues = {
  title: string
  category_id?: number | null
  description?: string | null
  meta_title?: string | null
  meta_description?: string | null
  variants: VariantForm[]
}

type ProductFormProps = {
  sellerId?: number
  product?: Product
  onSuccess?: () => void
  formId?: string
  hideFooter?: boolean
}

type VariantFieldsProps = {
  index: number
  control: Control<ProductFormValues>
  register: ReturnType<typeof useForm<ProductFormValues>>['register']
  setValue: ReturnType<typeof useForm<ProductFormValues>>['setValue']
  watch: ReturnType<typeof useForm<ProductFormValues>>['watch']
  removeVariant: (index: number) => void
  errors: FieldErrors<ProductFormValues>
}

const createDefaultInventory = (): InventoryForm => ({
  id: null,
  on_hand: 0,
  reserved: 0,
  warehouse_id: null,
  min_stock_level: 0,
})

const createDefaultVariantSize = (): VariantSizeForm => ({
  id: null,
  size_option_id: 0,
  price_lira: 0,
  inventory: createDefaultInventory(),
})

const createDefaultVariant = (): VariantForm => ({
  id: null,
  color_name: '',
  color_code: '#000000',
  price_lira: 0,
  is_popular: false,
  images: null,
  sizes: [createDefaultVariantSize()],
})

const defaultValues: ProductFormValues = {
  title: '',
  category_id: undefined,
  description: '',
  meta_title: '',
  meta_description: '',
  variants: [createDefaultVariant()],
}

const toCents = (value?: number | null) =>
  value != null ? Math.round(Number(value) * 100) : null

const fromCents = (value?: number | null) =>
  value != null ? Number(value) / 100 : 0

const buildFormData = (values: ProductFormValues) => {
  const formData = new FormData()

  formData.append('title', values.title)
  if (values.category_id != null) formData.append('category_id', String(values.category_id))
  if (values.description) formData.append('description', values.description)
  if (values.meta_title) formData.append('meta_title', values.meta_title)
  if (values.meta_description) formData.append('meta_description', values.meta_description)

  values.variants.forEach((variant, vIndex) => {
    if (variant.id != null) {
      formData.append(`variants[${vIndex}][id]`, String(variant.id))
    }

    formData.append(`variants[${vIndex}][color_name]`, variant.color_name)
    if (variant.color_code) {
      formData.append(`variants[${vIndex}][color_code]`, variant.color_code)
    }

    formData.append(
      `variants[${vIndex}][price_cents]`,
      String(toCents(variant.price_lira) ?? 0),
    )
    formData.append(`variants[${vIndex}][is_popular]`, variant.is_popular ? '1' : '0')

    Array.from(variant.images ?? []).forEach((file) => {
      formData.append(`variants[${vIndex}][images][]`, file)
    })

    variant.sizes.forEach((size, sIndex) => {
      if (size.id != null) {
        formData.append(`variants[${vIndex}][sizes][${sIndex}][id]`, String(size.id))
      }

      formData.append(
        `variants[${vIndex}][sizes][${sIndex}][size_option_id]`,
        String(size.size_option_id),
      )

      const cents = toCents(size.price_lira)
      if (cents != null) {
        formData.append(
          `variants[${vIndex}][sizes][${sIndex}][price_cents]`,
          String(cents),
        )
      }

      if (size.inventory.id != null) {
        formData.append(
          `variants[${vIndex}][sizes][${sIndex}][inventory][id]`,
          String(size.inventory.id),
        )
      }

      formData.append(
        `variants[${vIndex}][sizes][${sIndex}][inventory][on_hand]`,
        String(Number(size.inventory.on_hand)),
      )

      if (size.inventory.reserved != null) {
        formData.append(
          `variants[${vIndex}][sizes][${sIndex}][inventory][reserved]`,
          String(Number(size.inventory.reserved)),
        )
      }

      if (size.inventory.warehouse_id != null) {
        formData.append(
          `variants[${vIndex}][sizes][${sIndex}][inventory][warehouse_id]`,
          String(size.inventory.warehouse_id),
        )
      }

      if (size.inventory.min_stock_level != null) {
        formData.append(
          `variants[${vIndex}][sizes][${sIndex}][inventory][min_stock_level]`,
          String(size.inventory.min_stock_level),
        )
      }
    })
  })

  return formData
}

const VariantFields = ({
  index,
  control,
  register,
  setValue,
  watch,
  removeVariant,
  errors,
}: VariantFieldsProps) => {
  const sizesArray = useFieldArray({
    control,
    name: `variants.${index}.sizes`,
  })

  const files = watch(`variants.${index}.images`)

  const handleFileChange: React.ChangeEventHandler<HTMLInputElement> = (event) => {
    setValue(`variants.${index}.images`, event.target.files, { shouldValidate: true })
  }

  return (
    <div className="space-y-4 rounded-2xl border border-gray-200 p-4">
      <header className="flex items-center justify-between">
        <span className="text-sm font-medium text-gray-700">Varyant #{index + 1}</span>
        <button
          type="button"
          onClick={() => removeVariant(index)}
          className="text-xs text-red-500 hover:underline"
        >
          Varyantı kaldır
        </button>
      </header>

      <input type="hidden" {...register(`variants.${index}.id` as const)} />

      <div className="grid gap-4 md:grid-cols-2">
        <div className="space-y-2">
          <label className="block text-sm font-medium text-gray-700">Renk Adı</label>
          <input
            type="text"
            className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
            {...register(`variants.${index}.color_name` as const, {
              required: 'Renk adı zorunludur.',
            })}
          />
          {errors.variants?.[index]?.color_name && (
            <p className="text-xs text-red-500">{errors.variants[index]?.color_name?.message}</p>
          )}
        </div>

        <div className="space-y-2">
          <label className="block text-sm font-medium text-gray-700">Fiyat (TL)</label>
          <input
            type="number"
            min={0}
            step="0.01"
            className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
            {...register(`variants.${index}.price_lira` as const, {
              required: 'Fiyat zorunludur.',
              valueAsNumber: true,
              min: 0,
            })}
          />
        </div>
      </div>

      <div className="space-y-2">
        <label className="block text-sm font-medium text-gray-700">
          Görseller (minimum 1 dosya)
        </label>
        <input
          type="file"
          accept="image/*"
          multiple
          onChange={handleFileChange}
          className="w-full rounded-xl border border-dashed border-gray-300 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-none file:bg-gray-900 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-black"
        />
        {!files?.length && errors.variants?.[index]?.images && (
          <p className="text-xs text-red-500">En az bir görsel yüklenmelidir.</p>
        )}
        {files?.length ? (
          <ul className="text-xs text-gray-600">
            {Array.from(files).map((file) => (
              <li key={file.name}>{file.name}</li>
            ))}
          </ul>
        ) : null}
      </div>

      <div className="space-y-3">
        <header className="flex items-center justify-between">
          <h4 className="text-sm font-semibold text-gray-800">Beden & Stok Bilgileri</h4>
          <button
            type="button"
            onClick={() => sizesArray.append(createDefaultVariantSize())}
            className="text-xs font-medium text-gray-600 hover:underline"
          >
            + Beden ekle
          </button>
        </header>

        {sizesArray.fields.map((field, sizeIndex) => (
          <div
            key={field.id}
            className="grid gap-3 rounded-xl border border-gray-200 p-3 md:grid-cols-5"
          >
            <input
              type="hidden"
              {...register(`variants.${index}.sizes.${sizeIndex}.id` as const)}
            />
            <input
              type="hidden"
              {...register(`variants.${index}.sizes.${sizeIndex}.inventory.id` as const)}
            />

            <div className="space-y-1">
              <label className="block text-xs font-medium text-gray-600">Beden ID</label>
              <input
                type="number"
                className="w-full rounded-lg border border-gray-200 px-2 py-2 text-sm focus:border-gray-900 focus:outline-none"
                {...register(
                  `variants.${index}.sizes.${sizeIndex}.size_option_id` as const,
                  { required: true, valueAsNumber: true },
                )}
              />
            </div>

            <div className="space-y-1">
              <label className="block text-xs font-medium text-gray-600">Fiyat (TL)</label>
              <input
                type="number"
                step="0.01"
                className="w-full rounded-lg border border-gray-200 px-2 py-2 text-sm focus:border-gray-900 focus:outline-none"
                {...register(
                  `variants.${index}.sizes.${sizeIndex}.price_lira` as const,
                  { valueAsNumber: true },
                )}
              />
            </div>

            <div className="space-y-1">
              <label className="block text-xs font-medium text-gray-600">Stok (on hand)</label>
              <input
                type="number"
                className="w-full rounded-lg border border-gray-200 px-2 py-2 text-sm focus:border-gray-900 focus:outline-none"
                {...register(
                  `variants.${index}.sizes.${sizeIndex}.inventory.on_hand` as const,
                  { required: true, valueAsNumber: true },
                )}
              />
            </div>

            <div className="space-y-1">
              <label className="block text-xs font-medium text-gray-600">Depo ID</label>
              <input
                type="number"
                className="w-full rounded-lg border border-gray-200 px-2 py-2 text-sm focus:border-gray-900 focus:outline-none"
                {...register(
                  `variants.${index}.sizes.${sizeIndex}.inventory.warehouse_id` as const,
                  { valueAsNumber: true },
                )}
              />
            </div>

            <div className="space-y-1">
              <label className="block text-xs font-medium text-gray-600">Min stok</label>
              <input
                type="number"
                className="w-full rounded-lg border border-gray-200 px-2 py-2 text-sm focus;border-gray-900 focus:outline-none"
                {...register(
                  `variants.${index}.sizes.${sizeIndex}.inventory.min_stock_level` as const,
                  { valueAsNumber: true },
                )}
              />
            </div>

            {sizesArray.fields.length > 1 && (
              <button
                type="button"
                onClick={() => sizesArray.remove(sizeIndex)}
                className="col-span-full text-right text-xs text-red-500 hover:underline"
              >
                Bedeni kaldır
              </button>
            )}
          </div>
        ))}
      </div>
    </div>
  )
}

export function ProductForm({
  sellerId,
  product,
  onSuccess,
  formId = 'product-form',
  hideFooter = false,
}: ProductFormProps) {
  const createMutation = useCreateProduct(sellerId)
  const updateMutation = useUpdateProduct(product?.id ?? 0, sellerId)
  const [formError, setFormError] = useState<string | null>(null)

  const initialValues = useMemo<ProductFormValues>(() => {
    if (!product) return defaultValues

    const mapSizes = (variantSizes: ProductVariant['sizes']): VariantSizeForm[] =>
      (variantSizes ?? []).map((size) => ({
        id: size.id ?? null,
        size_option_id: size.size_option_id,
        price_lira: fromCents(size.price_cents ?? 0),
        inventory: {
          id: size.inventory?.id ?? null,
          on_hand: size.inventory?.on_hand ?? 0,
          reserved: size.inventory?.reserved ?? 0,
          warehouse_id: size.inventory?.warehouse_id ?? null,
          min_stock_level: size.inventory?.min_stock_level ?? 0,
        },
      }))

    return {
      title: product.title,
      category_id: product.category?.id ?? undefined,
      description: product.description ?? '',
      meta_title: product.meta_title ?? '',
      meta_description: product.meta_description ?? '',
      variants:
        product.variants?.length
          ? product.variants.map<VariantForm>((variant) => ({
              id: variant.id ?? null,
              color_name: variant.color_name ?? '',
              color_code: variant.color_code ?? '#000000',
              price_lira: fromCents(variant.price_cents ?? 0),
              is_popular: variant.is_popular ?? false,
              images: null,
              sizes: mapSizes(variant.sizes).length
                ? mapSizes(variant.sizes)
                : [createDefaultVariantSize()],
            }))
          : [createDefaultVariant()],
    }
  }, [product])

  const {
    register,
    handleSubmit,
    control,
    setValue,
    watch,
    formState: { errors, isSubmitting },
    reset,
  } = useForm<ProductFormValues>({
    defaultValues: initialValues,
  })

  const variantsFieldArray = useFieldArray({
    control,
    name: 'variants',
  })

  useEffect(() => {
    reset(initialValues)
  }, [initialValues, reset])

  const handleCreate = async (formValues: ProductFormValues) => {
    setFormError(null)

    const formData = buildFormData(formValues)

    try {
      await createMutation.mutateAsync(formData)
      toast.success('Ürün oluşturuldu')
      reset(defaultValues)
      onSuccess?.()
    } catch (error) {
      const message = normalizeError(error)
      setFormError(message)
    }
  }

  const handleUpdate = async (formValues: ProductFormValues) => {
    setFormError(null)

    const payload: UpdateProductRequest = {
      title: formValues.title,
      category_id: formValues.category_id,
      description: formValues.description,
      meta_title: formValues.meta_title,
      meta_description: formValues.meta_description,
    }

    try {
      await updateMutation.mutateAsync(payload)
      toast.success('Ürün güncellendi')
      onSuccess?.()
    } catch (error) {
      const message = normalizeError(error)
      setFormError(message)
    }
  }

  const onSubmit = async (formValues: ProductFormValues) => {
    if (product) {
      await handleUpdate(formValues)
      return
    }

    await handleCreate(formValues)
  }

  const isBusy =
    isSubmitting || createMutation.isPending || updateMutation.isPending

  return (
    <form id={formId} onSubmit={handleSubmit(onSubmit)} className="space-y-8">
      {formError && (
        <div className="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
          {formError}
        </div>
      )}

      <section className="space-y-4">
        <h3 className="text-base font-semibold text-gray-900">Genel Bilgiler</h3>
        <div className="space-y-2">
          <label className="block text-sm font-medium text-gray-700">Ürün Başlığı</label>
          <input
            type="text"
            placeholder="Örn. Siyah Basic T-Shirt"
            className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
            {...register('title', { required: 'Başlık zorunludur.' })}
          />
          {errors.title && (
            <p className="text-xs text-red-500">{errors.title.message}</p>
          )}
        </div>
        <div className="space-y-2">
          <label className="block text-sm font-medium text-gray-700">Kategori</label>
          <select
            className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
            defaultValue=""
            {...register('category_id', {
              required: 'Kategori seçimi zorunludur.',
              valueAsNumber: true,
            })}
          >
            <option value="" disabled>
              Kategori seçin
            </option>
            {CATEGORY_OPTIONS.map((category) => (
              <option key={category.id} value={category.id}>
                {category.title}
              </option>
            ))}
          </select>
          {errors.category_id && (
            <p className="text-xs text-red-500">{errors.category_id.message}</p>
          )}
        </div>

        <div className="space-y-2">
          <label className="block text-sm font-medium text-gray-700">Açıklama</label>
          <textarea
            rows={4}
            placeholder="Ürün detaylarını ekleyin..."
            className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
            {...register('description')}
          />
        </div>

        <div className="grid gap-4 md:grid-cols-2">
          <div className="space-y-2">
            <label className="block text-sm font-medium text-gray-700">Meta Başlık</label>
            <input
              type="text"
              className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
              {...register('meta_title')}
            />
          </div>
          <div className="space-y-2">
            <label className="block text-sm font-medium text-gray-700">Meta Açıklama</label>
            <input
              type="text"
              className="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none"
              {...register('meta_description')}
            />
          </div>
        </div>
      </section>

      {!product && (
        <section className="space-y-4">
          <header className="flex items-center justify_between">
            <h3 className="text-base font-semibold text-gray-900">Varyantlar</h3>
            <button
              type="button"
              onClick={() => variantsFieldArray.append(createDefaultVariant())}
              className="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-100"
            >
              + Varyant Ekle
            </button>
          </header>

          <div className="space-y-6">
            {variantsFieldArray.fields.map((variantField, index) => (
              <VariantFields
                key={variantField.id}
                index={index}
                control={control}
                register={register}
                setValue={setValue}
                watch={watch}
                removeVariant={variantsFieldArray.remove}
                errors={errors}
              />
            ))}
          </div>
        </section>
      )}

      {!hideFooter && (
        <footer className="flex items-center justify-end gap-3 border-t border-gray-200 pt-4">
          <button
            type="submit"
            disabled={isBusy}
            className="rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-black disabled:cursor-not-allowed disabled:opacity-70"
          >
            {isBusy ? 'Kaydediliyor...' : product ? 'Güncelle' : 'Kaydet'}
          </button>
        </footer>
      )}
    </form>
  )
}
