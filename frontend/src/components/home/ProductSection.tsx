'use client'

import { useFilterQuery } from '@/hooks/useSearchQuery'
import { ProductCardImage } from '@/components/ui/ProductImage'
import { useCategory } from '@/contexts/CategoryContext'
import { useBagIndex, useBagStore } from '@/hooks/useBagQuery'
import { useMe } from '@/hooks/useAuthQuery'
import { toast } from 'sonner'
import { useQueryClient } from '@tanstack/react-query'
import { useRouter } from "next/navigation"

export default function ProductSection() {
  const { selectedCategory } = useCategory()
  const queryClient = useQueryClient()
  const router = useRouter()
  const { data, isLoading, error } = useFilterQuery(
    selectedCategory ? { game: selectedCategory } : {}
  )
  const { data: me } = useMe()
  const { data: bagData } = useBagIndex(me?.id)
  const bagItems = bagData?.data?.products || []
  const { mutate: addToBag } = useBagStore(me?.id)

  const handleAddToBag = (product: any) => {
    const toastId = toast.loading('Ürün sepete ekleniyor...')
    addToBag(
      { product_id: product.id },
      {
        onSuccess: () => {
          toast.success(
            <div className="flex items-center justify-between gap-4">
              <span>"{product.title}" sepete eklendi</span>
              <button
                onClick={() => router.push("/bag")}
                className="px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 transition"
              >
                Sepete Git
              </button>
            </div>,
            { id: toastId, duration: 4000 }
          )
          queryClient.invalidateQueries({ queryKey: ['bags'] })
        },
        onError: () => {
          toast.error('Ürün sepete eklenemedi', { id: toastId })
        }
      }
    )
  }

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
          ? `${selectedCategory.replace(/-/g, ' ').toUpperCase()} Ürünleri`
          : 'Tüm Ürünler'}
      </h2>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        {data.data.products.map((product: any, index: number) => {
          const inBagQuantity =
            bagItems.find((b: any) => b.product_id === product.id)?.quantity ||
            0
          const remainingStock = product.stock_quantity - inBagQuantity
          const isOutOfStock = product.stock_quantity <= 0
          const isUserLimitReached = remainingStock <= 0

          return (
            <div
              key={product.id}
              className="bg-white rounded-lg p-4 shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105"
              style={{
                animation: `fadeInUp 0.6s ease-out ${index * 100}ms forwards`
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
                {isOutOfStock ? (
                  <button
                    disabled
                    className="px-3 py-1 rounded text-sm bg-gray-500 cursor-not-allowed text-white"
                  >
                    STOKTA YOK
                  </button>
                ) : isUserLimitReached ? (
                  <button
                    disabled
                    className="px-3 py-1 rounded text-sm bg-gray-400 cursor-not-allowed text-white"
                  >
                    MAX SEPETTE
                  </button>
                ) : (
                  <button
                    onClick={() => handleAddToBag(product)}
                    className="px-3 py-1 rounded text-sm bg-blue-500 hover:bg-blue-600 transition-colors text-white"
                  >
                    SEPETE EKLE
                  </button>
                )}
              </div>
            </div>
          )
        })}
      </div>
    </div>
  )
}
