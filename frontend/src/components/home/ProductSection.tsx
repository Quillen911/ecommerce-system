'use client'
import { useFilterQuery } from '@/hooks/useSearchQuery'
import { ProductCardImage } from '@/components/ui/ProductImage'
import { useCategory } from '@/contexts/CategoryContext'

export default function ProductSection() {
  const { selectedCategory } = useCategory()
  const { data, isLoading, error } = useFilterQuery(
    selectedCategory ? { game: selectedCategory } : {}
  )

  if (error) {
    return (
      <div className="p-10 text-center">
        <p className="text-red-500">Ürünler yüklenirken hata oluştu</p>
      </div>
    )
  }

  if (isLoading) {
    return (
      <div className="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        {[...Array(8)].map((_, i) => (
          <div
            key={i}
            className="bg-white rounded-lg p-4 shadow-md animate-pulse"
          >
            <div className="w-full h-48 bg-gray-200 rounded-lg mb-4"></div>
            <div className="h-4 bg-gray-200 rounded mb-2"></div>
            <div className="h-3 bg-gray-200 rounded mb-1"></div>
          </div>
        ))}
      </div>
    )
  }

  if (!data || data.data.products.length === 0) {
    return <p>Henüz ürün bulunmuyor</p>
  }
  

  return (
    <div className="p-10">
      <h2 className="text-2xl font-bold mb-6 animate-fadeIn">
        {selectedCategory
          ? `${selectedCategory.replace(/-/g, " ").toUpperCase()} Ürünleri`
          : 'Tüm Ürünler'}
      </h2>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        {data.data.products.map((product: any, index: number) => (
          <div
            key={product.id}
            className="bg-white rounded-lg p-4 shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105"
            style={{
              animation: `fadeInUp 0.6s ease-out ${index * 100}ms forwards`,
            }}
          >
            <div className="flex items-center justify-center p-4">
              <ProductCardImage
                product={product}
                className="w-full h-48 mb-4"
              />
            </div>
            <h3 className="font-semibold text-lg mb-2 line-clamp-2">
              {product.title}
            </h3>
            <p className="text-xs text-gray-500 mb-3">{product.store_name}</p>
            <div className="flex items-center justify-between">
              <span className="text-lg font-bold text-green-600">
                {product.list_price} ₺
              </span>
              <button className="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                Sepete Ekle
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  )
}
