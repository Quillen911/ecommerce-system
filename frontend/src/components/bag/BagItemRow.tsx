"use client"

import { BagItem } from "@/types/bag"
import  Image from "@/components/ui/ProductImage"

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
    <div className="flex items-center gap-4 p-4 rounded-lg surface border border-color shadow-sm animate-slideInFromLeft">
      <div className="w-20 h-20 flex-shrink-0">
        <Image 
          product={image}
          alt={item.product_title}
          className="!w-full !h-full object-cover rounded"
        />
      </div>
      <div className="flex-1">
        <h2 className="font-semibold line-clamp-2">{item.product_title}</h2>
        <div className="flex items-center gap-2 mt-2">
          <button
            onClick={() => onDecrease(item)}
            className="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 transition disabled:opacity-60"
            disabled={disabled}
          >
            -
          </button>
          <span className="px-3">{item.quantity}</span>
          <button
            onClick={() => onIncrease(item)}
            className="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 transition disabled:opacity-60"
            disabled={disabled}
          >
            +
          </button>
        </div>
      </div>
      <div className="text-right">
        <p className="font-bold">₺{(item.sizes.price_cents / 100).toFixed(2)}</p>
      </div>
      <button
        onClick={() => onRemove(item)}
        className="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition disabled:opacity-60"
        disabled={disabled}
      >
        Kaldır
      </button>
    </div>
  )
}
