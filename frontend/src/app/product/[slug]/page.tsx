"use client"

import ProductDetail from "@/components/product/productDetail/ProductDetail"
import { useParams } from "next/navigation"
import { useProductDetail } from "@/hooks/useVariantQuery"

export default function ProductDetailPage() {
  const { slug } = useParams()
  const { data: response, isLoading, error } = useProductDetail(slug as string)

  if (isLoading)
    return (
      <div className="flex items-center justify-center min-h-screen bg-[var(--bg)]">
        <p className="text-2xl font-bold text-gray-700 animate-pulse">Yükleniyor...</p>
      </div>
    )

  if (error)
    return (
      <div className="flex items-center justify-center min-h-screen bg-[var(--bg)]">
        <p className="text-2xl font-bold text-red-600">Bir hata oluştu</p>
      </div>
    )

  if (!response)
    return (
      <div className="flex items-center justify-center min-h-screen bg-[var(--bg)]">
        <p className="text-2xl font-bold text-gray-700">Ürün bulunamadı</p>
      </div>
    )

  const product = response.data
  const selectedVariant = product.variants[0]

  if (!selectedVariant)
    return (
      <div className="flex items-center justify-center min-h-screen bg-[var(--bg)]">
        <p className="text-xl font-semibold text-red-500">Seçili varyant bulunamadı</p>
      </div>
    )

  return (
    <div className="min-h-screen bg-[var(--bg)] p-4 md:p-8 mt-10">
      <div className="container mx-auto max-w-7xl">
        <ProductDetail
          product={product}
          variant={selectedVariant}
          allVariants={response.all_variants}
        />
      </div>
    </div>
  )
}
