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
      <div className="min-h-screen">
        <p className="text-center text-2xl font-bold justify-start items-start animate-pulse">Yükleniyor...</p>
      </div>
    )
  }
  if (categoryProducts.length === 0) 
    return (
    <div className="min-h-screen">
      <p className="text-center text-2xl font-bold justify-start items-start">Ürün bulunamadı</p>
    </div>
    )
  return (
    <div className="min-h-screen">
      <h1 className="text-2xl font-bold mb-6 animate-fadeIn">
        {categoryProducts.length} ürün bulundu
      </h1>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 justify-start items-start">
        {categoryProducts.map((item, i) => (
          <motion.div
            key={item.variant.id}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.4, delay: i * 0.1 }}
          >
            <ProductCard product={item.product} variant={item.variant} />
          </motion.div>
        ))}
      </div>
    </div>
  )
}
