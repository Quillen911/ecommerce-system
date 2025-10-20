"use client"

import { useBagIndex, useBagUpdate, useBagDestroy } from '@/hooks/useBagQuery'
import { useMe } from '@/hooks/useAuthQuery'
import { BagItem, BagTotals, BagCampaign } from '@/types/bag'
import { toast } from 'sonner'

import { BagItemRow } from "@/components/bag/BagItemRow"
import { BagSummary } from "@/components/bag/BagSummary"
import { EmptyBagState } from "@/components/bag/EmptyBagState"
import { BagCampaignSelector } from "@/components/bag/BagCampaignSelector"
import { useCreateCheckoutSession } from '@/hooks/checkout/useCheckoutSession'
import { useRouter } from 'next/navigation'
import LoadingState from '@/components/ui/LoadingState'

export default function BagPage() {
  const router = useRouter()
  const { data: me } = useMe() 
  const { data, isLoading, error } = useBagIndex(me?.id)
  const updateBag = useBagUpdate(me?.id)
  const destroyBag = useBagDestroy(me?.id)
  const createSession  = useCreateCheckoutSession()

  if (isLoading) {
    return (
      <div className="flex items-center justify-center h-screen">
        <div className="text-center py-12 sm:py-16 px-4">
          <p className="text-lg sm:text-xl font-semibold text-gray-900 mb-2 animate-pulse">Sepet yükleniyor...</p>
        </div>
      </div>
    )
  }

  if (error) return <div>Sepet yüklenirken hata oluştu</div>
  if (!data) return <EmptyBagState />

  const bagItems: BagItem[] = data?.products || []
  const bagTotals: BagTotals | null = data?.totals ?? null
  const bagCampaign: BagCampaign | null = data?.applied_campaign ?? null

  if (bagItems.length === 0) {
    return <EmptyBagState />
  }
  
  const handleCheckout = () => {
    if (!me) {
      router.push('/login')
      return
    }

    createSession.mutate(
      { coupon_code: bagCampaign?.code },
      {
        onSuccess: (resp) => {
          router.push(`/checkout/shipping?session=${resp.session_id}`)
        },
        onError: () => {
          toast.error('Checkout başlatılamadı')
        },
      }
    )
  }

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

  return (
    <div className="min-h-screen bg-[var(--bg)] p-4 sm:p-6">
      <h1 className="mb-6 text-2xl font-bold text-black animate-fadeIn">
        Sepetiniz
      </h1>

      <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div className="space-y-4 lg:col-span-2 lg:max-h-[calc(100vh-180px)] lg:overflow-y-auto lg:pr-2">
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

        <aside className="space-y-4 lg:col-span-1 lg:h-fit lg:sticky lg:top-6">
          <BagCampaignSelector
            activeCampaign={bagCampaign}
            campaigns={data?.campaigns ?? []}
          />

          <BagSummary
            total={bagTotals?.total_cents}
            discount={bagTotals?.discount_cents}
            cargoPrice={bagTotals?.cargo_cents}
            finalPrice={bagTotals?.final_cents}
            onCheckout={createSession.isPending ? undefined : handleCheckout}
            loading={createSession.isPending}
          />
        </aside>
      </div>
    </div>
  )
}
