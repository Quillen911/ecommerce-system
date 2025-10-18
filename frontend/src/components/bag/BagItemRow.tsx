"use client";

import Image from "@/components/ui/ProductImage";
import type { BagItem } from "@/types/bag";

interface BagItemRowProps {
  item: BagItem;
  onIncrease: (item: BagItem) => void;
  onDecrease: (item: BagItem) => void;
  onRemove: (item: BagItem) => void;
  disabled?: boolean;
}

export function BagItemRow({
  item,
  onIncrease,
  onDecrease,
  onRemove,
  disabled,
}: BagItemRowProps) {
  const variant = item.sizes?.product_variant;
  const primaryImage =
    variant?.variant_images?.find((img) => img.is_primary)?.image_url ??
    variant?.variant_images?.[0]?.image_url ??
    "";

  const sizeLabel = item.sizes?.size_option?.value ?? item.sizes?.sku ?? "Seçim yok";
  const colorName = variant?.color_name ?? "Belirtilmemiş";

  return (
    <div className="flex flex-col sm:flex-row sm:items-start gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md">
      {/* Ürün görseli */}
      <div className="w-full sm:w-28 flex justify-center sm:justify-start">
        <div className="h-28 w-28 sm:h-24 sm:w-24 flex-shrink-0 overflow-hidden rounded-lg bg-gray-50">
          <Image
            product={primaryImage}
            alt={item.product_title}
            className="!h-full !w-full object-cover"
          />
        </div>
      </div>

      {/* Ürün bilgileri */}
      <div className="flex flex-1 flex-col justify-between gap-3 text-center sm:text-left">
        <div>
          <h2 className="text-base sm:text-lg font-semibold leading-tight">
            {item.product_title}
          </h2>

          <div className="mt-2 flex flex-col sm:flex-row justify-center sm:justify-start items-center sm:items-center gap-1 sm:gap-4 text-sm text-gray-600">
            <div>
              <span className="font-medium">Beden:</span> {sizeLabel}
            </div>
            <div className="flex items-center gap-2">
              <span className="font-medium">Renk:</span> {colorName}
            </div>
          </div>
        </div>

        {/* Adet kontrolü */}
        <div className="flex items-center justify-center sm:justify-start gap-3">
          <button
            onClick={() => onDecrease(item)}
            disabled={disabled}
            className="rounded-lg bg-gray-100 px-3 py-1.5 text-lg font-semibold transition hover:bg-gray-200 disabled:opacity-60"
          >
            −
          </button>
          <span className="min-w-[2ch] text-base font-medium">{item.quantity}</span>
          <button
            onClick={() => onIncrease(item)}
            disabled={disabled}
            className="rounded-lg bg-gray-100 px-3 py-1.5 text-lg font-semibold transition hover:bg-gray-200 disabled:opacity-60"
          >
            +
          </button>
        </div>
      </div>

      {/* Fiyat ve kaldır butonu */}
      <div className="flex flex-col items-center sm:items-end gap-2 mt-2 sm:mt-0">
        <p className="text-lg font-bold text-gray-900">
          ₺{(item.unit_price_cents / 100).toFixed(2)}
        </p>
        <button
          onClick={() => onRemove(item)}
          disabled={disabled}
          className="rounded-lg bg-red-500 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600 transition disabled:opacity-60"
        >
          Kaldır
        </button>
      </div>
    </div>
  );
}
