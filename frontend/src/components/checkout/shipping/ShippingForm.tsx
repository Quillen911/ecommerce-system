"use client"

import { useEffect } from "react"
import { useForm } from "react-hook-form"
import { zodResolver } from "@hookform/resolvers/zod"

import { ShippingFormValues, shippingSchema } from "@/schemas/checkout/shippingSchema"
import AddressSelector from "@/components/forms/AddressSelector"
import { DeliveryMethodCard } from "./DeliveryMethodCard"

interface ShippingFormProps {
  sessionId: string
  userId: number
  defaultValues?: Partial<ShippingFormValues>
  onSubmit: (values: ShippingFormValues) => void
  isSubmitting?: boolean
  allowDifferentBilling?: boolean
}

export function ShippingForm({
  sessionId,
  userId,
  defaultValues,
  onSubmit,
  isSubmitting = false,
  allowDifferentBilling = false,
}: ShippingFormProps) {
  const {
    register,
    handleSubmit,
    watch,
    setValue,
    formState: { errors },
  } = useForm<ShippingFormValues>({
    resolver: zodResolver(shippingSchema),
    defaultValues: {
      session_id: sessionId,
      shipping_address_id: defaultValues?.shipping_address_id,
      billing_address_id: defaultValues?.billing_address_id,
      delivery_method: defaultValues?.delivery_method,
      notes: defaultValues?.notes ?? "",
      use_different_billing: defaultValues?.use_different_billing ?? false,
    },
  })

  const watchedShippingAddress = watch("shipping_address_id")
  const watchedBillingAddress = watch("billing_address_id")
  const useDifferentBilling = watch("use_different_billing")

  const watchedDeliveryMethod = watch("delivery_method")

  useEffect(() => {
    if (!watchedShippingAddress ) {
      setValue("shipping_address_id", watchedShippingAddress)
    }
  }, [setValue, watchedShippingAddress])

  useEffect(() => {
    if (!useDifferentBilling) {
      setValue("billing_address_id", watchedBillingAddress)
    } else if (!watch("billing_address_id") && watchedBillingAddress) {
      setValue("billing_address_id", watchedBillingAddress)
    }
  }, [setValue, useDifferentBilling, watch])


  const internalSubmit = (values: ShippingFormValues) => {
    onSubmit({
      ...values,
      notes: values.notes?.trim() ? values.notes.trim() : undefined,
    })
  }

  return (
    <form className="space-y-8" onSubmit={handleSubmit(internalSubmit)}>
      <section className="space-y-4">
        <h2 className="text-lg font-semibold">Teslimat Adresi</h2>
        <AddressSelector
          userId={userId}
          selectedAddressId={watchedShippingAddress ?? undefined}
          onSelect={(address) => {
            setValue("shipping_address_id", address.id, { shouldValidate: true })
            if (!useDifferentBilling) {
              setValue("billing_address_id", address.id, { shouldValidate: true })
            }
          }}
        />
        {errors.shipping_address_id && (
          <p className="text-sm text-red-600">{errors.shipping_address_id.message}</p>
        )}
      </section>

      {allowDifferentBilling && (
        <section className="space-y-3 rounded-lg border border-dashed border-color p-4">
          <label className="flex items-center gap-2 text-sm font-medium">
            <input type="checkbox" {...register("use_different_billing")} />
            Farklı bir fatura adresi kullan
          </label>

          {useDifferentBilling && (
            <>
              <AddressSelector
                userId={userId}
                selectedAddressId={watchedBillingAddress ?? undefined}
                onSelect={(address) =>
                  setValue("billing_address_id", address.id, { shouldValidate: true })
                }
              />
              {errors.billing_address_id && (
                <p className="text-sm text-red-600">{errors.billing_address_id.message}</p>
              )}
            </>
          )}
        </section>
      )}

      <section className="space-y-4">
        <h2 className="text-lg font-semibold">Teslimat Seçenekleri</h2>
        <div className="grid gap-4 md:grid-cols-2">
          <DeliveryMethodCard
            method={{ id: "standard", label: "Standart" }}
            isSelected={watchedDeliveryMethod === "standard"}
            onSelect={(id) => setValue("delivery_method", id, { shouldValidate: true })}
          />
        </div>
        {errors.delivery_method && (
          <p className="text-sm text-red-600">{errors.delivery_method.message}</p>
        )}
      </section>

      <section className="space-y-2">
        <label className="text-sm font-medium" htmlFor="checkout-notes">
          Teslimat Notu (isteğe bağlı)
        </label>
        <textarea
          id="checkout-notes"
          rows={3}
          className="w-full rounded-lg border border-color bg-transparent px-3 py-2 text-sm outline-none focus:border-[var(--accent)]"
          placeholder="Örn: Akşam 18:00'den sonra teslim edilsin."
          {...register("notes")}
        />
        {errors.notes && <p className="text-sm text-red-600">{errors.notes.message}</p>}
      </section>

      <div className="flex justify-end">
        <button
          type="submit"
          className="rounded-lg bg-[var(--accent)] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[var(--accent-dark)] disabled:opacity-60"
          disabled={isSubmitting}
        >
          {isSubmitting ? "Kaydediliyor..." : "Devam Et"}
        </button>
      </div>
    </form>
  )
}
