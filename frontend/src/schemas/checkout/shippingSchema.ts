import { z } from "zod"

export const shippingSchema = z.object({
  session_id: z.string().uuid("Geçerli bir session bulunamadı."),
  shipping_address_id: z
    .number()
    .int("Adres seçimi geçerli değil.")
    .min(1, "Lütfen bir teslimat adresi seçin."),
  delivery_method: z
    .string()
    .min(1, "Bir teslimat yöntemi seçmelisiniz."),
  notes: z
    .string()
    .max(280, "Not en fazla 280 karakter olabilir.")
    .optional(),
  use_different_billing: z.boolean().optional(),
  billing_address_id: z
    .number()
    .int("Fatura adresi geçerli değil.")
    .min(1, "Fatura adresi geçerli değil.")
    .optional(),
})

export type ShippingFormValues = z.infer<typeof shippingSchema>
