"use client"
import { ProductWithVariant } from "@/types/search"
import { useParams } from "next/navigation"
import ProductCard from "./ProductCard"
import { motion } from "framer-motion"

export default function ProductList({ products, isLoading }: { products: ProductWithVariant[]; isLoading: boolean }) {

  if (isLoading) {
    return (
      <div className="min-h-[60vh] flex items-center justify-center px-4">
        <p className="text-lg sm:text-xl font-semibold text-gray-900 mb-2 animate-pulse">Yükleniyor…</p>
      </div>
    ) 
  }

  if (products.length === 0) {
    return (
      <div className="min-h-[60vh] flex items-center justify-center px-4">
        <p className="text-lg sm:text-2xl font-bold text-gray-700">
          Ürün bulunamadı
        </p>
      </div>
    )
  }

  return (
    <div className="min-h-screen px-3 sm:px-6 lg:px-10 py-4">
      <h1 className="text-xl sm:text-2xl font-bold mb-5 sm:mb-8 animate-fadeIn text-gray-900">
        {products.length} ürün bulundu
      </h1>

      <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
        {products.map((item, i) => (
          <motion.div
            key={item.variant.id}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.4, delay: i * 0.08 }}
          >
            <ProductCard product={item.product} variant={item.variant} />
          </motion.div>
        ))}
      </div>
    </div>
  )
}
