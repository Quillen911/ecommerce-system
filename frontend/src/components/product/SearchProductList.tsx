"use client"

import { useSearchQuery } from "@/hooks/useSearchQuery"
import { useSearchParams } from "next/navigation"
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
    sizes: searchParams.get("sizes")?.split(",") || undefined,
    color: searchParams.get("color")?.split(",") || undefined,
  }

  const { data: searchProducts, isLoading, error } = useSearchQuery(query, filters, sorting, 1, 1000)
  const products = searchProducts?.products || []
  const totalVariants = products.reduce((acc, product) => acc + product.variants.length, 0)

  if (isLoading)
    return (
      <div className="min-h-screen flex justify-center items-center">
        <p className="text-lg sm:text-xl font-semibold text-gray-900 mb-2 animate-pulse">Yükleniyor…</p>
      </div>
    )

  if (error)
    return (
      <div className="min-h-screen flex justify-center items-center">
        <p className="text-2xl font-bold text-red-600">Hata oluştu</p>
      </div>
    )

  if (products.length === 0)
    return (
      <div className="min-h-screen flex justify-center items-center">
        <p className="text-2xl font-bold">Ürün bulunamadı</p>
      </div>
    )

  return (
    <div className="min-h-screen">
      <div className="mb-6">
        <p className="text-lg text-gray-500">arama sonucu: {query} için</p>
        <h1 className="text-2xl font-bold animate-fadeIn">{totalVariants} ürün bulundu</h1>
      </div>

      <div
        className="
          grid
          grid-cols-2
          md:grid-cols-3
          lg:grid-cols-4
          gap-8
          justify-start
          items-start
        "
      >
        {products.flatMap((product, i) =>
          product.variants.map((variant, j) => (
            <motion.div
              key={variant.id}
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.4, delay: (i + j) * 0.1 }}
              className="flex flex-col items-center w-full"
            >
              <div className="w-full">
                <ProductCard product={product} variant={variant} />
              </div>
            </motion.div>
          ))
        )}
      </div>
    </div>
  )
}
