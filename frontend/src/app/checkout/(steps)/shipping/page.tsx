"use client"

import Link from "next/link"
import { useEffect } from "react"
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

  const sessionId = searchParams.get("session") ?? undefined

  useEffect(() => {
    if (!sessionId) {
      router.replace("/bag")
    }
  }, [sessionId, router])

  if (!sessionId) {
    return (
      <CheckoutLayout currentStep="shipping">
        <div className="py-12 text-center text-muted-foreground">Yükleniyor…</div>
      </CheckoutLayout>
    )
  }

  return <ShippingContent sessionId={sessionId} />
}

function ShippingContent({ sessionId }: { sessionId: string }) {
  const router = useRouter()
  const { data: me, isLoading: meLoading } = useMe()
  const { data, isLoading, isError } = useCheckoutSession(sessionId)
  const updateShipping = useUpdateShipping()


  if (!me) {
    return (
      <CheckoutLayout currentStep="shipping">
        <div className="py-12 text-center">
          <h2 className="mb-2 text-xl font-semibold">Giriş yapmanız gerekiyor.</h2>
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
          <p className="mb-2 text-lg font-semibold text-gray-900 animate-pulse">
            Teslimat bilgileri yükleniyor...
          </p>
        </div>
      </CheckoutLayout>
    )
  }

  if (isError || !data) {
    return (
      <CheckoutLayout currentStep="shipping">
        <div className="py-12 text-center">
          <h2 className="mb-2 text-xl font-semibold">Ödeme Akışı bulunamadı.</h2>
          <p className="mb-4 text-sm text-muted-foreground">
            Lütfen sepet sayfasına dönüp checkout akışını yeniden başlatın.
          </p>
          <Link
            href="/bag"
            className="inline-block rounded-xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-600"
          >
            Sepete Git
          </Link>
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
      <div className="mb-6 flex items-center justify-between">
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
