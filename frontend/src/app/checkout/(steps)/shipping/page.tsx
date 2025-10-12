"use client"

import { useEffect, useState } from "react"
import { useRouter, useSearchParams } from "next/navigation"
import { toast } from "sonner"

import { CheckoutLayout } from "@/components/checkout/layout/CheckoutLayout"
import { ShippingForm } from "@/components/checkout/shipping/ShippingForm"
import type { ShippingFormValues } from "@/schemas/checkout/shippingSchema"

import { useCheckoutSession } from "@/hooks/checkout/useCheckoutSession"
import { useUpdateShipping } from "@/hooks/checkout/useShippingOptions"
import { useMe } from "@/hooks/useAuthQuery"

export default function ShippingStepPage() {
  const searchParams = useSearchParams()
  const [resolvedSessionId, setResolvedSessionId] = useState<string | null>(null)

  useEffect(() => {
    setResolvedSessionId(searchParams.get("session"))
  }, [searchParams])

  if (resolvedSessionId === null) {
    return (
      <CheckoutLayout currentStep="shipping">
        <div className="py-12 text-center text-muted-foreground">Yükleniyor…</div>
      </CheckoutLayout>
    )
  }

  if (!resolvedSessionId) {
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
        <div className="py-12 text-center text-muted-foreground">Yükleniyor…</div>
      </CheckoutLayout>
    )
  }

  if (!me) {
    return (
      <CheckoutLayout currentStep="shipping">
        <div className="py-12 text-center">
          <h2 className="text-xl font-semibold mb-2">Giriş yapmanız gerekiyor.</h2>
          <p className="text-sm text-muted-foreground">
            Shipping adımına devam etmek için lütfen hesabınıza giriş yapın.
          </p>
        </div>
      </CheckoutLayout>
    )
  }

  if (isLoading) {
    return (
      <CheckoutLayout currentStep="shipping">
        <div className="py-12 text-center text-muted-foreground">Yükleniyor…</div>
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
    const toastId = toast.loading("Teslimat seçenekleri kaydediliyor...")

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
          toast.success("Teslimat bilgileri güncellendi", { id: toastId })
          router.push(`/checkout/review?session=${formValues.session_id}`)
        },
        onError: () => {
          toast.error("Teslimat bilgileri kaydedilemedi", { id: toastId })
        },
      }
    )
  }

  return (
    <CheckoutLayout currentStep="shipping" bag={data.bag}>
      <ShippingForm
        sessionId={sessionId}
        userId={me.id} 
        defaultValues={{
          shipping_address_id: data.shipping_data?.shipping_address_id,
          billing_address_id: data.billing_data?.billing_address_id,
          delivery_method: data.shipping_data?.delivery_method,
          notes: data.shipping_data?.notes ?? "",
        }}
        onSubmit={handleSubmit}
        isSubmitting={updateShipping.isPending}
        allowDifferentBilling
      />
    </CheckoutLayout>
  )
}
