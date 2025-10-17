import { Product, ProductVariant } from "@/types/seller/product"
import { useMemo, useState } from "react"

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
    <div className="product-size-selector">
      <h1 className="mb-3 text-md font-sans font-semibold text-black">
        Yaş Seçenekleri
      </h1>

      <div className="grid grid-cols-3 gap-2 sm:grid-cols-4 lg:grid-cols-3">
        {uniqueSizes.map((size) => {
          const isSelected = selectedSizeId === size.variantSizeId

          return (
            <button
              key={size.variantSizeId}
              onClick={() => handleSizeClick(size.variantSizeId)}
              disabled={!size.available || isSubmitting}
              className={`flex items-center justify-center rounded border py-3 px-10 font-sans text-sm font-semibold transition
                ${
                  isSelected
                    ? "border-black bg-black text-white"
                    : "border-gray-400 bg-white text-black hover:border-black hover:bg-gray-200"
                }
                disabled:cursor-not-allowed disabled:opacity-50`}
            >
              {size.label}
            </button>
          )
        })}
      </div>

      {selectedSize && selectedSizeMessage && (
        <p className="mt-3 text-sm font-medium text-red-600">{selectedSizeMessage}</p>
      )}
    </div>
  )
}
