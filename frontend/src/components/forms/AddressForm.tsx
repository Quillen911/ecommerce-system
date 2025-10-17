"use client"

import { useMemo, useState } from "react"
import { AnimatePresence, motion } from "framer-motion"

interface AddressFormProps {
  initialData?: {
    title: string
    first_name: string
    last_name: string
    phone: string
    address_line_1: string
    address_line_2: string
    district: string
    city: string
    country: string
    postal_code: string
    is_default: boolean
    notes: string
  }
  onSubmit: (data: any, options?: any) => void
  onCancel: () => void
  isLoading?: boolean
  submitText?: string
}

type ServerErrors = Record<string, string[]>

const normalizeMessage = (message: string) =>
  message.replace(/\s*\(and\s+\d+\s+more\s+errors\)$/i, "")

const normalizeErrorEntry = (value: unknown): string[] => {
  if (Array.isArray(value)) {
    return value.map((item) => normalizeMessage(String(item)))
  }
  if (typeof value === "string") {
    return [normalizeMessage(value)]
  }
  return []
}

export default function AddressForm({
  initialData,
  onSubmit,
  onCancel,
  isLoading = false,
  submitText = "Kaydet",
}: AddressFormProps) {
  const [errors, setErrors] = useState<ServerErrors>({})

  const [formData, setFormData] = useState({
    title: initialData?.title ?? "",
    first_name: initialData?.first_name ?? "",
    last_name: initialData?.last_name ?? "",
    phone: initialData?.phone ?? "",
    address_line_1: initialData?.address_line_1 ?? "",
    address_line_2: initialData?.address_line_2 ?? "",
    district: initialData?.district ?? "",
    city: initialData?.city ?? "",
    country: initialData?.country ?? "",
    postal_code: initialData?.postal_code ?? "",
    is_default: initialData?.is_default ?? false,
    notes: initialData?.notes ?? "",
  })

  const setField = <K extends keyof typeof formData>(key: K, value: (typeof formData)[K]) => {
    setFormData((prev) => ({ ...prev, [key]: value }))
    setErrors((prev) => {
      if (!prev[key]) return prev
      const next = { ...prev }
      delete next[key]
      return next
    })
  }

  const aggregatedErrors = useMemo(() => {
    if (!errors) return []
    return Object.entries(errors)
      .filter(([key]) => key !== "general")
      .flatMap(([, messages]) => messages)
      .filter(Boolean)
  }, [errors])

  const handleSubmit = (event: React.FormEvent) => {
    event.preventDefault()
    setErrors({})

    onSubmit(
      {
        ...formData,
        notes: formData.notes.trim() ? formData.notes.trim() : undefined,
      },
      {
        onError: (error: unknown) => {
          if (!error || typeof error !== "object") return

          const response = (error as Record<string, unknown>).response
          if (!response || typeof response !== "object") return

          const data = (response as Record<string, unknown>).data
          if (!data || typeof data !== "object") return

          if ("errors" in data && data.errors && typeof data.errors === "object") {
            const normalized: ServerErrors = {}
            for (const [field, value] of Object.entries(data.errors as Record<string, unknown>)) {
              const messages = normalizeErrorEntry(value)
              if (messages.length) {
                normalized[field] = messages
              }
            }
            setErrors(normalized)
            return
          }

          if ("message" in data && typeof data.message === "string") {
            setErrors({ general: [normalizeMessage(data.message)] })
          }
        },
      },
    )
  }

  const ErrorMessage = ({ field }: { field: keyof typeof errors }) => {
    const fieldErrors = errors[field]
    if (!fieldErrors || fieldErrors.length === 0) return null

    return (
      <AnimatePresence initial={false}>
        <motion.div
          initial={{ opacity: 0, y: -8 }}
          animate={{ opacity: 1, y: 0 }}
          exit={{ opacity: 0, y: -8 }}
          className="mt-1 flex items-center space-x-1 text-sm text-red-600"
        >
          <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
            <path
              fillRule="evenodd"
              d="M18 10a8 8 0 11-16 0 8 8 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
            />
          </svg>
          <span>{fieldErrors[0]}</span>
        </motion.div>
      </AnimatePresence>
    )
  }

  const inputStyles =
    "w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all duration-200"
  const textareaStyles =
    "w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all duration-200 resize-none"

  return (
    <motion.form
      onSubmit={handleSubmit}
      className="space-y-6"
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      transition={{ duration: 0.3 }}
    >
      <AnimatePresence initial={false}>
        {(errors.general?.length || aggregatedErrors.length) && (
          <motion.div
            key="address-errors"
            initial={{ opacity: 0, y: -10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -10 }}
            className="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600"
          >
            <ul className="list-disc space-y-1 pl-4">
              {errors.general?.map((message, index) => (
                <li key={`general-${index}`}>{message}</li>
              ))}
              {aggregatedErrors.map((message, index) => (
                <li key={`field-${index}`}>{message}</li>
              ))}
            </ul>
          </motion.div>
        )}
      </AnimatePresence>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.3, delay: 0.1 }}
      >
        <label className="mb-2 block text-sm font-medium text-gray-700">Adres Başlığı</label>
        <input
          type="text"
          placeholder="Örn: Ev, İş, Ofis..."
          value={formData.title}
          onChange={(event) => setField("title", event.target.value)}
          className={`${inputStyles} ${errors.title ? "border-red-500" : ""}`}
        />
        <ErrorMessage field="title" />
      </motion.div>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.3, delay: 0.2 }}
        className="grid grid-cols-1 gap-4 md:grid-cols-2"
      >
        <div>
          <label className="mb-2 block text-sm font-medium text-gray-700">Ad</label>
          <input
            type="text"
            placeholder="Adınız"
            value={formData.first_name}
            onChange={(event) => setField("first_name", event.target.value)}
            className={`${inputStyles} ${errors.first_name ? "border-red-500" : ""}`}
          />
          <ErrorMessage field="first_name" />
        </div>
        <div>
          <label className="mb-2 block text-sm font-medium text-gray-700">Soyad</label>
          <input
            type="text"
            placeholder="Soyadınız"
            value={formData.last_name}
            onChange={(event) => setField("last_name", event.target.value)}
            className={`${inputStyles} ${errors.last_name ? "border-red-500" : ""}`}
          />
          <ErrorMessage field="last_name" />
        </div>
      </motion.div>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.3, delay: 0.3 }}
      >
        <label className="mb-2 block text-sm font-medium text-gray-700">Telefon</label>
        <input
          type="tel"
          placeholder="0555 555 55 55"
          value={formData.phone}
          onChange={(event) => setField("phone", event.target.value)}
          className={`${inputStyles} ${errors.phone ? "border-red-500" : ""}`}
        />
        <ErrorMessage field="phone" />
      </motion.div>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.3, delay: 0.4 }}
        className="space-y-4"
      >
        <div>
          <label className="mb-2 block text-sm font-medium text-gray-700">Adres Satırı 1</label>
          <textarea
            placeholder="Mahalle, cadde, sokak, kapı numarası..."
            value={formData.address_line_1}
            onChange={(event) => setField("address_line_1", event.target.value)}
            className={`${textareaStyles} ${errors.address_line_1 ? "border-red-500" : ""}`}
            rows={3}
          />
          <ErrorMessage field="address_line_1" />
        </div>
        <div>
          <label className="mb-2 block text-sm font-medium text-gray-700">
            Adres Satırı 2 (Opsiyonel)
          </label>
          <textarea
            placeholder="Apartman, daire numarası, kat..."
            value={formData.address_line_2}
            onChange={(event) => setField("address_line_2", event.target.value)}
            className={`${textareaStyles} ${errors.address_line_2 ? "border-red-500" : ""}`}
            rows={2}
          />
          <ErrorMessage field="address_line_2" />
        </div>
      </motion.div>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.3, delay: 0.5 }}
        className="grid grid-cols-1 gap-4 md:grid-cols-2"
      >
        <div>
          <label className="mb-2 block text-sm font-medium text-gray-700">İlçe</label>
          <input
            type="text"
            placeholder="İlçe"
            value={formData.district}
            onChange={(event) => setField("district", event.target.value)}
            className={`${inputStyles} ${errors.district ? "border-red-500" : ""}`}
          />
          <ErrorMessage field="district" />
        </div>
        <div>
          <label className="mb-2 block text-sm font-medium text-gray-700">Şehir</label>
          <input
            type="text"
            placeholder="Şehir"
            value={formData.city}
            onChange={(event) => setField("city", event.target.value)}
            className={`${inputStyles} ${errors.city ? "border-red-500" : ""}`}
          />
          <ErrorMessage field="city" />
        </div>
      </motion.div>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.3, delay: 0.6 }}
        className="grid grid-cols-1 gap-4 md:grid-cols-2"
      >
        <div>
          <label className="mb-2 block text-sm font-medium text-gray-700">Ülke</label>
          <input
            type="text"
            placeholder="Türkiye"
            value={formData.country}
            onChange={(event) => setField("country", event.target.value)}
            className={`${inputStyles} ${errors.country ? "border-red-500" : ""}`}
          />
          <ErrorMessage field="country" />
        </div>
        <div>
          <label className="mb-2 block text-sm font-medium text-gray-700">Posta Kodu</label>
          <input
            type="text"
            placeholder="34000"
            value={formData.postal_code}
            onChange={(event) => setField("postal_code", event.target.value)}
            className={`${inputStyles} ${errors.postal_code ? "border-red-500" : ""}`}
          />
          <ErrorMessage field="postal_code" />
        </div>
      </motion.div>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.3, delay: 0.7 }}
        className="mt-6 flex items-center space-x-3"
      >
        <input
          type="checkbox"
          id="is_default"
          checked={formData.is_default}
          onChange={(event) => setField("is_default", event.target.checked)}
          className="h-5 w-5 rounded border-gray-300 text-black focus:ring-2 focus:ring-black"
        />
        <label htmlFor="is_default" className="cursor-pointer text-sm font-medium text-gray-700">
          Bu adresi varsayılan adres yap
        </label>
      </motion.div>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.3, delay: 0.8 }}
        className="mt-6"
      >
        <label className="mb-2 block text-sm font-medium text-gray-700">Notlar (Opsiyonel)</label>
        <textarea
          placeholder="Adres için özel notlar..."
          value={formData.notes}
          onChange={(event) => setField("notes", event.target.value)}
          className={`${textareaStyles} ${errors.notes ? "border-red-500" : ""}`}
          rows={3}
        />
        <ErrorMessage field="notes" />
      </motion.div>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.3, delay: 0.9 }}
        className="space-y-4 pt-6"
      >
        <button
          type="submit"
          disabled={isLoading}
          className="flex w-full items-center justify-center space-x-2 rounded-xl bg-black px-6 py-3 font-medium text-white transition-all duration-200 hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
        >
          {isLoading ? (
            <>
              <div className="h-5 w-5 animate-spin rounded-full border-2 border-white border-t-transparent" />
              <span>Kaydediliyor...</span>
            </>
          ) : (
            <span>{submitText}</span>
          )}
        </button>

        <button
          type="button"
          onClick={onCancel}
          className="w-full rounded-xl bg-gray-100 px-6 py-3 font-medium text-gray-700 transition-all duration-200 hover:bg-gray-200"
        >
          İptal
        </button>
      </motion.div>
    </motion.form>
  )
}
