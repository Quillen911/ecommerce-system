'use client'

import { useFieldArray, useForm } from 'react-hook-form'
import { useStoreProduct, useUpdateProduct } from '@/hooks/seller/useProductQuery'
import { useMainData } from '@/hooks/useMainQuery'
import { useMySeller } from '@/hooks/seller/useSellerAuthQuery'
import { useState } from 'react'
import { StoreProductRequest } from '@/types/seller/product'
import { useCategory } from '@/hooks/seller/useCategoryQuery'

interface ProductFormProps {
  onSuccess?: () => void
}

export const ProductForm = ({ onSuccess }: ProductFormProps) => {
  const { data: me } = useMySeller()
  const { mutate: storeProduct } = useStoreProduct(me?.id!)
  const { data: mainData } = useMainData()
  const attributes = mainData?.attributes || []
  const attributeOptions = mainData?.attributeOptions || []
  const [parentId, setParentId] = useState<number | null>(null) 
  const { data: childCategories } = useCategory(parentId ?? 0, me?.id!)

  const {
    register,
    handleSubmit,
    control,
    watch,
    formState: { errors, isSubmitting }
  } = useForm<StoreProductRequest>()
  const { fields, append, remove } = useFieldArray({
    control,
    name: 'variants'
  })
  const onSubmit = (data: StoreProductRequest) => {
    const formData = new FormData()
    formData.append("title", data.title)
    formData.append("category_id", String(data.category_id || ""))
    formData.append("description", data.description || "")
    formData.append("meta_description", data.meta_description || "")
    formData.append("list_price", String(data.list_price))
    if (data.images && data.images.length > 0) {
      Array.from(data.images).forEach((file) => {
        formData.append("images[]", file)
      })
    }
    data.variants.forEach((variant, vIndex) => {
      formData.append(`variants[${vIndex}][price]`, String(variant.price))
      formData.append(`variants[${vIndex}][stock_quantity]`, String(variant.stock_quantity))
      if (variant.images && variant.images.length > 0) {
        Array.from(variant.images).forEach((file) => {
          formData.append(`variants[${vIndex}][images][]`, file)
        })
      }
      variant.attributes.forEach((attr, aIndex) => {
        formData.append(`variants[${vIndex}][attributes][${aIndex}][attribute_id]`, String(attr.attribute_id))
        formData.append(`variants[${vIndex}][attributes][${aIndex}][option_id]`, String(attr.option_id))
      })
    })
    storeProduct(formData, {
      onSuccess: () => onSuccess?.(),
      onError: (error) => {
        console.log(error)
      }
    })
  }
  return (
    <form onSubmit={handleSubmit(onSubmit)} className="max-w-3xl mx-auto bg-white shadow-md rounded-xl p-6 space-y-6">
      <h2 className="text-2xl font-bold text-gray-800 mb-4">Yeni Ürün Ekle</h2>

      <div>
        <label className="block text-sm font-medium">Ürün Adı</label>
        <input {...register('title', { required: 'Ürün Adı giriniz' })} className="w-full border rounded-lg p-2 mt-1" placeholder="Ürün Adı" />
        {errors.title && <span className="text-red-500">{errors.title.message}</span>}
      </div>

      <div>
        {/* Üst kategori seçimi */}
        <label className="block text-sm font-medium">Üst Kategori</label>
        <select 
          className="w-full border rounded-lg p-2 mt-1"
          onChange={(e) => setParentId(Number(e.target.value))}
        >
          <option value="">Üst Kategori Seç</option>
          {mainData?.categories
            ?.filter((c: any) => c.parent_id === null)
            .map((category: any) => (
              <option key={category.id} value={category.id}>
                {category.title}
              </option>
            ))}
        </select>

        {/* Alt kategori seçimi */}
        {childCategories?.data?.length && childCategories?.data?.length > 0 && (
          <div className="mt-4">
            <label className="block text-sm font-medium">Alt Kategori</label>
            <select 
              {...register('category_id', { required: 'Kategori seçiniz', valueAsNumber: true })}
              className="w-full border rounded-lg p-2 mt-1"
            >
              <option value="">Alt Kategori Seç</option>
              {childCategories?.data?.map((child: any) => (
                <option key={child.id} value={child.id}>
                  {child.title}
                </option>
              ))}
            </select>
          </div>
        )}
      </div>

      <div>
        <label className="block text-sm font-medium">Ürün Açıklaması</label>
        <textarea {...register('description')} className="w-full border rounded-lg p-2 mt-1" rows={3} />
      </div>

      <div>
        <label className="block text-sm font-medium">Meta Açıklaması</label>
        <textarea {...register('meta_description')} className="w-full border rounded-lg p-2 mt-1" rows={2} />
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div>
          <label className="block text-sm font-medium">Liste Fiyatı</label>
          <input type="number" {...register('list_price', { valueAsNumber: true, required: 'Fiyat giriniz' })} className="w-full border rounded-lg p-2 mt-1" placeholder="₺" />
        </div>
      </div>

      <div>
        <label className="block text-sm font-medium">Ürün Görselleri</label>
        <input type="file" multiple accept="image/*" {...register('images')} className="block mt-1" />
      </div>

      <div>
        <h3 className="text-lg font-semibold mb-2">Varyantlar</h3>

        {fields.map((variant, vIndex) => {
          const selectedAttrId0 = watch(`variants.${vIndex}.attributes.0.attribute_id`)
          const selectedAttrId1 = watch(`variants.${vIndex}.attributes.1.attribute_id`)
          return (
            <div key={variant.id} className="border p-4 rounded-lg mb-4 space-y-3">

              <div className="grid grid-cols-3 gap-4 items-center">
                <div>
                  <label className="block text-sm font-medium">Fiyat</label>
                  <input type="number" {...register(`variants.${vIndex}.price`, { valueAsNumber: true, required: 'Fiyat gerekli' })} className="border p-2 rounded" />
                </div>
                <div>
                  <label className="block text-sm font-medium">Stok</label>
                  <input type="number" {...register(`variants.${vIndex}.stock_quantity`, { valueAsNumber: true, required: 'Stok gerekli' })}className="border p-2 rounded" />
                </div>
              </div>

              <div>
                <label className="block text-sm font-medium">Varyant Görselleri</label>
                <input type="file" multiple accept="image/*" {...register(`variants.${vIndex}.images`)} className="block mt-1" />
              </div>

              <div>
                <label className="block text-sm font-medium">Özellikler</label>

                <div className="space-y-2">
                  <div className="grid grid-cols-3 gap-2">
                    <select {...register(`variants.${vIndex}.attributes.0.attribute_id`, { valueAsNumber: true, required: 'Özellik gerekli' })} className="border p-2 rounded">
                      <option value="">Özellik Seç</option>
                      {attributes.map((attr: any) => (
                        <option key={attr.id} value={attr.id}>{attr.name}</option>
                      ))}
                    </select>
                    <select {...register(`variants.${vIndex}.attributes.0.option_id`, { valueAsNumber: true, required: 'Seçenek gerekli' })} className="border p-2 rounded">
                      <option value="">Seçenek Seç</option>
                      {attributeOptions.filter((opt: any) => opt.attribute_id === selectedAttrId0).map((opt: any) => (
                        <option key={opt.id} value={opt.id}>{opt.value}</option>
                      ))}
                    </select>
                  </div>

                  <div className="grid grid-cols-3 gap-2">
                    <select {...register(`variants.${vIndex}.attributes.1.attribute_id`, { valueAsNumber: true, required: 'Özellik gerekli' })} className="border p-2 rounded">
                      <option value="">Özellik Seç</option>
                      {attributes.map((attr: any) => (
                        <option key={attr.id} value={attr.id}>{attr.name}</option>
                      ))}
                    </select>
                    <select {...register(`variants.${vIndex}.attributes.1.option_id`, { valueAsNumber: true, required: 'Seçenek gerekli' })} className="border p-2 rounded">
                      <option value="">Seçenek Seç</option>
                      {attributeOptions.filter((opt: any) => opt.attribute_id === selectedAttrId1).map((opt: any) => (
                        <option key={opt.id} value={opt.id}>{opt.value}</option>
                      ))}
                    </select>
                  </div>
                </div>
              </div>

              <button type="button" onClick={() => remove(vIndex)} className="text-red-600 hover:underline mt-2">Varyant Sil</button>
            </div>
          )
        })}
        <button type="button" onClick={() => append({ price: 0, stock_quantity: 0, images: [], attributes: [{ attribute_id: 0, option_id: 0 }, { attribute_id: 0, option_id: 0 }] })} className="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">+ Varyant Ekle</button>
      </div>
      <button type="submit" disabled={isSubmitting} className="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
        {isSubmitting ? 'Ürün kaydediliyor...' : 'Ürünü kaydet'}
      </button>
    </form>
  )
}
