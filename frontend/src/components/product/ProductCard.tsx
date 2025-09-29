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

  const colorSlugs = Array.from(
    new Set(
      product.variants
        .map(v => v.attributes.find(a => a.code === "color")?.slug)
        .filter(Boolean) as string[]
    )
  )
  const colorMap: Record<string, string> = {
    siyah: "bg-gray-800",
    kirmizi: "bg-red-500",
    yesil: "bg-green-400",
    mavi: "bg-blue-400",
    beyaz: "bg-gray-100 border",
  }
  
  
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
          <div className="flex gap-2 mt-2">
            {colorSlugs.map(slug => (
              <span
                key={slug}
                className={`w-6 h-6 rounded-full border-2 ${colorMap[slug] || "bg-gray-300"}`}
                title={slug}
              ></span>
            ))}
          </div>
          <p className="text-sm text-gray-500">{parentCategory?.title} {category?.title}</p>
          <p className="font-bold text-lg">{variant.price.toLocaleString()} ₺</p>
        </div>
      </div>
    )
    
}
