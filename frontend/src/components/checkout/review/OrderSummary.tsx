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
    <div className="surface rounded-lg border border-color p-6 shadow-sm">
      <h2 className="mb-4 text-lg font-semibold">Sipariş Özeti</h2>

      <ul className="space-y-3 text-sm">
        {items.map((item) => (
          <li key={item.bag_item_id} className="flex items-start justify-between gap-3">
            <div>
              <p className="font-medium">{item.product_title}</p>
              <p className="text-muted-foreground">
                Adet: {item.quantity}
              </p>
            </div>
            <span className="font-semibold">{formatPrice(item.total_price_cents)} ₺</span>
          </li>
        ))}
      </ul>

      <hr className="my-4 border-color" />

      <div className="space-y-2 text-sm">
        <div className="flex justify-between">
          <span>Ürün Toplamı</span>
          <span>{formatPrice(totals.total_cents)} ₺</span>
        </div>
        <div className="flex justify-between">
          <span>İndirim</span>
          <span className="text-green-600">- {formatPrice(totals.discount_cents)} ₺</span>
        </div>
        <div className="flex justify-between">
          <span>Kargo</span>
          <span>{formatPrice(totals.cargo_cents)} ₺</span>
        </div>
      </div>

      <hr className="my-4 border-color" />

      <div className="flex items-center justify-between text-base font-semibold">
        <span>Genel Toplam</span>
        <span>{formatPrice(totals.final_cents)} ₺</span>
      </div>

     {campaignName && (
        <div className="mt-3 rounded-md bg-emerald-50 px-3 py-2 text-xs text-emerald-700">
          Kampanya uygulandı ({campaignName})
        </div>
      )}
    </div>
  )
}