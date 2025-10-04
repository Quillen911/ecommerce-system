"use client"
import { useBagStore } from "@/hooks/useBagQuery"
import { BagStoreRequest } from "@/types/bag"
import { useCallback } from "react"
import { toast } from "sonner"

interface ProductAddtoBagProps {
  variantSizeId: number
}

export default function ProductAddtoBag({ variantSizeId }: ProductAddtoBagProps) {
  const bagStore = useBagStore()

  const handleAddToBag = useCallback(() => {
    if (!variantSizeId) {
      toast.error("Lütfen beden seçiniz")
      return
    }

    const payload: BagStoreRequest = {
      variant_size_id: variantSizeId,
      quantity: 1,
    }

    bagStore.mutate(payload, {
      onSuccess: () => {
        toast.success("Ürün sepete eklendi ")
      },
      onError: (err) => {
        console.error("Sepete eklenirken hata:", err)
        toast.error("Sepete eklenemedi")
      },
    })
  }, [variantSizeId, bagStore])

  return (
    <div className="product-addtobag">
      <button
        className="bg-black text-white px-4 py-2 rounded-md cursor-pointer"
        onClick={handleAddToBag}
        disabled={bagStore.isPending}
      >
        {bagStore.isPending ? "Ekleniyor..." : "Sepete Ekle"}
      </button>
    </div>
  )
}
