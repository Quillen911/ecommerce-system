"use client"

import ProductDetail from "@/components/product/productDetail/ProductDetail";
import { useParams } from "next/navigation";
import { useProductDetail } from "@/hooks/useVariantQuery";

export default function ProductDetailPage() {
    const { slug } = useParams()
    const { data: product } = useProductDetail(slug as string)

    if (!product) return <p className="text-center text-2xl font-bold text-white">Ürün bulunamadı</p>
    return (
        <div className="min-h-screen bg-[var(--bg)] p-4 md:p-8">
          <div className="container mx-auto">
            <ProductDetail 
              product={product} 
              variant={product.variants[0]} 
            />
          </div>
        </div>
      )
}