'use client'

import type { Product } from '@/types/seller/product'
import StatusBadge from '@/components/ui/StatusBadge'

type ProductCardProps = {
  product: Product
  onView: (id: number) => void
  onEdit: (product: Product) => void
  onDelete: (product: Product) => void
}

export default function ProductCard({
  product,
  onView,
  onEdit,
  onDelete,
}: ProductCardProps) {

  return (
    <article
      className="flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md"
    >
      <header className="mb-4">
        <div className="flex items-start justify-between gap-3">
          <div className="flex-1">
            <h2 className="line-clamp-2 text-lg font-semibold text-gray-900">
              {product.title}
            </h2>
            <p className="mt-1 line-clamp-3 text-sm text-gray-500">
              {product.description || 'Açıklama belirtilmemiş.'}
            </p>
          </div>
          <StatusBadge active={product.is_published} />
          <p className="text-xs text-gray-500">id: {product.id}</p>
        </div>
      </header>

      <dl className="grid grid-cols-2 gap-2 text-xs text-gray-500">
        <div>
          <dt className="font-medium text-gray-700">Kategori</dt>
          <dd>{product.category?.title ?? 'Belirtilmemiş'}</dd>
        </div>
        <div>
          <dt className="font-medium text-gray-700">Varyant</dt>
          <dd>{product.variants?.length ?? 0} adet</dd>
        </div>
      </dl>

      <div className="mt-auto flex gap-2 pt-4">
        <button
          onClick={() => onView(product.id)}
          className="flex-1 cursor-pointer rounded-xl border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100"
        >
          İncele
        </button>
        <button
          onClick={() => onEdit(product)}
          className="flex-1 cursor-pointer rounded-xl bg-gray-900 px-3 py-2 text-sm font-medium text-white transition hover:bg-black"
        >
          Düzenle
        </button>
        <button
          onClick={() => onDelete(product)}
          className="flex-1 cursor-pointer rounded-xl bg-red-500 px-3 py-2 text-sm font-medium text-white transition hover:bg-red-600"
        >
          Sil
        </button>
      </div>
    </article>
  )
}
