"use client";

import { useCallback, useMemo } from "react";
import { toast } from "sonner";

import { useMe } from "@/hooks/useAuthQuery";
import { useBagIndex, useBagStore } from "@/hooks/useBagQuery";
import type { BagStoreRequest } from "@/types/bag";

interface ProductAddtoBagProps {
  variantSizeId: number | null;
  /**
   * Opsiyonel: Seçili bedenin stok adedi doğrudan biliniyorsa iletilir.
   * Sağlanmazsa mevcut sepet öğesindeki stok bilgisinden okunur.
   */
  availableQuantity?: number | null;
}

export default function ProductAddtoBag({
  variantSizeId,
  availableQuantity,
}: ProductAddtoBagProps) {
  const { data: me } = useMe();
  const { data: bagData } = useBagIndex(me?.id);
  const bagStore = useBagStore(me?.id);

  const selectedBagItem = useMemo(
    () =>
      bagData?.products.find(
        (item) => item.variant_size_id === variantSizeId,
      ),
    [bagData?.products, variantSizeId],
  );

  const currentQuantity = selectedBagItem?.quantity ?? 0;
  const stockLimit =
    availableQuantity ??
    selectedBagItem?.sizes.inventory.available ??
    Number.POSITIVE_INFINITY;

  const handleAddToBag = useCallback(() => {
    if (!variantSizeId) {
      toast.error("Lütfen bir beden seçiniz.");
      return;
    }

    if (!Number.isFinite(stockLimit)) {
    } else if (stockLimit <= 0) {
      toast.error("Seçilen beden stokta yok.");
      return;
    } else if (currentQuantity + 1 > stockLimit) {
      toast.error("Stok sınırına ulaşıldı.");
      return;
    }

    const payload: BagStoreRequest = {
      variant_size_id: variantSizeId,
      quantity: 1,
    };

    const toastId = toast.loading("Ürün sepete ekleniyor...");
    bagStore.mutate(payload, {
      onSuccess: () =>
        toast.success("Ürün sepete eklendi.", { id: toastId }),
      onError: (err) => {
        console.error("Sepete eklenirken hata:", err);
        toast.error("Ürün sepete eklenemedi.", { id: toastId });
      },
    });
  }, [variantSizeId, stockLimit, currentQuantity, bagStore]);

  return (
    <div className="mt-3 w-full">
      <button
        onClick={handleAddToBag}
        disabled={bagStore.isPending}
        className="w-full rounded-xl bg-black py-3 text-sm font-medium text-white transition-all duration-200 hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-60 sm:text-base"
      >
        {bagStore.isPending ? "Ekleniyor..." : "Sepete Ekle"}
      </button>
    </div>
  );
}
