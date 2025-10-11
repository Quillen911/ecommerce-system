"use client"

import { useBagIndex, useBagUpdate, useBagDestroy, bagKeys } from '@/hooks/useBagQuery'
import { useMe } from '@/hooks/useAuthQuery'
import { BagItem, BagTotals,BagCampaign } from '@/types/bag'
import { toast } from 'sonner'

import { BagItemRow } from "@/components/bag/BagItemRow"
import { BagSummary } from "@/components/bag/BagSummary"
import { EmptyBagState } from "@/components/bag/EmptyBagState"

export default function BagPage() {
  const { data: me } = useMe()
  const { data, isLoading, error } = useBagIndex(me?.id)

  const updateBag = useBagUpdate(me?.id)
  const destroyBag = useBagDestroy(me?.id)

  if (isLoading) return <div>Loading...</div>
  if (error) return <div>Sepet yüklenirken hata oluştu</div>

  const bagItems: BagItem[] = data?.products || []
  const bagTotals: BagTotals | null = data?.totals ?? null;
  const bagCampaign: BagCampaign | null = data?.applied_campaign ?? null;
  console.log(bagCampaign,bagTotals)
  const handleIncrease = (item: BagItem) => {
    if (item.quantity < item.sizes.inventory.available) {
      const toastId = toast.loading('Ürün güncelleniyor...')
      updateBag.mutate(
        { id: item.id, data: { quantity: item.quantity + 1 } },
        {
          onSuccess: () => {
            toast.success(`"${item.product_title}" adedi arttı`, { id: toastId })
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
      },
      onError: () => {
        toast.error('Ürün kaldırılamadı', { id: toastId })
      }
    })
  }
  if (bagItems.length === 0) {
    return <EmptyBagState />
  }
  
  return (
    <div className="min-h-screen p-6 bg-[var(--bg)]">
      <h1 className="text-2xl font-bold mb-6 animate-fadeIn">Sepetiniz</h1>
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-2 space-y-4">
          {bagItems.map((item) => (
            <BagItemRow
              key={item.id}
              item={item}
              onIncrease={handleIncrease}
              onDecrease={handleDecrease}
              onRemove={handleDestroy}
              disabled={updateBag.isPending || destroyBag.isPending}
            />
          ))}
        </div>
        <BagSummary
          total={bagTotals?.total}
          discount={bagTotals?.discount}
          cargoPrice={bagTotals?.cargo}
          finalPrice={bagTotals?.final}
          onCheckout={() => toast.info("Ödeme akışı henüz hazır değil")}
        />
      </div>
    </div>
  )
  
}
