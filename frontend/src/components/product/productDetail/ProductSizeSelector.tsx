"use client"

import { useMemo, useState } from "react"
import type { Product, ProductVariant } from "@/types/seller/product"

interface ProductSizeSelectorProps {
  product: Product
  variants: ProductVariant[]
  onSizeSelect: (variantSizeId: number) => Promise<void> | void
}

type SizeOption = {
  variantId: number
  variantSizeId: number
  sizeOptionId: number
  label: string
  slug: string
  available: boolean
  availableCount: number
}

const LOW_STOCK_THRESHOLD = 5

export default function ProductSizeSelector({
  product,
  variants,
  onSizeSelect,
}: ProductSizeSelectorProps) {
  const [selectedSizeId, setSelectedSizeId] = useState<number | null>(null)
  const [isSubmitting, setIsSubmitting] = useState(false)

  const sizeOptions: SizeOption[] = useMemo(
    () =>
      variants.flatMap((variant) =>
        variant.sizes.map((size) => {
          const availableCount = size.inventory?.available ?? 0
          return {
            variantId: variant.id,
            variantSizeId: size.id,
            sizeOptionId: size.size_option.id,
            label: size.size_option.value,
            slug: size.size_option.slug,
            available: availableCount > 0,
            availableCount,
          }
        }),
      ),
    [variants],
  )

  const uniqueSizes = useMemo(
    () =>
      Array.from(
        new Map(
          sizeOptions
            .sort((a, b) => Number(b.available) - Number(a.available))
            .map((item) => [item.slug, item]),
        ).values(),
      ),
    [sizeOptions],
  )

  const selectedSize = useMemo(
    () => uniqueSizes.find((size) => size.variantSizeId === selectedSizeId) ?? null,
    [uniqueSizes, selectedSizeId],
  )

  const handleSizeClick = async (variantSizeId: number) => {
    if (variantSizeId === selectedSizeId) return
    setSelectedSizeId(variantSizeId)
    setIsSubmitting(true)
    try {
      await onSizeSelect?.(variantSizeId)
    } finally {
      setIsSubmitting(false)
    }
  }

  const selectedSizeMessage =
    selectedSize && selectedSize.availableCount > 0
      ? selectedSize.availableCount < LOW_STOCK_THRESHOLD
        ? `Seçilen beden için sadece ${selectedSize.availableCount} adet kaldı.`
        : ""
      : selectedSize
        ? "Seçilen beden stokta yok."
        : ""

  return (
    <div className="product-size-selector mt-4">
      <h2 className="mb-3 text-base sm:text-lg font-semibold text-gray-900">Beden / Yaş Seçenekleri</h2>

      <div className="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2 sm:gap-3">
        {uniqueSizes.map((size) => {
          const isSelected = selectedSizeId === size.variantSizeId
          return (
            <button
              key={size.variantSizeId}
              onClick={() => handleSizeClick(size.variantSizeId)}
              disabled={!size.available || isSubmitting}
              className={`flex items-center justify-center cursor-pointer rounded-md border px-3 py-2 text-sm font-medium transition-all duration-200  
                ${
                  isSelected
                    ? "border-black bg-black text-white"
                    : "border-gray-300 bg-white text-gray-900 hover:border-black hover:bg-gray-100"
                }
                disabled:cursor-not-allowed disabled:opacity-50`}
            >
              {size.label}
            </button>
          )
        })}
      </div>

      {selectedSize && selectedSizeMessage && (
        <p
          className={`mt-3 text-sm font-medium ${
            selectedSize.availableCount < LOW_STOCK_THRESHOLD && selectedSize.available
              ? "text-amber-600"
              : "text-red-600"
          }`}
        >
          {selectedSizeMessage}
        </p>
      )}
    </div>
  )
}
