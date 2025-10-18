"use client"

import { AnimatePresence, motion } from "framer-motion"
import AddressForm from "./AddressForm"

interface AddressFormModalProps {
  isOpen: boolean
  onClose: () => void
  onSubmit: (data: any, options?: any) => void
  initialData?: any
  isLoading?: boolean
  title?: string
  submitText?: string
}

export default function AddressFormModal({
  isOpen,
  onClose,
  onSubmit,
  initialData,
  isLoading = false,
  title = "Adres Düzenle",
  submitText = "Güncelle",
}: AddressFormModalProps) {
  return (
    <AnimatePresence>
      {isOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="absolute inset-0 bg-white backdrop-blur-sm"
            onClick={onClose}
          />
          <motion.div
            initial={{ opacity: 0, scale: 0.95, y: 24 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            exit={{ opacity: 0, scale: 0.95, y: 24 }}
            transition={{ duration: 0.25, ease: "easeOut" }}
            className="relative z-10 w-full max-w-2xl max-h-[92vh] overflow-hidden rounded-2xl bg-white shadow-2xl"
            onClick={(event) => event.stopPropagation()}
          >
            <div className="flex items-center justify-between border-b border-gray-200 px-6 py-4">
              <h3 className="text-2xl font-semibold text-gray-900">{title}</h3>
              <button
                type="button"
                onClick={onClose}
                className="rounded-full p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700"
                aria-label="Kapat"
              >
                <svg className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path
                    fillRule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clipRule="evenodd"
                  />
                </svg>
              </button>
            </div>

            <div className="max-h-[calc(92vh-120px)] overflow-y-auto px-6 py-5">
              <AddressForm
                initialData={initialData}
                onSubmit={onSubmit}
                onCancel={onClose}
                isLoading={isLoading}
                submitText={submitText}
              />
            </div>
          </motion.div>
        </div>
      )}
    </AnimatePresence>
  )
}
