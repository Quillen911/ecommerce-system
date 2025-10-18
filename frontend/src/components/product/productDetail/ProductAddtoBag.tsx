"use client"

import { useCallback } from "react"
import { toast } from "sonner"
import { useBagStore } from "@/hooks/useBagQuery"
import { useMe } from "@/hooks/useAuthQuery"
import type { BagStoreRequest } from "@/types/bag"

interface ProductAddtoBagProps {
  variantSizeId: number | null
}

export default function ProductAddtoBag({ variantSizeId }: ProductAddtoBagProps) {
  const { data: me } = useMe()
  const bagStore = useBagStore(me?.id)

  const handleAddToBag = useCallback(() => {
    if (!variantSizeId) {
      toast.error("Lütfen bir beden seçiniz.")
      return
    }

    const payload: BagStoreRequest = {
      variant_size_id: variantSizeId,
      quantity: 1,
    }

    bagStore.mutate(payload, {
      onSuccess: () => toast.success("Ürün sepete eklendi."),
      onError: (err) => {
        console.error("Sepete eklenirken hata:", err)
        toast.error("Ürün sepete eklenemedi.")
      },
    })
  }, [variantSizeId, bagStore])

  return (
    <div className="w-full mt-3">
      <button
        onClick={handleAddToBag}
        disabled={bagStore.isPending}
        className="w-full bg-black text-white text-sm sm:text-base font-medium py-3 rounded-xl transition-all duration-200 hover:bg-gray-800 disabled:opacity-60 disabled:cursor-not-allowed"
      >
        {bagStore.isPending ? "Ekleniyor..." : "Sepete Ekle"}
      </button>
    </div>
  )
}
