"use client"

import { BagItem } from "@/types/bag"
import Image from "@/components/ui/ProductImage"

interface BagItemRowProps {
  item: BagItem
  onIncrease: (item: BagItem) => void
  onDecrease: (item: BagItem) => void
  onRemove: (item: BagItem) => void
  disabled?: boolean
}

export function BagItemRow({
  item,
  onIncrease,
  onDecrease,
  onRemove,
  disabled,
}: BagItemRowProps) {
  const image =
    item?.sizes?.product_variant?.variant_images?.find((img) => img.is_primary)?.image_url ??
    item?.sizes?.product_variant?.variant_images?.[0]?.image_url

  return (
    <div className="flex flex-col sm:flex-row items-center sm:items-start gap-4 p-4 rounded-lg surface border border-color shadow-sm animate-slideInFromLeft">
      <div className="w-24 h-24 sm:w-20 sm:h-20 flex-shrink-0">
        <Image
          product={image}
          alt={item.product_title}
          className="!w-full !h-full object-cover rounded"
        />
      </div>

      <div className="flex-1 w-full text-center sm:text-left">
        <h2 className="font-semibold line-clamp-2">{item.product_title}</h2>

        <div className="flex items-center justify-center sm:justify-start gap-3 mt-3">
          <button
            onClick={() => onDecrease(item)}
            className="px-3 py-1.5 bg-gray-200 rounded hover:bg-gray-300 transition disabled:opacity-60"
            disabled={disabled}
          >
            -
          </button>
          <span className="px-3">{item.quantity}</span>
          <button
            onClick={() => onIncrease(item)}
            className="px-3 py-1.5 bg-gray-200 rounded hover:bg-gray-300 transition disabled:opacity-60"
            disabled={disabled}
          >
            +
          </button>
        </div>
      </div>

      <div className="flex flex-col items-center sm:items-end gap-2 sm:gap-0 mt-3 sm:mt-0 text-center sm:text-right">
        <p className="font-bold text-base">₺{(item.unit_price_cents / 100).toFixed(2)}</p>
        <button
          onClick={() => onRemove(item)}
          className="px-3 py-1.5 bg-red-500 text-white rounded hover:bg-red-600 transition disabled:opacity-60 text-sm"
          disabled={disabled}
        >
          Kaldır
        </button>
      </div>
    </div>
  )
}
