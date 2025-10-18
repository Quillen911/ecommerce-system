'use client'

import type { ProductVariant } from '@/types/seller/product'
import VariantRowActions from './VariantRowActions'
import ProductImage from '@/components/ui/ProductImage'

type VariantTableProps = {
  productId: number
  variants: ProductVariant[]
  onEdit: (variant: ProductVariant) => void
  onManageSizes: (variant: ProductVariant) => void
  onManageImages: (variant: ProductVariant) => void
  onDelete: (variant: ProductVariant) => void
}

export default function VariantTable({
  productId,
  variants,
  onEdit,
  onManageSizes,
  onManageImages,
  onDelete,
}: VariantTableProps) {
  if (!variants.length) {
    return (
      <div className="rounded-3xl border border-dashed border-gray-200 bg-white p-12 text-center text-sm text-gray-500">
        Bu ürüne ait varyant bulunmuyor. Varyant eklemek için “Ürünü düzenle” sekmesini kullan.
      </div>
    )
  }

  return (
    <section className="rounded-3xl border border-gray-200 bg-white shadow-sm">
      <header className="border-b border-gray-200 px-6 py-4">
        <h3 className="text-lg font-semibold text-gray-900">Varyantlar</h3>
        <p className="text-xs text-gray-500">
          Renkler, fiyatlar, stoklar ve görselleri buradan yönetebilirsin.
        </p>
      </header>

      {/* Masaüstü tablo */}
      <div className="hidden md:block overflow-x-auto">
        <table className="min-w-full divide-y divide-gray-100 text-left text-sm text-gray-600">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 font-medium text-gray-500">Görsel</th>
              <th className="px-6 py-3 font-medium text-gray-500">SKU</th>
              <th className="px-6 py-3 font-medium text-gray-500">Renk</th>
              <th className="px-6 py-3 font-medium text-gray-500">Fiyat</th>
              <th className="px-6 py-3 font-medium text-gray-500">Bedenler</th>
              <th className="px-6 py-3 text-right font-medium text-gray-500">Aksiyonlar</th>
            </tr>
          </thead>

          <tbody className="divide-y divide-gray-100">
            {variants.map((variant) => {
              const primaryImage = variant.images?.[0]?.image ?? null
              const sizesText =
                variant.sizes?.map((s) => `${s.size_option.value} (${s.inventory?.on_hand ?? 0})`).join(', ') ||
                'Beden yok'

              return (
                <tr key={variant.id} className="hover:bg-gray-50">
                  <td className="px-6 py-4">
                    {primaryImage ? (
                      <ProductImage
                        product={primaryImage}
                        className="h-12 w-12 rounded-xl bg-gray-100 object-cover"
                        alt={variant.color_name}
                      />
                    ) : (
                      <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-[10px] text-gray-400">
                        Görsel
                      </div>
                    )}
                  </td>
                  <td className="px-6 py-4 font-medium text-gray-900">{variant.sku ?? '—'}</td>
                  <td className="px-6 py-4">{variant.color_name ?? '—'}</td>
                  <td className="px-6 py-4">
                    ₺{((variant.price_cents ?? 0) / 100).toLocaleString('tr-TR', { minimumFractionDigits: 2 })}
                  </td>
                  <td className="px-6 py-4 text-xs text-gray-500">{sizesText}</td>
                  <td className="px-6 py-4">
                    <VariantRowActions
                      variant={variant}
                      onEdit={onEdit}
                      onManageImages={onManageImages}
                      onManageSizes={onManageSizes}
                      onDelete={onDelete}
                    />
                  </td>
                </tr>
              )
            })}
          </tbody>
        </table>
      </div>

      {/* Mobil görünüm - kart listesi */}
      <div className="block md:hidden divide-y divide-gray-200">
        {variants.map((variant) => {
          const primaryImage = variant.images?.[0]?.image ?? null
          const sizesText =
            variant.sizes?.map((s) => `${s.size_option.value} (${s.inventory?.on_hand ?? 0})`).join(', ') ||
            'Beden yok'

          return (
            <div key={variant.id} className="p-4 flex gap-3 items-center">
              <div className="flex-shrink-0">
                {primaryImage ? (
                  <ProductImage
                    product={primaryImage}
                    className="h-14 w-14 rounded-xl bg-gray-100 object-cover"
                    alt={variant.color_name}
                  />
                ) : (
                  <div className="flex h-14 w-14 items-center justify-center rounded-xl bg-gray-100 text-[10px] text-gray-400">
                    Görsel
                  </div>
                )}
              </div>
              <div className="flex flex-col flex-1">
                <span className="text-sm font-semibold text-gray-900">{variant.color_name ?? '—'}</span>
                <span className="text-xs text-gray-500">{variant.sku ?? '—'}</span>
                <span className="text-xs text-gray-700 mt-1">{sizesText}</span>
                <span className="text-sm font-medium text-gray-900 mt-1">
                  ₺{((variant.price_cents ?? 0) / 100).toLocaleString('tr-TR', { minimumFractionDigits: 2 })}
                </span>
              </div>
              <VariantRowActions
                variant={variant}
                onEdit={onEdit}
                onManageImages={onManageImages}
                onManageSizes={onManageSizes}
                onDelete={onDelete}
              />
            </div>
          )
        })}
      </div>
    </section>
  )
}
