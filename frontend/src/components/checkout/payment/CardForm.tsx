"use client"

import { useEffect, useMemo, useState } from "react"
import { motion } from "framer-motion"
import clsx from "clsx"

import Input from "@/components/ui/Input"
import type { CreatePaymentIntentRequest } from "@/types/checkout"

type ServerErrors = Record<string, string[]>

interface CardFormProps {
  sessionId: string
  userId: number
  defaultValues?: Partial<CreatePaymentIntentRequest>
  onSubmit: (values: CreatePaymentIntentRequest) => void
  isSubmitting?: boolean
  serverErrors?: ServerErrors
}

type FormState = {
  payment_method: CreatePaymentIntentRequest["payment_method"]
  payment_method_id?: CreatePaymentIntentRequest["payment_method_id"]
  provider: string
  card_alias: string
  card_number: string
  card_holder_name: string
  expire_month: string
  expire_year: string
  cvv: string
  save_card: boolean
  requires_3ds: boolean
}

const months = Array.from({ length: 12 }, (_, index) =>
  String(index + 1).padStart(2, "0"),
)
const years = Array.from({ length: 15 }, (_, index) =>
  String(new Date().getFullYear() + index),
)

export function CardForm({
  sessionId,
  defaultValues,
  onSubmit,
  isSubmitting = false,
  serverErrors = {},
}: CardFormProps) {
  const [formState, setFormState] = useState<FormState>(() => ({
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
    requires_3ds: defaultValues?.requires_3ds ?? false,
  }))
  const [clientErrors, setClientErrors] = useState<Record<string, string>>({})

  useEffect(() => {
    setFormState((prev) => ({
      ...prev,
      payment_method: defaultValues?.payment_method ?? prev.payment_method,
      payment_method_id: defaultValues?.payment_method_id ?? prev.payment_method_id,
      provider: defaultValues?.provider ?? prev.provider,
      card_alias: defaultValues?.card_alias ?? "",
      card_number: defaultValues?.card_number ?? "",
      card_holder_name: defaultValues?.card_holder_name ?? "",
      expire_month: defaultValues?.expire_month ?? "",
      expire_year: defaultValues?.expire_year ?? "",
      cvv: defaultValues?.cvv ?? "",
      save_card: defaultValues?.save_card ?? false,
      requires_3ds: defaultValues?.requires_3ds ?? false,
    }))
    setClientErrors({})
  }, [defaultValues])

  useEffect(() => {
    if (!formState.card_holder_name) return
    const upper = formState.card_holder_name.toUpperCase()
    if (upper !== formState.card_holder_name) {
      setFormState((prev) => ({ ...prev, card_holder_name: upper }))
    }
  }, [formState.card_holder_name])

  const isUsingSavedCard = formState.payment_method === "saved_card"

  const formattedCardNumber = useMemo(() => {
    const raw = formState.card_number.replace(/\D/g, "").slice(0, 16)
    if (!raw) return "•••• •••• •••• ••••"
    return raw.replace(/(\d{4})(?=\d)/g, "$1 ").padEnd(19, "•")
  }, [formState.card_number])

  const expPreview = formState.expire_month && formState.expire_year
    ? `${formState.expire_month.padStart(2, "0")}/${formState.expire_year.slice(-2)}`
    : "AA/YY"

  const handleChange = <K extends keyof FormState>(key: K, value: FormState[K]) => {
    setFormState((prev) => ({
      ...prev,
      [key]: value,
    }))
  }

  const handleCardNumberChange = (value: string) => {
    const digits = value.replace(/\D/g, "").slice(0, 16)
    const formatted = digits.replace(/(\d{4})(?=\d)/g, "$1 ").trim()
    handleChange("card_number", formatted)
  }

  const handleSubmit = (event: React.FormEvent) => {
    event.preventDefault()
    setClientErrors({})

    const errors: Record<string, string> = {}

    if (!isUsingSavedCard) {
      if (!formState.card_holder_name.trim()) {
        errors.card_holder_name = "Kart sahibinin adı zorunludur."
      }
      if (formState.card_number.replace(/\s/g, "").length !== 16) {
        errors.card_number = "Kart numarası 16 haneli olmalıdır."
      }
      if (!months.includes(formState.expire_month)) {
        errors.expire_month = "Geçerli bir ay seçiniz."
      }
      if (!years.includes(formState.expire_year)) {
        errors.expire_year = "Geçerli bir yıl seçiniz."
      }
      if (formState.cvv.replace(/\D/g, "").length < 3) {
        errors.cvv = "CVV en az 3 haneli olmalıdır."
      }
    }

    if (Object.keys(errors).length) {
      setClientErrors(errors)
      return
    }

    const payload: CreatePaymentIntentRequest = {
      session_id: sessionId,
      payment_method: formState.payment_method,
      payment_method_id: formState.payment_method_id,
      provider: formState.provider,
      card_alias: formState.card_alias,
      card_number: formState.card_number.replace(/\s+/g, ""),
      card_holder_name: formState.card_holder_name,
      expire_month: formState.expire_month,
      expire_year: formState.expire_year,
      cvv: formState.cvv,
      save_card: formState.save_card,
      installment: 1,
      requires_3ds: formState.requires_3ds,
    }

    onSubmit(payload)
  }

  const resolveError = (field: keyof FormState) =>
    clientErrors[field] ?? serverErrors[field]?.[0] ?? ""

  return (
    <form onSubmit={handleSubmit} className="space-y-8">
      <motion.section
        layout
        className="rounded-2xl border border-color bg-card p-6 shadow-sm"
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
      >
        <h3 className="text-sm font-semibold text-muted-foreground">Ödeme Yöntemi</h3>
        <div className="mt-4 grid gap-3 sm:grid-cols-2">
          <label
            className={clsx(
              "flex cursor-pointer items-center gap-3 rounded-lg border px-4 py-3 transition",
              formState.payment_method === "new_card"
                ? "border-[var(--accent)] bg-[var(--accent)]/10"
                : "border-color hover:border-muted",
            )}
          >
            <input
              type="radio"
              value="new_card"
              checked={formState.payment_method === "new_card"}
              onChange={() => handleChange("payment_method", "new_card")}
              className="h-4 w-4 accent-[var(--accent)]"
            />
            <div>
              <p className="text-sm font-medium">Yeni Kart</p>
              <p className="text-xs text-muted-foreground">Kart bilgilerini şimdi gir.</p>
            </div>
          </label>

          <label
            className={clsx(
              "flex cursor-pointer items-center gap-3 rounded-lg border px-4 py-3 transition",
              formState.payment_method === "saved_card"
                ? "border-[var(--accent)] bg-[var(--accent)]/10"
                : "border-color hover:border-muted",
            )}
          >
            <input
              type="radio"
              value="saved_card"
              checked={formState.payment_method === "saved_card"}
              onChange={() => handleChange("payment_method", "saved_card")}
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

        {formState.payment_method === "saved_card" && (
          <p className="mt-4 rounded-lg border border-dashed border-color bg-card/40 p-4 text-sm text-muted-foreground">
            Kayıtlı kart listesini burada gösterebilirsiniz.
          </p>
        )}
      </motion.section>

      {!isUsingSavedCard && (
        <motion.section
          layout
          className="grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,1fr)]"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
        >
          <div className="space-y-6 rounded-2xl border border-color bg-card p-6 shadow-sm">
            <h3 className="text-sm font-semibold text-muted-foreground">Kart Bilgileri</h3>

            <Input
              label="Kart Sahibinin Adı"
              placeholder="AD SOYAD"
              autoComplete="off"
              value={formState.card_holder_name}
              onChange={(value) => handleChange("card_holder_name", value.toUpperCase())}
              error={resolveError("card_holder_name")}
            />

            <Input
              label="Kart Numarası"
              placeholder="0000 0000 0000 0000"
              autoComplete="off"
              value={formState.card_number}
              onChange={handleCardNumberChange}
              error={resolveError("card_number")}
            />

            <div className="grid grid-cols-2 gap-4">
              <label className="space-y-2">
                <span className="text-xs font-medium text-muted-foreground">
                  Son Kullanma (Ay)
                </span>
                <select
                  value={formState.expire_month}
                  onChange={(event) => handleChange("expire_month", event.target.value)}
                  className="w-full rounded-lg border border-color bg-background px-3 py-2 text-sm focus:border-[var(--accent)] focus:outline-none"
                >
                  <option value="">Ay seç</option>
                  {months.map((month) => (
                    <option key={month} value={month}>
                      {month}
                    </option>
                  ))}
                </select>
                {resolveError("expire_month") && (
                  <p className="text-xs text-red-600">{resolveError("expire_month")}</p>
                )}
              </label>

              <label className="space-y-2">
                <span className="text-xs font-medium text-muted-foreground">
                  Son Kullanma (Yıl)
                </span>
                <select
                  value={formState.expire_year}
                  onChange={(event) => handleChange("expire_year", event.target.value)}
                  className="w-full rounded-lg border border-color bg-background px-3 py-2 text-sm focus:border-[var(--accent)] focus:outline-none"
                >
                  <option value="">Yıl seç</option>
                  {years.map((year) => (
                    <option key={year} value={year}>
                      {year}
                    </option>
                  ))}
                </select>
                {resolveError("expire_year") && (
                  <p className="text-xs text-red-600">{resolveError("expire_year")}</p>
                )}
              </label>
            </div>

            <Input
              label="CVV"
              placeholder="123"
              value={formState.cvv}
              onChange={(value) =>
                handleChange("cvv", value.replace(/\D/g, "").slice(0, 4))
              }
              autoComplete="off"
              error={resolveError("cvv")}
            />

            <Input
              label="Kart Takma Adı"
              placeholder="Örn: İş Kartı"
              value={formState.card_alias}
              onChange={(value) => handleChange("card_alias", value)}
              error={resolveError("card_alias")}
            />

            <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
              <label className="flex items-center gap-2 text-sm font-medium">
                <input
                  type="checkbox"
                  checked={formState.save_card}
                  onChange={(event) => handleChange("save_card", event.target.checked)}
                  className="h-4 w-4 rounded border border-color accent-[var(--accent)]"
                />
                Kartımı kaydet
              </label>

              <label className="flex items-center gap-2 text-sm text-muted-foreground">
                <input
                  type="checkbox"
                  checked={formState.requires_3ds}
                  onChange={(event) =>
                    handleChange("requires_3ds", event.target.checked)
                  }
                  className="h-4 w-4 rounded border border-color accent-[var(--accent)]"
                />
                3D Secure doğrulaması gerekli
              </label>
            </div>
          </div>

          <aside className="flex flex-col gap-5">
            <motion.div
              layout
              className="relative overflow-hidden rounded-lg bg-gradient-to-br from-[#1f2937] via-[#111827] to-[#0ea5e9] p-6 text-white shadow-lg"
            >
              <div className="flex items-center justify-between text-xs uppercase tracking-[0.3em] text-white/70">
                <span>Kart</span>
                <span>VISA</span>
              </div>

              <div className="mt-8 text-lg font-medium tracking-[0.25em]">
                {formattedCardNumber}
              </div>

              <div className="mt-6 grid grid-cols-2 gap-4 text-xs uppercase text-white/70">
                <div>
                  <p className="text-[10px]">Kart Sahibi</p>
                  <p className="text-sm text-white">
                    {formState.card_holder_name || "AD SOYAD"}
                  </p>
                </div>
                <div>
                  <p className="text-[10px]">SKT</p>
                  <p className="text-sm text-white">{expPreview}</p>
                </div>
              </div>

              <div className="absolute -top-6 right-6 h-20 w-20 rounded-full bg-white/10 blur-3xl" />
              <div className="absolute -bottom-8 left-4 h-24 w-24 rounded-full bg-[#0ea5e9]/30 blur-3xl" />
            </motion.div>

            <div className="rounded-xl bg-card/40 px-4 py-2 text-sm text-muted-foreground">
              Bu sipariş tek çekim olarak tahsil edilecektir.
            </div>
          </aside>
        </motion.section>
      )}

      <button
        type="submit"
        disabled={isSubmitting}
        className="w-full cursor-pointer rounded-xl bg-[var(--accent)] py-3 text-sm font-semibold text-white transition hover:bg-[var(--accent-dark)] disabled:opacity-60"
      >
        {isSubmitting ? "İşleniyor..." : "Ödemeyi Tamamla"}
      </button>
    </form>
  )
}
