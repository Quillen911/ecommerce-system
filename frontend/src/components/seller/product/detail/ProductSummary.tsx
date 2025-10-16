'use client'

import StatusBadge from '@/components/ui/StatusBadge'
import type { Product } from '@/types/seller/product'

type ProductSummaryProps = {
  product: Product
}

export default function ProductSummary({ product }: ProductSummaryProps) {
  const primaryVariant = product.variants?.[0]
  const primaryImage = primaryVariant?.images?.[0]?.image_url ?? null

  return (
    <section className="grid gap-6 rounded-3xl bg-white p-6 shadow-sm lg:grid-cols-[2fr,1fr]">
      <div className="space-y-4">
        <div className="flex items-center justify-between gap-3">
          <h1 className="text-3xl font-bold text-gray-900">{product.title}</h1>
          <StatusBadge active={product.is_published} />
        </div>

        <p className="text-sm text-gray-600">{product.description || 'Açıklama bulunmuyor.'}</p>

        <dl className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          <div>
            <dt className="text-xs font-medium uppercase tracking-wider text-gray-400">
              Kategori
            </dt>
            <dd className="text-sm text-gray-700">
              {product.category?.title ?? 'Belirtilmemiş'}
            </dd>
          </div>

          <div>
            <dt className="text-xs font-medium uppercase tracking-wider text-gray-400">
              Varyant Sayısı
            </dt>
            <dd className="text-sm text-gray-700">{product.variants?.length ?? 0}</dd>
          </div>

          <div>
            <dt className="text-xs font-medium uppercase tracking-wider text-gray-400">
              Oluşturulma
            </dt>
            <dd className="text-sm text-gray-700">
              {new Date(product.created_at).toLocaleDateString('tr-TR')}
            </dd>
          </div>
        </dl>
      </div>
    </section>
  )
}
