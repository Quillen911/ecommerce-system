"use client"

import { useEffect, useState } from "react"
import { useRouter, useSearchParams } from "next/navigation"
import { toast } from "sonner"

import { CheckoutLayout } from "@/components/checkout/layout/CheckoutLayout"
import { useMe } from "@/hooks/useAuthQuery"
import { useCheckoutSession } from "@/hooks/checkout/useCheckoutSession"
import { useCreatePaymentIntent } from "@/hooks/checkout/usePaymentMethods"
import { Link } from "lucide-react"
import { CreatePaymentIntentRequest } from "@/types/checkout"
import { CardForm } from "@/components/checkout/payment/CardForm"

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
        router.push('/bag')
        return (
            <CheckoutLayout currentStep="payment">
                <div className="py-12 text-center">
                    <h2 className="text-xl font-semibold mb-2">Session bulunamadı.</h2>
                    <p className="text-sm text-muted-foreground">
                        Lütfen sepet sayfasına dönüp tekrar işlem yapınız.
                    </p>
                </div>
            </CheckoutLayout>
        )
    }

    return <PaymentContent sessionId={resolvedSessionId} />

    function PaymentContent({ sessionId }: { sessionId: string }) {
        const { data: me, isLoading: meLoading } = useMe()
        const { data, isLoading, isError } = useCheckoutSession(sessionId)
        const createPaymentIntent = useCreatePaymentIntent()

        if (meLoading) {
            return (
                <CheckoutLayout currentStep="payment">
                    <div className="py-12 text-center text-muted-foreground">Yükleniyor…</div>
                </CheckoutLayout>
            )
        }

        if (!me) {
            return (
                <CheckoutLayout currentStep="payment">
                    <div className="py-12 text-center">
                        <h2 className="text-xl font-semibold mb-2">Giriş yapmanız gerekiyor.</h2>
                        <p className="text-sm text-muted-foreground">
                            Payment adımına devam etmek için lütfen hesabınıza giriş yapın.
                        </p>
                    </div>
                </CheckoutLayout>
            )
        }

        if (isLoading) {
            return (
                <CheckoutLayout currentStep="payment">
                    <div className="py-12 text-center text-muted-foreground">Yükleniyor…</div>
                </CheckoutLayout>
            )
        }

        if (isError || !data) {
            router.push('/bag')
            return (
                <CheckoutLayout currentStep="payment">
                    <div className="py-12 text-center">
                        <h2 className="text-xl font-semibold mb-2">Session bulunamadı.</h2>
                        <p className="text-sm text-muted-foreground">
                            Lütfen sepet sayfasından checkout akışını yeniden başlatın.
                        </p>
                        <Link href="/bag">Sepete Git</Link>
                    </div>
                </CheckoutLayout>
            )
        }
        const paymentDefaults = {
        payment_method: data.payment_data?.method ?? "new_card",
        provider: data.payment_data?.provider ?? "iyzico",
        card_alias: "",
        card_number: "",
        card_holder_name: "",
        expire_month: "",
        expire_year: "",
        cvv: "",
        save_card: false,
        installment: data.payment_data?.installment ?? 1,
        requires_3ds: data.payment_data?.intent?.requires_3ds ?? false,
        }
        const handleSubmit = (values: CreatePaymentIntentRequest) => {
            const payload = {...values, session_id: sessionId}
            const toastId = toast.loading("Ödeme işlemi tamamlanıyor...")

            createPaymentIntent.mutate(payload, {
                onSuccess: () => {
                    router.push(`/checkout/success?session=${sessionId}`)
                },
                onError: () => {
                    toast.error("Ödeme işlemi tamamlanamadı.", { id: toastId })
                },
            })
        }
        return (
            <CheckoutLayout currentStep="payment" bag={data.bag}>
                <CardForm
                sessionId={sessionId}
                userId={me.id}
                defaultValues={paymentDefaults}
                onSubmit={handleSubmit}
                isSubmitting={createPaymentIntent.isPending}
                />
            </CheckoutLayout>
        )
    }
}