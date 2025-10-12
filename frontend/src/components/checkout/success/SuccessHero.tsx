"use client"

import { CheckCircle2 } from "lucide-react"

interface SuccessHeroProps {
  orderCode?: string
  totalCents?: number
}

const formatPrice = (cents?: number) =>
  typeof cents === "number" ? `${(cents / 100).toFixed(2)} ₺` : "-"

export function SuccessHero({ orderCode, totalCents }: SuccessHeroProps) {
  return (
    <div className="flex flex-col items-center gap-4 rounded-3xl border border-color bg-gradient-to-br from-[var(--accent)]/10 via-card to-card p-8 text-center shadow-md">
      <div className="flex h-14 w-14 items-center justify-center rounded-full bg-[var(--accent)] text-white shadow-lg">
        <CheckCircle2 className="h-8 w-8" />
      </div>
      <div className="space-y-1">
        <h1 className="text-2xl font-semibold">Siparişin onaylandı!</h1>
        <p className="text-sm text-muted-foreground">
          Sipariş özeti e-postana gönderildi. Aşağıdan detaylarını inceleyebilirsin.
        </p>
      </div>
      <div className="flex flex-wrap items-center justify-center gap-3 text-sm">
        {orderCode && (
          <span className="rounded-full border border-color px-4 py-1 font-medium text-muted-foreground">
            Sipariş No: {orderCode}
          </span>
        )}
        <span className="rounded-full bg-card px-4 py-1 font-semibold">
          Toplam: {formatPrice(totalCents)}
        </span>
      </div>
    </div>
  )
}
