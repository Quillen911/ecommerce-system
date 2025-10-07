"use client"

import ProductDetail from "@/components/product/productDetail/ProductDetail";
import { useParams } from "next/navigation";
import { useProductDetail } from "@/hooks/useVariantQuery";

export default function ProductDetailPage() {
  const { slug } = useParams()
  const { data: response , isLoading , error } = useProductDetail(slug as string)

  if (isLoading) return <p className="text-center text-2xl font-bold text-white">Yükleniyor...</p>

  if (!response) return <p className="text-center text-2xl font-bold text-white">Ürün bulunamadı</p>

  const product = response.data
  const selectedVariant = product.variants[0]
  if (!selectedVariant) return <p className="text-center text-xl text-red-500">Seçili varyant bulunamadı</p>

  return (
    <div className="min-h-screen bg-[var(--bg)] p-4 md:p-8 mt-10">
      <div className="container mx-auto">
        <ProductDetail 
          product={product} 
          variant={selectedVariant} 
          allVariants={response.all_variants}
        />
      </div>
    </div>
  )
}
