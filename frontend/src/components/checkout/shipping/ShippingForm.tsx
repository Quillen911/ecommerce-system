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
    if (issue.path.length > 0) {
      acc[issue.path.join(".")] = issue.message
    }
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
  const [shippingAddressId, setShippingAddressId] = useState<number | null>(
    defaultValues?.shipping_address_id ?? null,
  )
  const [billingAddressId, setBillingAddressId] = useState<number | null>(
    defaultValues?.billing_address_id ?? null,
  )
  const [deliveryMethod, setDeliveryMethod] = useState<ShippingFormValues["delivery_method"]>(
    defaultValues?.delivery_method ?? "standard",
  )
  const [notes, setNotes] = useState(defaultValues?.notes ?? "")
  const [useDifferentBilling, setUseDifferentBilling] = useState(
    defaultValues?.use_different_billing ?? false,
  )
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
    if (!useDifferentBilling && shippingAddressId) {
      setBillingAddressId(shippingAddressId)
    }
  }, [useDifferentBilling, shippingAddressId])

  const aggregatedErrors = useMemo(() => Object.values(fieldErrors).filter(Boolean), [fieldErrors])

  const handleSubmit = (event: React.FormEvent) => {
    event.preventDefault()
    setFieldErrors({})

    const normalizedShippingId = shippingAddressId ?? 0
    const normalizedBillingId = useDifferentBilling
      ? billingAddressId ?? 0
      : shippingAddressId ?? 0

    const payload: ShippingFormValues = {
      session_id: sessionId,
      shipping_address_id: normalizedShippingId,
      delivery_method: deliveryMethod,
      notes: notes.trim() ? notes.trim() : undefined,
      use_different_billing: allowDifferentBilling ? useDifferentBilling : false,
      billing_address_id: allowDifferentBilling ? normalizedBillingId : shippingAddressId ?? undefined,
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
            className="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700"
          >
            {aggregatedErrors[0]}
            {aggregatedErrors.length > 1 && (
              <ul className="mt-2 list-disc pl-4">
                {aggregatedErrors.slice(1).map((message, index) => (
                  <li key={index}>{message}</li>
                ))}
              </ul>
            )}
          </motion.div>
        )}
      </AnimatePresence>

      <motion.section
        layout
        className="space-y-4 rounded-2xl border border-color bg-card p-6 shadow-sm"
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
      >
        <h2 className="text-lg font-semibold">Teslimat Adresi</h2>
        <AddressSelector
          userId={userId}
          selectedAddressId={shippingAddressId ?? undefined}
          onSelect={(address) => {
            setShippingAddressId(address.id)
            if (!useDifferentBilling) {
              setBillingAddressId(address.id)
            }
            setFieldErrors((prev) => ({ ...prev, shipping_address_id: "" }))
          }}
        />
        {fieldErrors.shipping_address_id && (
          <p className="text-sm text-red-600">{fieldErrors.shipping_address_id}</p>
        )}
      </motion.section>

      {allowDifferentBilling && (
        <motion.section
          layout
          className="space-y-4 rounded-2xl border border-dashed border-color bg-card/40 p-6"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
        >
          <label className="flex items-center gap-2 text-sm font-medium">
            <input
              type="checkbox"
              checked={useDifferentBilling}
              onChange={(event) => setUseDifferentBilling(event.target.checked)}
              className="h-4 w-4 rounded border border-color accent-[var(--accent)]"
            />
            Farklı bir fatura adresi kullan
          </label>

          <AnimatePresence initial={false}>
            {useDifferentBilling && (
              <motion.div
                key="billing-selector"
                initial={{ opacity: 0, y: -8 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: -8 }}
                className="space-y-4"
              >
                <AddressSelector
                  userId={userId}
                  selectedAddressId={billingAddressId ?? undefined}
                  onSelect={(address) => {
                    setBillingAddressId(address.id)
                    setFieldErrors((prev) => ({ ...prev, billing_address_id: "" }))
                  }}
                />
                {fieldErrors.billing_address_id && (
                  <p className="text-sm text-red-600">{fieldErrors.billing_address_id}</p>
                )}
              </motion.div>
            )}
          </AnimatePresence>
        </motion.section>
      )}

      <motion.section
        layout
        className="space-y-4 rounded-2xl border border-color bg-card p-6 shadow-sm"
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
      >
        <h2 className="text-lg font-semibold">Teslimat Seçenekleri</h2>
        <div className="grid gap-4 md:grid-cols-2">
          <DeliveryMethodCard
            method={{ id: "standard", label: "Standart" }}
            isSelected={deliveryMethod === "standard"}
            onSelect={setDeliveryMethod}
          />
        </div>
        {fieldErrors.delivery_method && (
          <p className="text-sm text-red-600">{fieldErrors.delivery_method}</p>
        )}
      </motion.section>

      <motion.section
        layout
        className="space-y-2 rounded-2xl border border-color bg-card p-6 shadow-sm"
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
      >
        <label className="text-sm font-medium" htmlFor="checkout-notes">
          Teslimat Notu (isteğe bağlı)
        </label>
        <textarea
          id="checkout-notes"
          rows={3}
          value={notes}
          onChange={(event) => setNotes(event.target.value)}
          className="w-full rounded-lg border border-color bg-transparent px-3 py-2 text-sm outline-none focus:border-[var(--accent)]"
          placeholder="Örn: Akşam 18:00'den sonra teslim edilsin."
        />
        {fieldErrors.notes && <p className="text-sm text-red-600">{fieldErrors.notes}</p>}
      </motion.section>

      <div className="flex justify-end">
        <button
          type="submit"
          disabled={isSubmitting}
          className="cursor-pointer rounded-xl bg-black px-6 py-3 text-sm font-semibold text-white transition hover:bg-[var(--accent-dark)] disabled:opacity-60"
        >
          {isSubmitting ? "Kaydediliyor..." : "Devam Et"}
        </button>
      </div>
    </form>
  )
}
