'use client'

import type { Product } from '@/types/seller/product'
import ProductCard from './ProductCard'

type ProductGridProps = {
  products: Product[]
  onView: (id: number) => void
  onEdit: (product: Product) => void
  onDelete: (product: Product) => void
}

export default function ProductGrid({
  products,
  onView,
  onEdit,
  onDelete,
}: ProductGridProps) {
  return (
    <section className="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
      {products.map((product) => (
        <ProductCard
          key={product.id}
          product={product}
          onView={onView}
          onEdit={onEdit}
          onDelete={onDelete}
        />
      ))}
    </section>
  )
}
