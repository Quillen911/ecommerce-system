"use client"

import ProductDetail from "@/components/product/productDetail/ProductDetail";
import { useParams } from "next/navigation";
import { useProductDetail } from "@/hooks/useVariantQuery";

export default function ProductDetailPage() {
    const { slug } = useParams()
    const { data: product } = useProductDetail(slug as string)

    if (!product) return <p className="text-center text-2xl font-bold text-white">Ürün bulunamadı</p>
    return (
        <div className="min-h-screen grid grid-cols-12 gap-6 bg-[var(--bg)] p-8">
            <div className="col-span-8 col-start-3">
                <ProductDetail 
                product={product} 
                variant={product.variants[0]} 
                />
            </div>
        </div>
    )
}