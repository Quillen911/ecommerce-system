"use client"

import type { Bag } from "@/types/checkout"

interface OrderSummaryProps {
  bag: Bag
}

const formatPrice = (cents: number) => (cents / 100).toFixed(2)

export function OrderSummary({ bag }: OrderSummaryProps) {
  const { items, totals, applied_campaign } = bag
  const campaignName =
    applied_campaign?.name?.trim() ||
    (applied_campaign?.campaign_id
      ? `#${applied_campaign.campaign_id}`
      : null)

  return (
    <div className="surface rounded-xl border border-color p-6 shadow-md animate-fadeInUp">
      <h2 className="mb-4 text-lg font-semibold text-[var(--accent)]">
        Sipariş Özeti
      </h2>

      <ul className="space-y-3 text-sm">
        {items.map((item) => (
          <li
            key={item.bag_item_id}
            className="flex items-start justify-between gap-3 border-b border-dashed border-color pb-2 last:border-0"
          >
            <div className="max-w-[70%]">
              <p className="font-medium leading-tight">{item.product_title}</p>
              <p className="text-muted-foreground text-xs mt-1">
                Adet: {item.quantity}
              </p>
            </div>
            <span className="font-semibold whitespace-nowrap">
              {formatPrice(item.total_price_cents)} ₺
            </span>
          </li>
        ))}
      </ul>

      <hr className="my-4 border-color" />

      <div className="space-y-2 text-sm">
        <div className="flex justify-between">
          <span className="text-muted-foreground">Ürün Toplamı</span>
          <span className="font-medium">{formatPrice(totals.total_cents)} ₺</span>
        </div>

        <div className="flex justify-between">
          <span className="text-muted-foreground">İndirim</span>
          <span className="text-emerald-500 font-medium">
            - {formatPrice(totals.discount_cents)} ₺
          </span>
        </div>

        <div className="flex justify-between">
          <span className="text-muted-foreground">Kargo</span>
          <span>{formatPrice(totals.cargo_cents)} ₺</span>
        </div>
      </div>

      <hr className="my-4 border-color" />

      <div className="flex items-center justify-between text-base font-semibold text-white bg-[var(--accent)] px-4 py-2 rounded-lg">
        <span>Genel Toplam</span>
        <span>{formatPrice(totals.final_cents)} ₺</span>
      </div>

      {campaignName && (
        <div className="mt-4 rounded-md border border-emerald-300 bg-emerald-50 px-4 py-2 text-xs text-emerald-700 shadow-sm">
          Kampanya uygulandı: <span className="font-semibold">{campaignName}</span>
        </div>
      )}
    </div>
  )
}
