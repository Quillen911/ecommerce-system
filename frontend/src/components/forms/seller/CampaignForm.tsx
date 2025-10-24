"use client"

import { useEffect, useMemo } from "react"
import { useForm } from "react-hook-form"
import { AnimatePresence, motion } from "framer-motion"
import type { ReactNode } from "react"

import type { Campaign, CampaignType } from "@/types/seller/campaign"

export interface CampaignFormValues {
  name: string
  description?: string | null
  code?: string | null
  type: CampaignType
  discount_value?: number | null
  buy_quantity?: number | null
  pay_quantity?: number | null
  min_subtotal?: number | null
  usage_limit?: number | null
  per_user_limit?: number | null
  is_active?: boolean
  starts_at?: string | null
  ends_at?: string | null
  product_ids?: number[]
  category_ids?: number[]
}

interface CampaignFormProps {
  initialValues: CampaignFormValues | Campaign
  onSubmit: (values: CampaignFormValues) => Promise<void> | void
  loading?: boolean
}

const typeOptions: { value: CampaignType; label: string; description: string }[] = [
  {
    value: "percentage",
    label: "Yüzdesel İndirim",
    description: "Sepet toplamı üzerinden % düşer.",
  },
  {
    value: "fixed",
    label: "Sabit Tutar",
    description: "Sepetten belirli tutar düşer.",
  },
  {
    value: "x_buy_y_pay",
    label: "X Al Y Öde",
    description: "Ürün bazlı kampanyalar için.",
  },
]

function Section({
  title,
  description,
  children,
}: {
  title: string
  description: string
  children: ReactNode
}) {
  return (
    <section className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <header className="mb-6">
        <h3 className="text-base font-semibold text-gray-900">{title}</h3>
        <p className="mt-1 text-sm text-gray-500">{description}</p>
      </header>
      <div className="space-y-4">{children}</div>
    </section>
  )
}

const extractErrorMessages = (error: unknown, bag: Set<string>) => {
  if (!error) return

  if (Array.isArray(error)) {
    error.forEach((item) => extractErrorMessages(item, bag))
    return
  }

  if (typeof error === "object") {
    const fieldError = error as {
      message?: unknown
      types?: Record<string, unknown>
      ref?: unknown
    }

    if (typeof fieldError.message === "string" && fieldError.message.trim()) {
      bag.add(fieldError.message)
    }

    if (fieldError.types && typeof fieldError.types === "object") {
      Object.values(fieldError.types).forEach((value) => extractErrorMessages(value, bag))
    }

    Object.entries(error).forEach(([key, value]) => {
      if (key === "ref" || key === "type") return
      extractErrorMessages(value, bag)
    })
  } else if (typeof error === "string" && error.trim()) {
    bag.add(error)
  }
}

