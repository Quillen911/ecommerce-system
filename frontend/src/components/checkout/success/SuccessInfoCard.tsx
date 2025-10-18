"use client"
import type { BillingData, PaymentData, ShippingData } from "@/types/checkout"

interface SuccessInfoCardProps {
  shipping?: ShippingData | null
  billing?: BillingData | null
  payment?: PaymentData | null
}

const labelStyles = "text-[10px] sm:text-xs font-medium uppercase tracking-[0.2em] text-muted-foreground"
const valueStyles = "text-sm sm:text-base font-semibold break-words"

export function SuccessInfoCard({ shipping, billing, payment }: SuccessInfoCardProps) {
  return (
    <div className="space-y-4 rounded-2xl border border-color bg-card p-4 sm:p-6 shadow-sm">
      <div>
        <p className={labelStyles}>Teslimat</p>
        <p className={valueStyles}>{shipping?.delivery_method ?? "Belirtilmedi"}</p>
        {shipping?.notes && <p className="mt-1 text-xs text-muted-foreground break-words">Not: {shipping.notes}</p>}
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 rounded-xl border border-dashed border-color bg-background px-3 sm:px-4 py-3">
        <div>
          <p className={labelStyles}>Teslimat Adresi ID</p>
          <p className={valueStyles}>{shipping?.shipping_address_id ?? "-"}</p>
        </div>
        <div>
          <p className={labelStyles}>Fatura Adresi ID</p>
          <p className={valueStyles}>{billing?.billing_address_id ?? "-"}</p>
        </div>
      </div>

      <div>
        <p className={labelStyles}>Ödeme</p>
        <p className={valueStyles}>{payment?.provider ?? "-"} · {payment?.method == "new_card" ? "Yeni Kart" : "Varolan Kart"}</p>
        <p className="mt-1 text-xs text-muted-foreground">Durum: {payment?.status  == "authorized" ? "Ödendi" : "Ödenmedi"}</p>
      </div>
    </div>
  )
}
