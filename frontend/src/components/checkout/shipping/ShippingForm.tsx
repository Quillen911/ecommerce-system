"use client"

import { useEffect, useMemo, useState } from "react"
import { AnimatePresence, motion } from "framer-motion"
import { z } from "zod"

import AddressSelector from "@/components/forms/AddressSelector"
import Input from "@/components/ui/Input"
import { DeliveryMethodCard } from "./DeliveryMethodCard"
import { shippingSchema, type ShippingFormValues } from "@/schemas/checkout/shippingSchema"

type FieldErrors = Record<string, string>

interface ShippingFormProps {
  sessionId: string
  userId: number
  defaultValues?: Partial<ShippingFormValues>
  onSubmit: (values: ShippingFormValues) => void
  isSubmitting?: boolean
  allowDifferentBilling?: boolean
}

const flattenZodErrors = (issues: z.ZodIssue[]): FieldErrors =>
  issues.reduce<FieldErrors>((acc, issue) => {
    if (issue.path.length > 0) acc[issue.path.join(".")] = issue.message
    return acc
  }, {})

export function ShippingForm({
  sessionId,
  userId,
  defaultValues,
  onSubmit,
  isSubmitting = false,
  allowDifferentBilling = false,
}: ShippingFormProps) {
  const [shippingAddressId, setShippingAddressId] = useState<number | null>(defaultValues?.shipping_address_id ?? null)
  const [billingAddressId, setBillingAddressId] = useState<number | null>(defaultValues?.billing_address_id ?? null)
  const [deliveryMethod, setDeliveryMethod] = useState<ShippingFormValues["delivery_method"]>(defaultValues?.delivery_method ?? "standard")
  const [notes, setNotes] = useState(defaultValues?.notes ?? "")
  const [useDifferentBilling, setUseDifferentBilling] = useState(defaultValues?.use_different_billing ?? false)
  const [fieldErrors, setFieldErrors] = useState<FieldErrors>({})

  useEffect(() => {
    setShippingAddressId(defaultValues?.shipping_address_id ?? null)
    setBillingAddressId(defaultValues?.billing_address_id ?? null)
    setDeliveryMethod(defaultValues?.delivery_method ?? "standard")
    setNotes(defaultValues?.notes ?? "")
    setUseDifferentBilling(defaultValues?.use_different_billing ?? false)
    setFieldErrors({})
  }, [defaultValues])

  useEffect(() => {
    if (!useDifferentBilling && shippingAddressId) setBillingAddressId(shippingAddressId)
  }, [useDifferentBilling, shippingAddressId])

  const aggregatedErrors = useMemo(() => Object.values(fieldErrors).filter(Boolean), [fieldErrors])

  const handleSubmit = (event: React.FormEvent) => {
    event.preventDefault()
    setFieldErrors({})

    const payload: ShippingFormValues = {
      session_id: sessionId,
      shipping_address_id: shippingAddressId ?? 0,
      billing_address_id: allowDifferentBilling
        ? (useDifferentBilling ? billingAddressId ?? 0 : shippingAddressId ?? 0)
        : shippingAddressId ?? undefined,
      delivery_method: deliveryMethod,
      notes: notes.trim() || undefined,
      use_different_billing: allowDifferentBilling ? useDifferentBilling : false,
    }

    const parsed = shippingSchema.safeParse(payload)
    if (!parsed.success) {
      setFieldErrors(flattenZodErrors(parsed.error.issues))
      return
    }

    onSubmit(parsed.data)
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-10">
      <AnimatePresence initial={false}>
        {aggregatedErrors.length > 0 && (
          <motion.div
            key="shipping-errors"
            initial={{ opacity: 0, y: -10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -10 }}
            className="rounded-lg border border-red-400 bg-red-50 p-4 text-sm text-red-700 shadow-sm"
          >
            <strong>Hata:</strong>
            <ul className="mt-2 list-disc pl-5 space-y-1">
              {aggregatedErrors.map((msg, i) => (
                <li key={i}>{msg}</li>
              ))}
            </ul>
          </motion.div>
        )}
      </AnimatePresence>

      <motion.section layout className="surface rounded-2xl border border-color bg-card p-6 shadow-md animate-fadeInUp">
        <h2 className="text-lg font-semibold text-[var(--accent)]">Teslimat Adresi</h2>
        <AddressSelector
          userId={userId}
          selectedAddressId={shippingAddressId ?? undefined}
          onSelect={(address) => {
            setShippingAddressId(address.id)
            if (!useDifferentBilling) setBillingAddressId(address.id)
            setFieldErrors((prev) => ({ ...prev, shipping_address_id: "" }))
          }}
        />
        {fieldErrors.shipping_address_id && <p className="text-sm text-red-600 mt-1">{fieldErrors.shipping_address_id}</p>}
      </motion.section>

      {allowDifferentBilling && (
        <motion.section layout className="rounded-2xl border border-dashed border-color bg-card/50 p-6 shadow-sm animate-fadeInUp">
          <label className="flex items-center gap-2 text-sm font-medium cursor-pointer">
            <input
              type="checkbox"
              checked={useDifferentBilling}
              onChange={(e) => setUseDifferentBilling(e.target.checked)}
              className="h-4 w-4 accent-[var(--accent)] rounded"
            />
            Farklı bir fatura adresi kullan
          </label>

          <AnimatePresence>
            {useDifferentBilling && (
              <motion.div
                key="billing-selector"
                initial={{ opacity: 0, y: -8 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: -8 }}
                className="mt-4"
              >
                <AddressSelector
                  userId={userId}
                  selectedAddressId={billingAddressId ?? undefined}
                  onSelect={(address) => {
                    setBillingAddressId(address.id)
                    setFieldErrors((prev) => ({ ...prev, billing_address_id: "" }))
                  }}
                />
                {fieldErrors.billing_address_id && <p className="text-sm text-red-600 mt-1">{fieldErrors.billing_address_id}</p>}
              </motion.div>
            )}
          </AnimatePresence>
        </motion.section>
      )}

      <motion.section layout className="surface rounded-2xl border border-color bg-card p-6 shadow-md animate-fadeInUp">
        <h2 className="text-lg font-semibold text-[var(--accent)]">Teslimat Seçenekleri</h2>
        <div className="grid gap-4 md:grid-cols-2 mt-3">
          <DeliveryMethodCard
            method={{ id: "standard", label: "Standart Teslimat" }}
            isSelected={deliveryMethod === "standard"}
            onSelect={setDeliveryMethod}
          />
        </div>
        {fieldErrors.delivery_method && <p className="text-sm text-red-600 mt-1">{fieldErrors.delivery_method}</p>}
      </motion.section>

      <motion.section layout className="surface rounded-2xl border border-color bg-card p-6 shadow-md animate-fadeInUp">
        <label className="text-sm font-medium text-[var(--accent)]" htmlFor="checkout-notes">
          Teslimat Notu (isteğe bağlı)
        </label>
        <textarea
          id="checkout-notes"
          rows={3}
          value={notes}
          onChange={(e) => setNotes(e.target.value)}
          className="w-full mt-2 rounded-lg border border-color bg-transparent px-3 py-2 text-sm outline-none focus:border-[var(--accent)]"
          placeholder="Örn: Akşam 18:00'den sonra teslim edilsin."
        />
        {fieldErrors.notes && <p className="text-sm text-red-600 mt-1">{fieldErrors.notes}</p>}
      </motion.section>

      <div className="flex justify-end">
        <button
          type="submit"
          disabled={isSubmitting}
          className="cursor-pointer rounded-xl bg-[var(--accent)] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[var(--accent-dark)] disabled:opacity-60"
        >
          {isSubmitting ? "Kaydediliyor..." : "Devam Et"}
        </button>
      </div>
    </form>
  )
}