export default function CampaignForm({ initialValues, onSubmit, loading = false }: CampaignFormProps) {
  const preparedDefaults = useMemo<CampaignFormValues>(() => {
    const base = initialValues as CampaignFormValues
    return {
      ...base,
      description: base.description ?? "",
      code: base.code ?? "",
      discount_value: base.discount_value ?? null,
      buy_quantity: base.buy_quantity ?? null,
      pay_quantity: base.pay_quantity ?? null,
      min_subtotal: base.min_subtotal ?? null,
      usage_limit: base.usage_limit ?? null,
      per_user_limit: base.per_user_limit ?? null,
      starts_at: base.starts_at ?? "",
      ends_at: base.ends_at ?? "",
      product_ids: Array.isArray(base.product_ids)
        ? base.product_ids.filter((id): id is number => Number.isInteger(id))
        : [],
      category_ids: Array.isArray(base.category_ids)
        ? base.category_ids.filter((id): id is number => Number.isInteger(id))
        : [],
      is_active: base.is_active ?? true,
    }
  }, [initialValues])

  const {
    register,
    handleSubmit,
    watch,
    reset,
    setError,
    clearErrors,
    formState: { errors },
  } = useForm<CampaignFormValues>({
    defaultValues: preparedDefaults,
  })

  useEffect(() => {
    reset(preparedDefaults)
  }, [preparedDefaults, reset])

  const type = watch("type")
  const productIds = watch("product_ids")
  const categoryIds = watch("category_ids")

  useEffect(() => {
    const hasProducts =
      Array.isArray(productIds) && productIds.filter((id) => Number.isInteger(id)).length > 0
    const hasCategories =
      Array.isArray(categoryIds) && categoryIds.filter((id) => Number.isInteger(id)).length > 0

    if (hasProducts || hasCategories) {
      clearErrors(["product_ids", "category_ids"])
    }
  }, [productIds, categoryIds, clearErrors])

  const ensureProductOrCategory = (values: CampaignFormValues) => {
    const hasProducts =
      Array.isArray(values.product_ids) &&
      values.product_ids.filter((id) => Number.isInteger(id)).length > 0
    const hasCategories =
      Array.isArray(values.category_ids) &&
      values.category_ids.filter((id) => Number.isInteger(id)).length > 0

    if (!hasProducts && !hasCategories) {
      const message = "En az bir ürün ID veya kategori ID girmelisiniz."
      setError("product_ids", { type: "manual", message })
      setError("category_ids", { type: "manual", message })
      return false
    }
    return true
  }

  const submitHandler = handleSubmit(async (values) => {
    if (!ensureProductOrCategory(values)) return

    const sanitized: CampaignFormValues = {
      ...values,
      product_ids: Array.isArray(values.product_ids)
        ? values.product_ids.filter((id): id is number => Number.isInteger(id))
        : [],
      category_ids: Array.isArray(values.category_ids)
        ? values.category_ids.filter((id): id is number => Number.isInteger(id))
        : [],
      discount_value: values.discount_value ?? null,
      buy_quantity: values.buy_quantity ?? null,
      pay_quantity: values.pay_quantity ?? null,
      min_subtotal: values.min_subtotal ?? null,
      per_user_limit: values.per_user_limit ?? null,
      usage_limit: values.usage_limit ?? null,
      starts_at: values.starts_at || null,
      ends_at: values.ends_at || null,
    }

    await onSubmit(sanitized)
  })

  const showDiscountField = type === "percentage" || type === "fixed"
  const showQuantityFields = type === "x_buy_y_pay"

  const aggregatedErrors = useMemo(() => {
    const messages = new Set<string>()
    extractErrorMessages(errors, messages)
    return Array.from(messages)
  }, [errors])

  return (
    <form className="flex flex-col gap-7" onSubmit={submitHandler}>
      <AnimatePresence initial={false}>
        {aggregatedErrors.length > 0 && (
          <motion.div
            key="campaign-errors"
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

      <Section
        title="Genel Bilgiler"
        description="Kampanya adı, kodu ve kısa açıklama gibi temel bilgileri giriniz."
      >
        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">
              Kampanya Adı <span className="text-red-500">*</span>
            </label>
            <input
              className={`block w-full rounded-md border ${
                errors.name ? "border-red-400" : "border-gray-300"
              } p-2.5 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm`}
              placeholder="Örn. Yaz İndirimi"
              {...register("name", { required: "Bu alan zorunludur." })}
            />
            {errors.name && <p className="mt-1 text-xs text-red-600">{errors.name.message}</p>}
          </div>

          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">Kampanya Kodu</label>
            <input
              className="block w-full rounded-md border border-gray-300 p-2.5 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
              placeholder="Örn: YAZ25"
              {...register("code")}
            />
            <p className="mt-1 text-xs text-gray-500">Kodsuz kampanya oluşturmak için boş bırakın.</p>
          </div>

          <div className="md:col-span-2">
            <label className="mb-1 block text-sm font-medium text-gray-700">Açıklama</label>
            <textarea
              rows={3}
              className="block w-full rounded-md border border-gray-300 p-2.5 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
              placeholder="Kampanya detaylarını yazın..."
              {...register("description")}
            />
          </div>
        </div>
      </Section>

      <Section
        title="Kampanya Kuralları"
        description="Kampanya tipini seç, gerekiyorsa indirim tutarını ve özel koşulları tanımla."
      >
        <div className="grid gap-4">
          <div className="grid gap-3 md:grid-cols-3">
            {typeOptions.map((option) => {
              const selected = type === option.value
              return (
                <label
                  key={option.value}
                  className={`flex cursor-pointer items-start gap-3 rounded-2xl border p-4 transition ${
                    selected
                      ? "border-black bg-black/5 text-black"
                      : "border-gray-200 bg-white hover:border-black/40"
                  }`}
                >
                  <input
                    type="radio"
                    value={option.value}
                    className="mt-1 h-4 w-4 accent-black"
                    {...register("type", { required: "Kampanya tipi seçilmelidir." })}
                  />
                  <div>
                    <p className="text-sm font-semibold">{option.label}</p>
                    <p className="text-xs text-gray-500">{option.description}</p>
                  </div>
                </label>
              )
            })}
          </div>

          {errors.type && <span className="text-xs text-red-600">{errors.type.message}</span>}

          <div className="grid gap-4 md:grid-cols-3">
            {showDiscountField && (
              <label className="form-control md:col-span-1">
                <span className="text-sm font-medium text-gray-700">İndirim Değeri</span>
                <input
                  type="number"
                  step="0.1"
                  max={type === "percentage" ? 100 : undefined}
                  min={0}
                  className="mt-1 rounded-lg border border-gray-300 bg-white p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  placeholder={type === "percentage" ? "% oran" : "TL tutar"}
                  {...register("discount_value", {
                    setValueAs: (value) => {
                      if (value === "" || value === null || value === undefined) return undefined
                      const parsed = Number(value)
                      return Number.isNaN(parsed) ? undefined : parsed
                    },
                    min: { value: 0, message: "0’dan küçük olamaz." },
                    max:
                      type === "percentage"
                        ? { value: 100, message: "%100’den büyük olamaz." }
                        : undefined,
                  })}
                />
                {errors.discount_value && (
                  <span className="mt-1 text-xs text-red-600">
                    {errors.discount_value.message}
                  </span>
                )}
                <span className="mt-1 text-sm text-gray-500">
                  Yüzdesel kampanyalarda % değer; sabit kampanyalarda TL olarak düşün.
                </span>
              </label>
            )}

            {showQuantityFields && (
              <div className="flex flex-col gap-4 md:flex-row md:col-span-3">
                <label className="form-control flex-1">
                  <span className="text-sm font-medium text-gray-700">Alınacak Ürün Adedi</span>
                  <input
                    type="number"
                    className="mt-1 rounded-lg border border-gray-300 bg-white p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Örn. 3"
                    {...register("buy_quantity", {
                      setValueAs: (value) => {
                        if (value === "" || value === null || value === undefined) return undefined
                        const parsed = Number(value)
                        return Number.isNaN(parsed) ? undefined : parsed
                      },
                      required: "Alınacak adet zorunludur.",
                      min: { value: 1, message: "En az 1 olmalıdır." },
                    })}
                  />
                  {errors.buy_quantity && (
                    <span className="mt-1 text-xs text-red-600">{errors.buy_quantity.message}</span>
                  )}
                </label>

                <label className="form-control flex-1">
                  <span className="text-sm font-medium text-gray-700">Ödenecek Ürün Adedi</span>
                  <input
                    type="number"
                    className="mt-1 rounded-lg border border-gray-300 bg-white p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Örn. 2"
                    {...register("pay_quantity", {
                      setValueAs: (value) => {
                        if (value === "" || value === null || value === undefined) return undefined
                        const parsed = Number(value)
                        return Number.isNaN(parsed) ? undefined : parsed
                      },
                      required: "Ödenecek adet zorunludur.",
                      min: { value: 0, message: "Negatif olamaz." },
                    })}
                  />
                  {errors.pay_quantity && (
                    <span className="mt-1 text-xs text-red-600">{errors.pay_quantity.message}</span>
                  )}
                </label>
              </div>
            )}
          </div>
        </div>
      </Section>

      <Section
        title="Geçerlilik ve Limitler"
        description="Kampanyanın zaman aralığını ve varsa kullanım limiti ile minimum sepet tutarını belirt."
      >
        <div className="grid gap-4 md:grid-cols-2">
          <label className="form-control">
            <span className="text-sm font-medium text-gray-700">Minimum Sepet Tutarı (TL)</span>
            <input
              type="number"
              step="0.01"
              className="mt-1 rounded-lg border border-gray-300 bg-white p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              placeholder="Opsiyonel"
              {...register("min_subtotal", {
                setValueAs: (value) => {
                  if (value === "" || value === null || value === undefined) return undefined
                  const parsed = Number(value)
                  return Number.isNaN(parsed) ? undefined : parsed
                },
              })}
            />
          </label>

          <label className="form-control">
            <span className="text-sm font-medium text-gray-700">Kullanım Limiti</span>
            <input
              type="number"
              className="mt-1 rounded-lg border border-gray-300 bg-white p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              placeholder="Sınırsız bırakmak için boş bırak"
              {...register("usage_limit", {
                setValueAs: (value) => {
                  if (value === "" || value === null || value === undefined) return undefined
                  const parsed = Number(value)
                  return Number.isNaN(parsed) ? undefined : parsed
                },
              })}
            />
          </label>
          
          <label className="form-control">
            <span className="text-sm font-medium text-gray-700">Kullanıcı Limiti</span>
            <input
              type="number"
              className="mt-1 rounded-lg border border-gray-300 bg-white p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              placeholder="Sınırsız bırakmak için boş bırak"
              {...register("per_user_limit", {
                setValueAs: (value) => {
                  if (value === "" || value === null || value === undefined) return undefined
                  const parsed = Number(value)
                  return Number.isNaN(parsed) ? undefined : parsed
                },
              })}
            />
          </label>
          
          <label className="form-control">
            <span className="text-sm font-medium text-gray-700">Başlangıç Tarihi</span>
            <input
              type="date"
              className="mt-1 rounded-lg border border-gray-300 bg-white p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              {...register("starts_at")}
            />
          </label>

          <label className="form-control">
            <span className="text-sm font-medium text-gray-700">Bitiş Tarihi</span>
            <input
              type="date"
              className="mt-1 rounded-lg border border-gray-300 bg-white p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              {...register("ends_at")}
            />
          </label>

          <label className="md:col-span-2 flex items-center gap-3 rounded-2xl border border-gray-200 bg-gray-50 p-4">
            <input type="checkbox" className="h-5 w-5 accent-black" {...register("is_active")} />
            <div>
              <p className="text-sm font-semibold text-gray-900">Kampanya Aktif</p>
              <p className="text-xs text-gray-500">
                Pasif kampanyalar müşterilere gösterilmez ancak kayıtlarda tutulur.
              </p>
            </div>
          </label>
        </div>
      </Section>

      <Section
        title="Ürün / Kategori Ataması"
        description="En az bir ürün ID veya kategori ID gir. Virgülle ayrılmış değerler bekleniyor."
      >
        <div className="grid gap-6 md:grid-cols-2">
          <label className="form-control">
            <span className="mb-2 text-sm font-medium text-gray-700">Ürün ID’leri</span>
            <input
              className={`rounded-lg border ${
                errors.product_ids ? "border-red-400" : "border-gray-300"
              } bg-white p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500`}
              placeholder="Örn. 12, 45, 78"
              defaultValue={preparedDefaults.product_ids?.join(", ")}
              {...register("product_ids", {
                setValueAs: (raw) => {
                  if (Array.isArray(raw)) {
                    return raw.filter((id): id is number => Number.isInteger(id))
                  }
                  if (typeof raw !== "string") return []
                  const parts = raw
                    .split(",")
                    .map((val) => parseInt(val.trim(), 10))
                    .filter((num) => Number.isInteger(num))
                  return parts
                },
              })}
            />
            {errors.product_ids && (
              <span className="mt-1 text-xs text-red-600">{errors.product_ids.message}</span>
            )}
            <span className="mt-1 text-sm text-gray-500">
              Kampanya sadece belirli ürünlerde geçerliyse ürün ID’lerini virgülle ayırarak gir.
            </span>
          </label>

          <label className="form-control">
            <span className="mb-2 text-sm font-medium text-gray-700">Kategori ID’leri</span>
            <input
              className={`rounded-lg border ${
                errors.category_ids ? "border-red-400" : "border-gray-300"
              } bg-white p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500`}
              placeholder="Örn. 5, 9"
              defaultValue={preparedDefaults.category_ids?.join(", ")}
              {...register("category_ids", {
                setValueAs: (raw) => {
                  if (Array.isArray(raw)) {
                    return raw.filter((id): id is number => Number.isInteger(id))
                  }
                  if (typeof raw !== "string") return []
                  const parts = raw
                    .split(",")
                    .map((val) => parseInt(val.trim(), 10))
                    .filter((num) => Number.isInteger(num))
                  return parts
                },
              })}
            />
            {errors.category_ids && (
              <span className="mt-1 text-xs text-red-600">{errors.category_ids.message}</span>
            )}
            <span className="mt-1 text-sm text-gray-500">
              Kategori seçersen, o kategoriye ait tüm ürünler kampanyaya dahil edilir.
            </span>
          </label>
        </div>
      </Section>

      <footer className="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
        <button
          type="button"
          className="cursor-pointer rounded-xl border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 disabled:opacity-60"
          onClick={() => reset(preparedDefaults)}
          disabled={loading}
        >
          Temizle
        </button>
        <button
          type="submit"
          className="flex cursor-pointer items-center justify-center rounded-xl bg-black px-6 py-3 text-sm font-semibold text-white transition hover:bg-gray-900 disabled:opacity-60"
          disabled={loading}
        >
          {loading ? "Kaydediliyor..." : "Kampanyayı Kaydet"}
        </button>
      </footer>
    </form>
  )
}
