"use client"

import { useEffect, useMemo } from "react"
import { useForm } from "react-hook-form"
import { zodResolver } from "@hookform/resolvers/zod"
import { clsx } from "clsx"

import { paymentSchema } from "@/schemas/checkout/paymentSchema"
import type { CreatePaymentIntentRequest } from "@/types/checkout"

interface CardFormProps {
  sessionId: string
  userId: number
  defaultValues?: Partial<CreatePaymentIntentRequest>
  onSubmit: (values: CreatePaymentIntentRequest) => void
  isSubmitting?: boolean
}

export function CardForm({
  sessionId,
  userId,
  defaultValues,
  onSubmit,
  isSubmitting = false,
}: CardFormProps) {
  const {
    register,
    handleSubmit,
    watch,
    setValue,
    formState: { errors },
    reset,
  } = useForm<CreatePaymentIntentRequest>({
    resolver: zodResolver(paymentSchema),
    defaultValues: {
      session_id: sessionId,
      payment_method: defaultValues?.payment_method ?? "new_card",
      payment_method_id: defaultValues?.payment_method_id,
      provider: defaultValues?.provider ?? "iyzico",
      card_alias: defaultValues?.card_alias ?? "",
      card_number: defaultValues?.card_number ?? "",
      card_holder_name: defaultValues?.card_holder_name ?? "",
      expire_month: defaultValues?.expire_month ?? "",
      expire_year: defaultValues?.expire_year ?? "",
      cvv: defaultValues?.cvv ?? "",
      save_card: defaultValues?.save_card ?? false,
      installment: defaultValues?.installment ?? 1,
      requires_3ds: defaultValues?.requires_3ds ?? false,
    },
  })

  useEffect(() => {
    reset((prev) => ({
      ...prev,
      session_id: sessionId,
      payment_method: defaultValues?.payment_method ?? prev.payment_method,
      payment_method_id: defaultValues?.payment_method_id ?? prev.payment_method_id,
      provider: defaultValues?.provider ?? prev.provider,
      card_alias: defaultValues?.card_alias ?? prev.card_alias,
      card_number: defaultValues?.card_number ?? prev.card_number,
      card_holder_name: defaultValues?.card_holder_name ?? prev.card_holder_name,
      expire_month: defaultValues?.expire_month ?? prev.expire_month,
      expire_year: defaultValues?.expire_year ?? prev.expire_year,
      cvv: defaultValues?.cvv ?? prev.cvv,
      save_card: defaultValues?.save_card ?? prev.save_card,
      installment: 1,
      requires_3ds: defaultValues?.requires_3ds ?? prev.requires_3ds,
    }))
  }, [sessionId, defaultValues, reset])

  const watchedFields = watch()
  const isUsingSavedCard = watchedFields.payment_method === "saved_card"

  const formattedCardNumber = useMemo(() => {
    const raw = watchedFields.card_number?.replace(/\s+/g, "") ?? ""
    if (!raw) return "•••• •••• •••• ••••"
    return raw.replace(/(\d{4})(?=\d)/g, "$1 ").padEnd(19, "•")
  }, [watchedFields.card_number])

  const cardHolderPreview = watchedFields.card_holder_name || "AD SOYAD"
  const expPreview =
    watchedFields.expire_month && watchedFields.expire_year
      ? `${watchedFields.expire_month.padStart(2, "0")}/${watchedFields.expire_year
          .toString()
          .slice(-2)}`
      : "AA/YY"

  const internalSubmit = (values: CreatePaymentIntentRequest) => {
    onSubmit({
      ...values,
      session_id: sessionId,
      installment: 1,
    })
  }

  return (
    <form className="space-y-8" onSubmit={handleSubmit(internalSubmit)}>
      <section className="rounded-2xl border border-color bg-card p-6 shadow-sm">
        <h3 className="text-sm font-semibold text-muted-foreground">Ödeme Yöntemi</h3>
        <div className="mt-4 grid gap-3 sm:grid-cols-2">
          <label
            className={clsx(
              "flex cursor-pointer items-center gap-3 rounded-lg border px-4 py-3 transition",
              watchedFields.payment_method === "new_card"
                ? "border-[var(--accent)] bg-[var(--accent)]/10"
                : "border-color hover:border-muted",
            )}
          >
            <input
              type="radio"
              value="new_card"
              {...register("payment_method")}
              className="h-4 w-4 accent-[var(--accent)]"
            />
            <div>
              <p className="text-sm font-medium">Yeni Kart</p>
              <p className="text-xs text-muted-foreground">
                Kart bilgilerini şimdi gir.
              </p>
            </div>
          </label>

          <label
            className={clsx(
              "flex cursor-pointer items-center gap-3 rounded-lg border px-4 py-3 transition",
              watchedFields.payment_method === "saved_card"
                ? "border-[var(--accent)] bg-[var(--accent)]/10"
                : "border-color hover:border-muted",
            )}
          >
            <input
              type="radio"
              value="saved_card"
              {...register("payment_method")}
              className="h-4 w-4 accent-[var(--accent)]"
            />
            <div>
              <p className="text-sm font-medium">Kayıtlı Kart</p>
              <p className="text-xs text-muted-foreground">
                Daha önce kaydettiğin kartı seç.
              </p>
            </div>
          </label>
        </div>
        {errors.payment_method && (
          <p className="mt-2 text-xs text-red-600">{errors.payment_method.message}</p>
        )}
      </section>

      {isUsingSavedCard ? (
        <section className="rounded-2xl border border-dashed border-color bg-card/40 p-6">
          <h3 className="text-sm font-semibold text-muted-foreground">Kayıtlı Kart Seç</h3>
          <input
            type="number"
            placeholder="Kayıtlı kart ID"
            className="mt-3 w-full rounded-lg border border-color px-3 py-2 text-sm focus:border-[var(--accent)] focus:outline-none"
            {...register("payment_method_id", { valueAsNumber: true })}
          />
          {errors.payment_method_id && (
            <p className="mt-2 text-xs text-red-600">{errors.payment_method_id.message}</p>
          )}

          <label className="mt-4 flex items-center gap-2 text-sm text-muted-foreground">
            <input
              type="checkbox"
              {...register("requires_3ds")}
              className="h-4 w-4 rounded border border-color accent-[var(--accent)]"
            />
            3D Secure doğrulaması gerekli
          </label>
        </section>
      ) : (
        <section className="grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,1fr)]">
          <div className="space-y-6 rounded-2xl border border-color bg-card p-6 shadow-sm">
            <h3 className="text-sm font-semibold text-muted-foreground">Kart Bilgileri</h3>

            <label className="space-y-2">
              <span className="text-xs font-medium text-muted-foreground">
                Kart Sahibinin Adı
              </span>
              <input
                placeholder="Ad Soyad"
                className="w-full rounded-lg border border-color px-3 py-2 text-sm focus:border-[var(--accent)] focus:outline-none"
                {...register("card_holder_name")}
              />
              {errors.card_holder_name && (
                <p className="text-xs text-red-600">{errors.card_holder_name.message}</p>
              )}
            </label>

            <label className="space-y-2">
              <span className="text-xs font-medium text-muted-foreground">Kart Numarası</span>
              <input
                placeholder="0000 0000 0000 0000"
                className="w-full rounded-lg border border-color px-3 py-2 text-sm focus:border-[var(--accent)] focus:outline-none tracking-[0.2em]"
                {...register("card_number")}
              />
              {errors.card_number && (
                <p className="text-xs text-red-600">{errors.card_number.message}</p>
              )}
            </label>

            <div className="grid grid-cols-2 gap-4">
              <label className="space-y-2">
                <span className="text-xs font-medium text-muted-foreground">Son Kullanma (Ay)</span>
                <input
                  placeholder="AA"
                  className="w-full rounded-lg border border-color px-3 py-2 text-sm focus:border-[var(--accent)] focus:outline-none"
                  {...register("expire_month")}
                />
                {errors.expire_month && (
                  <p className="text-xs text-red-600">{errors.expire_month.message}</p>
                )}
              </label>

              <label className="space-y-2">
                <span className="text-xs font-medium text-muted-foreground">Son Kullanma (Yıl)</span>
                <input
                  placeholder="YY"
                  className="w-full rounded-lg border border-color px-3 py-2 text-sm focus:border-[var(--accent)] focus:outline-none"
                  {...register("expire_year")}
                />
                {errors.expire_year && (
                  <p className="text-xs text-red-600">{errors.expire_year.message}</p>
                )}
              </label>
            </div>

            <label className="space-y-2">
              <span className="text-xs font-medium text-muted-foreground">CVV</span>
              <input
                placeholder="123"
                className="w-full rounded-lg border border-color px-3 py-2 text-sm focus:border-[var(--accent)] focus:outline-none"
                {...register("cvv")}
              />
              {errors.cvv && <p className="text-xs text-red-600">{errors.cvv.message}</p>}
            </label>

            <label className="space-y-2">
              <span className="text-xs font-medium text-muted-foreground">Kart Takma Adı</span>
              <input
                placeholder="Örn: İş Kartı"
                className="w-full rounded-lg border border-color px-3 py-2 text-sm focus:border-[var(--accent)] focus:outline-none"
                {...register("card_alias")}
              />
              {errors.card_alias && (
                <p className="text-xs text-red-600">{errors.card_alias.message}</p>
              )}
            </label>

            <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
              <label className="flex items-center gap-2 text-sm font-medium">
                <input
                  type="checkbox"
                  {...register("save_card")}
                  className="h-4 w-4 rounded border border-color accent-[var(--accent)]"
                />
                Kartımı kaydet
              </label>

              <label className="flex items-center gap-2 text-sm text-muted-foreground">
                <input
                  type="checkbox"
                  {...register("requires_3ds")}
                  className="h-4 w-4 rounded border border-color accent-[var(--accent)]"
                />
                3D Secure doğrulaması gerekli
              </label>
            </div>
          </div>

          <aside className="flex flex-col gap-5">
            <div className="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#1f2937] via-[#111827] to-[#0ea5e9] p-6 text-white shadow-lg">
              <div className="flex items-center justify-between text-xs uppercase tracking-[0.3em] text-white/70">
                <span>Bagisa</span>
                <span>Ödeme</span>
              </div>

              <div className="mt-8 text-lg font-medium tracking-[0.25em]">
                {formattedCardNumber}
              </div>

              <div className="mt-6 grid grid-cols-2 gap-4 text-xs uppercase text-white/70">
                <div>
                  <p className="text-[10px]">Kart Sahibi</p>
                  <p className="text-sm text-white">{cardHolderPreview}</p>
                </div>
                <div>
                  <p className="text-[10px]">SKT</p>
                  <p className="text-sm text-white">{expPreview}</p>
                </div>
              </div>

              <div className="absolute -top-6 right-6 h-20 w-20 rounded-full bg-white/10 blur-3xl" />
              <div className="absolute -bottom-8 left-4 h-24 w-24 rounded-full bg-[#0ea5e9]/30 blur-3xl" />
            </div>

            <div className="rounded-2xl border border-dashed border-color bg-card/40 p-4 text-sm text-muted-foreground">
              <p className="font-medium text-foreground">Taksit Bilgisi</p>
              <p className="mt-1">Bu sipariş tek çekim (%100) olarak tahsil edilecektir.</p>
              <p className="mt-1 text-xs">
                Taksit seçeneği ileride eklenecek; şimdilik tercihen tek ödeme yapılır.
              </p>
            </div>
          </aside>
        </section>
      )}

      <button
        type="submit"
        className="w-full rounded-xl bg-[var(--accent)] py-3 text-sm font-semibold text-white transition hover:bg-[var(--accent-dark)] disabled:opacity-60"
        disabled={isSubmitting}
      >
        {isSubmitting ? "İşleniyor..." : "Ödemeyi Tamamla"}
      </button>
    </form>
  )
}
