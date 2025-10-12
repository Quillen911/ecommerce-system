import { z } from "zod"

export const paymentSchema = z.object({
  session_id: z.string().uuid("Geçerli bir session bulunamadı."),
  payment_method: z.string().min(1, "Ödeme yöntemi seçmelisiniz."),
  payment_method_id: z
    .number()
    .int("Ödeme yöntemi geçerli değil.")
    .min(1, "Ödeme yöntemi geçerli değil.")
    .optional(),
  provider: z.string().min(1, "Ödeme sağlayıcısı seçmelisiniz."),
  card_alias: z.string().optional(),
  card_number: z.string().optional(),
  card_holder_name: z.string().optional(),
  expire_month: z.string().optional(),
  expire_year: z.string().optional(),
  cvv: z.string().optional(),
  save_card: z.boolean().optional(),
  installment: z
    .number()
    .int("Faizli ödeme geçerli değil.")
    .min(1, "Faizli ödeme geçerli değil.")
    .optional(),
  requires_3ds: z.boolean().optional(),
})

export type PaymentFormValues = z.infer<typeof paymentSchema>
