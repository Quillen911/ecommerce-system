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
  if (Array.isArray(value)) return value.map((item) => normalizeMessage(String(item)))
  if (typeof value === "string") return [normalizeMessage(value)]
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

  const aggregatedErrors = useMemo(
    () =>
      Object.entries(errors)
        .filter(([key]) => key !== "general")
        .flatMap(([, messages]) => messages)
        .filter(Boolean),
    [errors]
  )

  const handleSubmit = (event: React.FormEvent) => {
    event.preventDefault()
    setErrors({})
    onSubmit(
      { ...formData, notes: formData.notes.trim() || undefined },
      {
        onError: (error: unknown) => {
          const response = (error as any)?.response?.data
          if (!response) return
          if (response.errors && typeof response.errors === "object") {
            const normalized: ServerErrors = {}
            for (const [field, value] of Object.entries(response.errors))
              normalized[field] = normalizeErrorEntry(value)
            setErrors(normalized)
          } else if (response.message) setErrors({ general: [normalizeMessage(response.message)] })
        },
      }
    )
  }

  const ErrorMessage = ({ field }: { field: keyof typeof errors }) => {
    const fieldErrors = errors[field]
    if (!fieldErrors?.length) return null
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
    "w-full px-4 py-2.5 sm:py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all duration-200 text-sm sm:text-base"
  const textareaStyles =
    "w-full px-4 py-2.5 sm:py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition-all duration-200 resize-none text-sm sm:text-base"

  return (
    <motion.form onSubmit={handleSubmit} className="space-y-6" initial={{ opacity: 0 }} animate={{ opacity: 1 }}>
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
              {errors.general?.map((msg, i) => <li key={`general-${i}`}>{msg}</li>)}
              {aggregatedErrors.map((msg, i) => <li key={`field-${i}`}>{msg}</li>)}
            </ul>
          </motion.div>
        )}
      </AnimatePresence>

      {[
        { key: "title", label: "Adres Başlığı", placeholder: "Örn: Ev, İş, Ofis..." },
        { key: "phone", label: "Telefon", placeholder: "0555 555 55 55" },
      ].map(({ key, label, placeholder }) => (
        <motion.div key={key} initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
          <label className="mb-2 block text-sm font-medium text-gray-700">{label}</label>
          <input
            type="text"
            placeholder={placeholder}
            value={(formData as any)[key]}
            onChange={(e) => setField(key as any, e.target.value)}
            className={`${inputStyles} ${errors[key as keyof typeof errors] ? "border-red-500" : ""}`}
          />
          <ErrorMessage field={key as keyof typeof errors} />
        </motion.div>
      ))}

      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {["first_name", "last_name"].map((field, i) => (
          <motion.div key={field} initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <label className="mb-2 block text-sm font-medium text-gray-700">
              {field === "first_name" ? "Ad" : "Soyad"}
            </label>
            <input
              type="text"
              placeholder={field === "first_name" ? "Adınız" : "Soyadınız"}
              value={(formData as any)[field]}
              onChange={(e) => setField(field as any, e.target.value)}
              className={`${inputStyles} ${errors[field as keyof typeof errors] ? "border-red-500" : ""}`}
            />
            <ErrorMessage field={field as keyof typeof errors} />
          </motion.div>
        ))}
      </div>

      <div className="space-y-4">
        {["address_line_1", "address_line_2"].map((field, i) => (
          <motion.div key={field} initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <label className="mb-2 block text-sm font-medium text-gray-700">
              {field === "address_line_1" ? "Adres Satırı 1" : "Adres Satırı 2 (Opsiyonel)"}
            </label>
            <textarea
              placeholder={
                field === "address_line_1"
                  ? "Mahalle, cadde, sokak, kapı numarası..."
                  : "Apartman, daire numarası, kat..."
              }
              value={(formData as any)[field]}
              onChange={(e) => setField(field as any, e.target.value)}
              className={`${textareaStyles} ${errors[field as keyof typeof errors] ? "border-red-500" : ""}`}
              rows={field === "address_line_1" ? 3 : 2}
            />
            <ErrorMessage field={field as keyof typeof errors} />
          </motion.div>
        ))}
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {["district", "city", "country", "postal_code"].map((field, i) => (
          <motion.div key={field} initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <label className="mb-2 block text-sm font-medium text-gray-700">
              {field === "district"
                ? "İlçe"
                : field === "city"
                ? "Şehir"
                : field === "country"
                ? "Ülke"
                : "Posta Kodu"}
            </label>
            <input
              type="text"
              placeholder={field === "country" ? "Türkiye" : ""}
              value={(formData as any)[field]}
              onChange={(e) => setField(field as any, e.target.value)}
              className={`${inputStyles} ${errors[field as keyof typeof errors] ? "border-red-500" : ""}`}
            />
            <ErrorMessage field={field as keyof typeof errors} />
          </motion.div>
        ))}
      </div>

      <motion.div className="mt-6 flex items-center space-x-3">
        <input
          type="checkbox"
          id="is_default"
          checked={formData.is_default}
          onChange={(e) => setField("is_default", e.target.checked)}
          className="h-5 w-5 rounded border-gray-300 text-black focus:ring-2 focus:ring-black"
        />
        <label htmlFor="is_default" className="cursor-pointer text-sm font-medium text-gray-700">
          Bu adresi varsayılan adres yap
        </label>
      </motion.div>

      <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="mt-6">
        <label className="mb-2 block text-sm font-medium text-gray-700">Notlar (Opsiyonel)</label>
        <textarea
          placeholder="Adres için özel notlar..."
          value={formData.notes}
          onChange={(e) => setField("notes", e.target.value)}
          className={`${textareaStyles} ${errors.notes ? "border-red-500" : ""}`}
          rows={3}
        />
        <ErrorMessage field="notes" />
      </motion.div>

      <motion.div className="space-y-4 pt-6">
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
