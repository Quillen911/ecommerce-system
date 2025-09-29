"use client"

import { Product, ProductVariant } from "@/types/main"

type ProductCardProps = {
  product: Product
  variant: ProductVariant
}

export default function ProductCard({ product, variant }: ProductCardProps) {
  const primaryImage =
    variant.images.find((img) => img.is_primary)?.image ||
    variant.images[0]?.image ||
    "/images/no-image.png"

  return (
    <div className="p-4 hover:shadow-lg transition cursor-pointer">
      {/* Ürün resmi */}
      <img
        src={primaryImage}
        alt={product.title}
        className="w-full h-125 object-cover rounded-md"
      />

      {/* Ürün bilgileri */}
      <div className="mt-3 space-y-1">
        {variant.is_popular && (
          <span className="text-red-600 text-sm font-semibold">En Çok Satan</span>
        )}
        <h3 className="font-semibold line-clamp-2">{product.title}</h3>
        <p className="text-sm text-gray-500">{product.category.title}</p>
        <p className="font-bold text-lg">{variant.price.toLocaleString()} ₺</p>
      </div>
    </div>
  )
}
