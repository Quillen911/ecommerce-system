"use client"

import { Product, ProductVariant } from "@/types/main"
import { useMainData } from "@/hooks/useMainQuery"
type ProductCardProps = {
  product: Product
  variant: ProductVariant
}

export default function ProductCard({ product, variant }: ProductCardProps) {
  const { data: mainData, isLoading, error } = useMainData()
  const category = mainData?.categories.find((c) => c.id === product.category_id)
  const parentCategory = mainData?.categories.find((c) => c.id === category?.parent_id)

  const primaryImage =
    variant.images.find((img) => img.is_primary)?.image ||
    variant.images[0]?.image ||
    "/images/no-image.png"

    return (
      <div className="p-4 hover:shadow-lg transition cursor-pointer">
        <div className="w-full h-100 overflow-hidden flex items-center justify-center">
          <img
            src={primaryImage}
            alt={product.title}
            className="max-h-full max-w-full object-contain transition-all duration-500"
          />
        </div>
    
        <div className="mt-3 space-y-1">
          {variant.is_popular && (
            <span className="text-red-600 text-sm font-semibold">En Çok Satan</span>
          )}
          <h3 className="font-semibold line-clamp-2">{product.title}</h3>
          <p className="text-sm text-gray-500">{parentCategory?.title} {category?.title}</p>
          <p className="font-bold text-lg">{variant.price.toLocaleString()} ₺</p>
        </div>
      </div>
    )
    
}
