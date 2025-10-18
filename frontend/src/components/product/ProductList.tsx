"use client"
import { ProductWithVariant } from "@/types/search"
import { useCategoryProducts } from "@/hooks/useSearchQuery"
import { useParams } from "next/navigation"
import ProductCard from "./ProductCard"
import { motion } from "framer-motion"

export default function ProductList() {
  const { category } = useParams()
  const { data: filteredProducts, isLoading } = useCategoryProducts(category as string)
  const categoryProducts: ProductWithVariant[] = filteredProducts?.products ?? []

  if (isLoading) {
    return (
      <div className="min-h-[60vh] flex items-center justify-center px-4">
        <p className="text-lg sm:text-2xl font-bold animate-pulse text-gray-700">
          Yükleniyor...
        </p>
      </div>
    )
  }

  if (categoryProducts.length === 0) {
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
        {categoryProducts.length} ürün bulundu
      </h1>

      <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
        {categoryProducts.map((item, i) => (
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
