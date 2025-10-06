"use client"

import { useBagIndex, useBagUpdate, useBagDestroy, bagKeys } from '@/hooks/useBagQuery'
import { useMe } from '@/hooks/useAuthQuery'
import { BagItem } from '@/types/bag'
import { ProductCardImage } from '@/components/ui/ProductImage'
import { useQueryClient } from '@tanstack/react-query'
import { toast } from 'sonner'
import Link from 'next/link'

export default function BagPage() {
  const { data: me } = useMe()
  const { data, isLoading, error } = useBagIndex(me?.id)
  const queryClient = useQueryClient()
  const updateBag = useBagUpdate(me?.id)
  const destroyBag = useBagDestroy(me?.id)

  if (isLoading) return <div>Loading...</div>
  if (error) return <div>Sepet yüklenirken hata oluştu</div>

  const bag = data 
  const bagItems: BagItem[] = bag?.products || []
  
  const handleIncrease = (item: BagItem) => {
    if (item.quantity < item.sizes.inventory.available) {
      const toastId = toast.loading('Ürün güncelleniyor...')
      updateBag.mutate(
        { id: item.id, data: { quantity: item.quantity + 1 } },
        {
          onSuccess: () => {
            toast.success(`"${item.product_title}" adedi arttı`, { id: toastId })
            queryClient.invalidateQueries({ queryKey: bagKeys.index(me?.id) })
          },
          onError: () => {
            toast.error('Ürün güncellenemedi', { id: toastId })
          }
        }
      )
    } else {
      toast.error('Stok sınırına ulaşıldı')
    }
  }

  const handleDecrease = (item: BagItem) => {
    if (item.quantity > 1) {
      const toastId = toast.loading('Ürün güncelleniyor...')
      updateBag.mutate(
        { id: item.id, data: { quantity: item.quantity - 1 } },
        {
          onSuccess: () => {
            toast.success(`"${item.product_title}" adedi azaltıldı`, { id: toastId })
            queryClient.invalidateQueries({ queryKey: bagKeys.index(me?.id) })
          },
          onError: () => {
            toast.error('Ürün güncellenemedi', { id: toastId })
          }
        }
      )
    } else {
      handleDestroy(item)
    }
  }

  const handleDestroy = (item: BagItem) => {
    const toastId = toast.loading('Ürün sepetten kaldırılıyor...')
    destroyBag.mutate(item.id, {
      onSuccess: () => {
        toast.success(`"${item.product_title}" sepetten kaldırıldı`, { id: toastId })
        queryClient.invalidateQueries({ queryKey: bagKeys.index(me?.id) })
      },
      onError: () => {
        toast.error('Ürün kaldırılamadı', { id: toastId })
      }
    })
  }
  if (bagItems.length === 0) {
    return (
      <div className="min-h-screen p-6 bg-[var(--bg)] flex flex-col items-center justify-center">
        <div className="surface border border-dashed border-color rounded-2xl shadow-md p-10 max-w-md text-center animate-fadeIn">
          <h2 className="text-2xl font-bold mb-3">Sepetiniz Boş</h2>
          <p className="text-gray-500 mb-6 text-sm">
            Henüz ürün eklemediniz. Şimdi keşfetmeye başlayın.
          </p>
          <Link 
            href="/"
            className="inline-block bg-[var(--stone)] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[var(--accent-dark)] transition-colors duration-200"
          >
            Alışverişe Başla
          </Link>
        </div>
      </div>
    )
    
  }
  
  return (
    <div className="min-h-screen p-6 bg-[var(--bg)]">
      <h1 className="text-2xl font-bold mb-6 animate-fadeIn">Sepetiniz</h1>
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2 space-y-4">
          {bagItems.map((item) => (
            <div
              key={item.id}
              className="flex items-center gap-4 p-4 rounded-lg surface border border-color shadow-sm animate-slideInFromLeft"
            >
              <div className="w-20 h-20 flex-shrink-0">
                <ProductCardImage
                  product={item.sizes.variants}
                  alt={item.product_title}
                  className="!w-full !h-full object-cover rounded"
                />
              </div>
              <div className="flex-1">
                <h2 className="font-semibold line-clamp-2">{item.product_title}</h2>
                <div className="flex items-center gap-2 mt-2">
                  <button
                    onClick={() => handleDecrease(item)}
                    className="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 transition"
                  >
                    -
                  </button>
                  <span className="px-3">{item.quantity}</span>
                  <button
                    onClick={() => handleIncrease(item)}
                    className="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 transition"
                  >
                    +
                  </button>
                </div>
              </div>
              <div className="text-right">
                <p className="font-bold">₺{item.sizes.price_cents/100}</p>
              </div>
              <button 
                onClick={() => handleDestroy(item)}
                className="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition"
              >
                Kaldır
              </button>
            </div>
          ))}
        </div>
        <div className="surface p-6 rounded-lg shadow-md border border-color h-fit animate-fadeInUp">
          <h2 className="text-xl font-semibold mb-4">Sepet Özeti</h2>
          <div className="space-y-2 text-sm">
            <div className="flex justify-between">
              <span>Ürün Toplamı</span>
              <span>{bag?.total?.toFixed(2)} ₺</span>
            </div>
            <div className="flex justify-between">
              <span>İndirim</span>
              <span className="text-green-600">- {bag?.discount?.toFixed(2)} ₺</span>
            </div>
            <div className="flex justify-between">
              <span>Kargo</span>
              <span>{bag?.cargoPrice?.toFixed(2)} ₺</span>
            </div>
            <hr className="my-3 border-color" />
            <div className="flex justify-between font-bold text-lg">
              <span>Ödenecek Tutar</span>
              <span>{bag?.finalPrice?.toFixed(2)} ₺</span>
            </div>
          </div>
          <button className="w-full mt-6 py-3 bg-[var(--accent)] text-white rounded-lg font-semibold hover:bg-[var(--accent-dark)] transition">
            Alışverişi Tamamla
          </button>
        </div>
      </div>
    </div>
  )
  
}
