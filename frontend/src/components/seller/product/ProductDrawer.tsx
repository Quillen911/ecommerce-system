'use client'

import { useEffect } from 'react'
import { ProductForm } from '@/components/forms/seller/ProductForm'
import type { Product } from '@/types/seller/product'

type ProductDrawerProps = {
  isOpen: boolean
  product?: Product
  sellerId?: number
  onClose: () => void
}

export const ProductDrawer = ({
  isOpen,
  product,
  sellerId,
  onClose,
}: ProductDrawerProps) => {
  const formId = product ? `product-form-${product.id}` : 'product-form-new'
  const submitLabel = product ? 'Güncelle' : 'Kaydet'

  useEffect(() => {
    const handleEsc = (event: KeyboardEvent) => {
      if (event.key === 'Escape') onClose()
    }
    window.addEventListener('keydown', handleEsc)
    return () => window.removeEventListener('keydown', handleEsc)
  }, [onClose])

  return (
    <div
      className={`fixed inset-0 z-50 flex transition-opacity duration-300 ${
        isOpen ? 'opacity-100' : 'pointer-events-none opacity-0'
      }`}
    >
      <div
        className={`fixed inset-0 bg-black transition-opacity duration-300 ${
          isOpen ? 'opacity-50' : 'opacity-0'
        }`}
        onClick={onClose}
      />

      <aside
        className={`fixed right-0 top-0 h-full w-full max-w-3xl transform bg-white shadow-2xl transition-transform duration-300 ${
          isOpen ? 'translate-x-0' : 'translate-x-full'
        }`}
      >
        <header className="flex items-center justify-between border-b border-gray-200 px-6 py-4">
          <div>
            <h2 className="text-lg font-semibold text-gray-900">
              {product ? 'Ürünü Düzenle' : 'Yeni Ürün Ekle'}
            </h2>
            <p className="text-xs text-gray-500">
              {product
                ? 'Ürün bilgilerini güncelle.'
                : 'Ürününü oluştur, varyantlarını ve stoklarını ekle.'}
            </p>
          </div>

          <div className="flex items-center gap-3">
            <button
              form={formId}
              type="submit"
              className="rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
            >
              {submitLabel}
            </button>
            <button
              onClick={onClose}
              className="rounded-full border border-gray-200 p-2 text-gray-500 transition hover:bg-gray-100"
              aria-label="Kapat"
            >
              ✕
            </button>
          </div>
        </header>

        <div className="h-[calc(100%-72px)] overflow-y-auto px-6 py-4">
          <ProductForm
            formId={formId}
            hideFooter
            sellerId={sellerId}
            product={product}
            onSuccess={onClose}
          />
        </div>
      </aside>
    </div>
  )
}
