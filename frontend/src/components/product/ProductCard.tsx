"use client"

import { useRouter } from "next/navigation"

import ProductImageGallery from "@/components/ui/ProductImageGallery"
import { Product, ProductVariant } from "@/types/seller/product"

type ProductCardProps = {
  product: Product
  variant: ProductVariant
}

export default function ProductCard({ product, variant }: ProductCardProps) {
  const router = useRouter()

  const colorMap: Record<string, string> = {
    "#000000": "bg-black",
    "#FF0000": "bg-red-500",
    "#00FF00": "bg-green-400",
    "#0066CC": "bg-blue-500",
    "#AAAAAA": "bg-gray-400",
    "#FFFFFF": "bg-gray-100 border",
  }

  const handleProductDetail = (slug: string) => {
    router.push(`/product/${slug}`)
  }

  const galleryImages =
    variant.images?.map((image) => ({
      ...image,
      image: image.image ?? undefined,
    })) ?? undefined
    
  const remainingStock =
    variant.sizes?.reduce(
      (sum, size) => sum + (size.inventory?.available ?? 0),
      0,
    ) ?? 0

  const lowStockMessage =
    remainingStock > 0 && remainingStock < 5
      ? `Sadece ${remainingStock} adet kaldı`
      : null
  const variantName = variant.color_name + " " + product.title
  return (
    <div className="p-4 cursor-pointer">
      <div className="w-full h-100 overflow-hidden flex items-center justify-center">
        <ProductImageGallery
          images={galleryImages}
          alt={product.title}
          className="object-contain w-full h-120"
          onClick={() => handleProductDetail(variant.slug)}
        />
      </div>
       
      <div className="mt-3 space-y-1">
        {lowStockMessage && (
          <p className="text-sm font-semibold text-red-600">
            {lowStockMessage}
          </p>
        )}
        {variant.is_popular && (
          <span className="text-red-600 text-sm font-semibold">En Çok Satan</span>
        )}
        
        <h3 className="font-semibold line-clamp-2">{variantName}</h3>
        
        <div className="flex gap-2 mt-2">
          <span
            className={`w-6 h-6 rounded-full border-2 ${colorMap[variant.color_code] || "bg-gray-300"}`}
            title={variant.color_name}
            style={{ backgroundColor: variant.color_code }}
          />
        </div>
        <p className="text-sm text-gray-500">
          {product.category?.parent?.title} / {product.category?.gender?.title}
        </p>
        
        <p className="font-bold text-lg">₺{(variant.price_cents / 100).toFixed(2)}</p>
      </div>
    </div>
  )
}
