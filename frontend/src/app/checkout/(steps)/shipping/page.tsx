"use client"

import { useEffect, useState } from "react"
import Link from "next/link"
import { useRouter, useSearchParams } from "next/navigation"
import { toast } from "sonner"

import { CheckoutLayout } from "@/components/checkout/layout/CheckoutLayout"
import { StepBackButton } from "@/components/checkout/layout/StepBackButton"
import { ShippingForm } from "@/components/checkout/shipping/ShippingForm"
import type { ShippingFormValues } from "@/schemas/checkout/shippingSchema"

import { useCheckoutSession } from "@/hooks/checkout/useCheckoutSession"
import { useUpdateShipping } from "@/hooks/checkout/useShippingOptions"
import { useMe } from "@/hooks/useAuthQuery"

export default function ShippingStepPage() {
  const searchParams = useSearchParams()
  const router = useRouter()
  const [resolvedSessionId, setResolvedSessionId] = useState<string | undefined>(undefined)
  const [shouldRedirect, setShouldRedirect] = useState(false)

  useEffect(() => {
    const sessionParam = searchParams.get("session")
    setResolvedSessionId(sessionParam ?? "")
    if (!sessionParam) setShouldRedirect(true)
  }, [searchParams])

  useEffect(() => {
    if (shouldRedirect) router.replace("/bag")
  }, [shouldRedirect, router])

  if (resolvedSessionId === undefined) {
    return (
      <CheckoutLayout currentStep="shipping">
        <div className="py-12 text-center text-muted-foreground">Yükleniyor…</div>
      </CheckoutLayout>
    )
  }

  if (shouldRedirect) {
    return (
      <CheckoutLayout currentStep="shipping">
        <div className="py-12 text-center">
          <h2 className="text-xl font-semibold mb-2">Session bulunamadı.</h2>
          <p className="text-sm text-muted-foreground mb-4">
            Lütfen sepet sayfasına dönüp checkout akışını yeniden başlatın.
          </p>
          <Link
            href="/bag"
            className="rounded-xl bg-[var(--accent)] px-5 py-3 text-sm font-semibold text-white hover:bg-[var(--accent-dark)] transition"
          >
            Sepete Git
          </Link>
        </div>
      </CheckoutLayout>
    )
  }

  return <ShippingContent sessionId={resolvedSessionId} />
}

function ShippingContent({ sessionId }: { sessionId: string }) {
  const router = useRouter()
  const { data: me, isLoading: meLoading } = useMe()
  const { data, isLoading, isError } = useCheckoutSession(sessionId)
  const updateShipping = useUpdateShipping()

  if (meLoading) {
    return (
      <CheckoutLayout currentStep="shipping">
        <div className="py-12 text-center">
          <p className="text-lg sm:text-xl font-semibold text-gray-900 mb-2 animate-pulse">Yükleniyor…</p>
        </div>
      </CheckoutLayout>
    )
  }

  if (!me) {
    return (
      <CheckoutLayout currentStep="shipping">
        <div className="py-12 text-center">
          <h2 className="text-xl font-semibold mb-2">Giriş yapmanız gerekiyor.</h2>
          <p className="text-sm text-muted-foreground">
            Teslimat adımına devam etmek için lütfen hesabınıza giriş yapın.
          </p>
        </div>
      </CheckoutLayout>
    )
  }

  if (isLoading) {
    return (
      <CheckoutLayout currentStep="shipping">
        <div className="py-12 text-center">
          <p className="text-lg sm:text-xl font-semibold text-gray-900 mb-2 animate-pulse">Teslimat bilgileri yükleniyor...</p>
        </div>
      </CheckoutLayout>
    )
  }

  if (isError || !data) {
    return (
      <CheckoutLayout currentStep="shipping">
        <div className="py-12 text-center">
          <h2 className="text-xl font-semibold mb-2">Session bulunamadı.</h2>
          <p className="text-sm text-muted-foreground">
            Lütfen sepet sayfasından checkout akışını yeniden başlatın.
          </p>
        </div>
      </CheckoutLayout>
    )
  }

  const handleSubmit = (formValues: ShippingFormValues) => {
    updateShipping.mutate(
      {
        session_id: formValues.session_id,
        shipping_address_id: formValues.shipping_address_id,
        billing_address_id: formValues.billing_address_id,
        delivery_method: formValues.delivery_method,
        notes: formValues.notes,
      },
      {
        onSuccess: () => {
          router.push(`/checkout/payment?session=${formValues.session_id}`)
        },
        onError: () => {
          toast.error("Teslimat bilgileri kaydedilemedi")
        },
      }
    )
  }

  return (
    <CheckoutLayout currentStep="shipping" bag={data.bag}>
      <div className="flex items-center justify-between mb-6">
        <StepBackButton fallbackHref="/bag" />
      </div>

      <ShippingForm
        sessionId={sessionId}
        userId={me.id}
        defaultValues={{
          shipping_address_id: data.shipping_data?.shipping_address_id ?? undefined,
          billing_address_id: data.billing_data?.billing_address_id ?? undefined,
          delivery_method: data.shipping_data?.delivery_method ?? "standard",
          notes: data.shipping_data?.notes ?? "",
        }}
        onSubmit={handleSubmit}
        isSubmitting={updateShipping.isPending}
        allowDifferentBilling
      />
    </CheckoutLayout>
  )
}
