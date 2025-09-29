'use client'

import { Product } from "@/types/main"
import { useSearchQuery } from "@/hooks/useSearchQuery"
import { useSearchParams  } from "next/navigation"
import ProductCard from "./ProductCard"
import { motion } from "framer-motion"

export default function SearchProductList() {
    const searchParams = useSearchParams()
    const query = searchParams.get("q") || ""
    const sorting = searchParams.get("sorting") || undefined
    const filters = {
    gender: searchParams.get("gender") || undefined,
    min_price: searchParams.get("min_price") || undefined,
    max_price: searchParams.get("max_price") || undefined,
    age: searchParams.get("age")?.split(",") || undefined,
    color: searchParams.get("color")?.split(",") || undefined,
    }

    const { data: searchProducts, isLoading, error } = useSearchQuery(query, filters, sorting, 1, 12)

    const products = searchProducts?.products || []
    const totalVariants = products.reduce((acc, product) => acc + product.variants.length, 0)

    if (isLoading) {
      return (
        <div className="min-h-screen">
            <p className="text-center text-2xl font-bold justify-start items-start animate-pulse">Yükleniyor...</p>
            </div>
        )
      }
    if (products.length === 0) {
        return (
            <div className="min-h-screen">
            <p className="text-center text-2xl font-bold justify-start items-start">Ürün bulunamadı</p>
            </div>
        )
    }
    if (error) {
        return (
            <div className="min-h-screen">
            <p className="text-center text-2xl font-bold justify-start items-start">Hata oluştu</p>
            </div>
        )
    }
    
    return (
        <div className="min-h-screen">
          <div>
            <p className="text-lg text-gray-500">
              arama sonucu: {query} için
            </p>
            <h1 className="text-2xl font-bold mb-6 animate-fadeIn">
              {totalVariants} ürün bulundu
            </h1>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 justify-start items-start">
            {products.flatMap((product, i) =>
              product.variants.map((variant, j) => (
                <motion.div
                  key={variant.id}
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.4, delay: (i + j) * 0.1 }}
                >
                  <ProductCard product={product} variant={variant} />
                </motion.div>
              ))
            )}
          </div>
        </div>
      )
    
}
