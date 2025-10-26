'use client'

import type { ProductVariant } from '@/types/seller/product'

type VariantRowActionsProps = {
  variant: ProductVariant
  onEdit: (variant: ProductVariant) => void
  onManageImages: (variant: ProductVariant) => void
  onManageSizes: (variant: ProductVariant) => void
  onDelete: (variant: ProductVariant) => void
}

export default function VariantRowActions({
  variant,
  onEdit,
  onManageImages,
  onManageSizes,
  onDelete,
}: VariantRowActionsProps) {
  return (
    <div className="flex flex-wrap justify-end gap-2">
      <button
        onClick={() => onEdit(variant)}
        className="cursor-pointer rounded-xl border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100"
      >
        Düzenle
      </button>
      <button
        onClick={() => onManageImages(variant)}
        className="cursor-pointer rounded-xl border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100"
      >
        Görseller
      </button>
      <button
        onClick={() => onManageSizes(variant)}
        className="cursor-pointer rounded-xl border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100"
      >
        Bedenler
      </button>
      <button
        onClick={() => onDelete(variant)}
        className="cursor-pointer rounded-xl bg-red-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-600"
      >
        Sil
      </button>
    </div>
  )
}
