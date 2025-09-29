"use client"
import { Product } from "@/types/main"
import { useCategoryProducts } from "@/hooks/useSearchQuery"
import { useParams } from "next/navigation"
import ProductCard from "./ProductCard"
import { motion } from "framer-motion"


export default function ProductList() {
  const { category } = useParams()
  const { data: filteredProducts, isLoading } = useCategoryProducts(category as string)

  const categoryProducts: Product[] = filteredProducts?.products ?? []
  const totalVariants = categoryProducts.reduce((acc, product) => acc + product.variants.length, 0)

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
      {/* Başlık grid dışında */}
      <h1 className="text-2xl font-bold mb-6 animate-fadeIn">
        {totalVariants} ürün bulundu
      </h1>

      {/* Sadece ürünler grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 justify-start items-start">
        {categoryProducts.flatMap((product, i) =>
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
