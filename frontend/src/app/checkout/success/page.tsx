"use client"
import { useEffect, useState } from "react"
import Link from "next/link"
import { useRouter, useSearchParams } from "next/navigation"
import { CheckoutLayout } from "@/components/checkout/layout/CheckoutLayout"
import { SuccessHero } from "@/components/checkout/success/SuccessHero"
import { SuccessInfoCard } from "@/components/checkout/success/SuccessInfoCard"
import { OrderSummary } from "@/components/checkout/review/OrderSummary"
import { useMe } from "@/hooks/useAuthQuery"
import { useCheckoutSession } from "@/hooks/checkout/useCheckoutSession"
import LoadingState from "@/components/ui/LoadingState"

export default function SuccessPage() {
  const searchParams = useSearchParams()
  const router = useRouter()
  const [resolvedSessionId, setResolvedSessionId] = useState<string | null>(null)
  useEffect(() => { setResolvedSessionId(searchParams.get("session")) }, [searchParams])

  if (resolvedSessionId === null) {
    return (
      <CheckoutLayout currentStep="success" showSummary={false}>
        <div className="py-12 text-center text-muted-foreground">Yükleniyor…</div>
      </CheckoutLayout>
    )
  }

  if (!resolvedSessionId) {
    return (
      <CheckoutLayout currentStep="success" showSummary={false}>
        <div className="py-12 text-center">
          <h2 className="text-xl font-semibold mb-2">Session bulunamadı.</h2>
          <p className="text-sm text-muted-foreground">Lütfen sepet sayfasından checkout akışını yeniden başlatın.</p>
          <Link href="/bag" className="mt-4 inline-block text-[var(--accent)] underline">Sepete dön</Link>
        </div>
      </CheckoutLayout>
    )
  }

  return <SuccessContent sessionId={resolvedSessionId} />
}

function SuccessContent({ sessionId }: { sessionId: string }) {
  const router = useRouter()
  const { data: me, isLoading: meLoading } = useMe()
  const { data, isLoading, isError } = useCheckoutSession(sessionId)

  if (meLoading || isLoading) {
    return (
      <CheckoutLayout currentStep="success" showSummary={false}>
        <div className="py-12 text-center">
          <p className="text-lg sm:text-xl font-semibold text-gray-900 mb-2 animate-pulse">Yükleniyor…</p>
        </div>
      </CheckoutLayout>
    )
  }

  if (!me || isError || !data) {
    router.push("/bag")
    return (
      <CheckoutLayout currentStep="success" showSummary={false}>
        <div className="py-12 text-center">
          <h2 className="text-xl font-semibold mb-2">Sipariş bulunamadı.</h2>
          <Link href="/bag" className="text-[var(--accent)] underline">Sepete dön</Link>
        </div>
      </CheckoutLayout>
    )
  }

  const orderCode = (data.meta as Record<string, unknown> | undefined)?.order_number?.toString() ?? data.session_id

  return (
    <CheckoutLayout currentStep="success" showSummary={false}>
      <div className="flex flex-col gap-8">
        <SuccessHero orderCode={orderCode} totalCents={data.bag?.totals.final_cents} />
        <div className="grid gap-6 md:grid-cols-1 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
          <OrderSummary bag={data.bag ?? null} />
          <SuccessInfoCard shipping={data.shipping_data} billing={data.billing_data} payment={data.payment_data} />
        </div>
        <div className="flex flex-col sm:flex-row flex-wrap gap-3">
          <Link href="/account/orders" className="rounded-lg bg-[var(--accent)] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[var(--accent-dark)] text-center">Siparişlerim</Link>
          <Link href="/" className="rounded-lg border border-color px-5 py-3 text-sm font-semibold transition hover:border-[var(--accent)] hover:text-[var(--accent)] text-center">Alışverişe devam et</Link>
        </div>
      </div>
    </CheckoutLayout>
  )
}
