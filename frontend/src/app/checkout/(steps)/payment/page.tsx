"use client"

import { useEffect, useState } from "react"
import Link from "next/link"
import { useRouter, useSearchParams } from "next/navigation"
import { toast } from "sonner"

import { CheckoutLayout } from "@/components/checkout/layout/CheckoutLayout"
import { CardForm } from "@/components/checkout/payment/CardForm"
import { StepBackButton } from "@/components/checkout/layout/StepBackButton"

import { useMe } from "@/hooks/useAuthQuery"
import { useCheckoutSession } from "@/hooks/checkout/useCheckoutSession"
import { useCreatePaymentIntent } from "@/hooks/checkout/usePaymentMethods"

import type { CreatePaymentIntentRequest } from "@/types/checkout"

type AxiosErrorLike = {
  isAxiosError: boolean
  response?: {
    status?: number
    data?: {
      errors?: Record<string, string[]>
    }
  }
}

const isAxiosError = (error: unknown): error is AxiosErrorLike => {
  return (
    typeof error === "object" &&
    error !== null &&
    "isAxiosError" in error &&
    (error as AxiosErrorLike).isAxiosError === true
  )
}

export default function PaymentStepPage() {
  const searchParams = useSearchParams()
  const router = useRouter()
  const [resolvedSessionId, setResolvedSessionId] = useState<string | null>(null)

  useEffect(() => {
    setResolvedSessionId(searchParams.get("session"))
  }, [searchParams])

  if (resolvedSessionId === null) {
    return (
      <CheckoutLayout currentStep="payment">
        <div className="py-12 text-center text-muted-foreground">Yükleniyor…</div>
      </CheckoutLayout>
    )
  }

  if (!resolvedSessionId) {
    router.push("/bag")
    return (
      <CheckoutLayout currentStep="payment">
        <div className="py-12 text-center">
          <h2 className="mb-2 text-xl font-semibold">Session bulunamadı.</h2>
          <p className="text-sm text-muted-foreground">
            Lütfen sepet sayfasına dönüp tekrar işlem yapınız.
          </p>
          <Link href="/bag" className="mt-4 inline-block text-[var(--accent)] underline">
            Sepete dön
          </Link>
        </div>
      </CheckoutLayout>
    )
  }

  return <PaymentContent sessionId={resolvedSessionId} />
}

function PaymentContent({ sessionId }: { sessionId: string }) {
  const router = useRouter()
  const { data: me, isLoading: meLoading } = useMe()
  const { data, isLoading, isError } = useCheckoutSession(sessionId)
  const createPaymentIntent = useCreatePaymentIntent()
  const [formErrors, setFormErrors] = useState<Record<string, string[]>>({})

  useEffect(() => {
    if (!meLoading && (!me || isError)) {
      router.push("/bag")
    }
  }, [meLoading, me, isError, router])

  if (meLoading || isLoading) {
    return (
      <CheckoutLayout currentStep="payment">
        <div className="py-12 text-center text-muted-foreground">Yükleniyor…</div>
      </CheckoutLayout>
    )
  }

  if (!me || !data) {
    return (
      <CheckoutLayout currentStep="payment">
        <div className="py-12 text-center">
          <h2 className="mb-2 text-xl font-semibold">Session bulunamadı.</h2>
          <Link href="/bag" className="text-[var(--accent)] underline">
            Sepete dön
          </Link>
        </div>
      </CheckoutLayout>
    )
  }

  const paymentDefaults: Partial<CreatePaymentIntentRequest> = {
    payment_method: data.payment_data?.method ?? "new_card",
    provider: data.payment_data?.provider ?? "iyzico",
    card_alias: "",
    card_number: "",
    card_holder_name: "",
    expire_month: "",
    expire_year: "",
    cvv: "",
    save_card: false,
    installment: 1,
    requires_3ds: data.payment_data?.intent?.requires_3ds ?? false,
  }

  const mountThreeDsForm = (html: string) => {
    const wrapperId = "iyzico-3ds-wrapper"
    document.getElementById(wrapperId)?.remove()

    const wrapper = document.createElement("div")
    wrapper.id = wrapperId
    wrapper.style.position = "fixed"
    wrapper.style.inset = "0"
    wrapper.style.zIndex = "9999"
    wrapper.style.backgroundColor = "rgba(0,0,0,0.55)"
    wrapper.style.display = "flex"
    wrapper.style.alignItems = "center"
    wrapper.style.justifyContent = "center"
    wrapper.innerHTML = html

    document.body.appendChild(wrapper)
    wrapper.querySelector("form")?.submit()
  }

  const handleSubmit = (values: CreatePaymentIntentRequest) => {
    const normalizedYear =
      values.expire_year && values.expire_year.length === 2
        ? `20${values.expire_year}`
        : values.expire_year

    const normalizedMonth =
      values.expire_month && values.expire_month.length === 1
        ? values.expire_month.padStart(2, "0")
        : values.expire_month

    const payload = {
      ...values,
      session_id: sessionId,
      expire_year: normalizedYear ?? "",
      expire_month: normalizedMonth ?? "",
    }

    setFormErrors({})
    const toastId = toast.loading("Ödeme işlemi tamamlanıyor...")

    createPaymentIntent.mutate(payload, {
      onSuccess: (response) => {
        toast.dismiss(toastId)
        const intent = response.payment_data?.intent
        if (intent?.requires_3ds && intent.three_ds_html) {
          mountThreeDsForm(intent.three_ds_html)
          return
        }
        toast.success("Ödeme başarıyla tamamlandı.")
        router.push(`/checkout/success?session=${sessionId}`)
      },
      onError: (error) => {
        toast.dismiss(toastId)
        if (isAxiosError(error) && error.response?.status === 422) {
          setFormErrors(error.response.data?.errors ?? {})
          return
        }
        console.error("createPaymentIntent error:", isAxiosError(error) ? error.response?.data : error)
      },
    })
  }

  return (
    <CheckoutLayout currentStep="payment" bag={data.bag}>
      <div className="mb-6 flex items-center justify-between">
        <StepBackButton fallbackHref="/checkout/shipping" />
      </div>
      <CardForm
        sessionId={sessionId}
        userId={me.id}
        defaultValues={paymentDefaults}
        onSubmit={handleSubmit}
        isSubmitting={createPaymentIntent.isPending}
        serverErrors={formErrors}
      />
    </CheckoutLayout>
  )
}
