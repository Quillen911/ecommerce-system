'use client'
import { ProductForm } from '@/components/forms/seller/ProductForm'

interface ProductDrawerProps {
  isOpen: boolean
  onClose: () => void
}

export const ProductDrawer = ({ isOpen, onClose }: ProductDrawerProps) => {
  return (
    <div
      className={`fixed inset-0 z-50 flex transition-opacity duration-300 ${
        isOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'
      }`}
    >
      {/* Overlay */}
      <div
        className={`fixed inset-0 bg-black transition-opacity duration-300 ${
          isOpen ? 'opacity-50' : 'opacity-0'
        }`}
        onClick={onClose}
      />

      {/* Drawer */}
      <div
        className={`fixed right-0 top-0 h-full w-[800px] bg-white shadow-lg flex flex-col transform transition-transform duration-300 ${
          isOpen ? 'translate-x-0' : 'translate-x-full'
        }`}
      >
        {/* Header */}
        <div className="p-4 border-b flex justify-between items-center">
          <h2>Yeni Ürün Ekle</h2>
          <button
            onClick={onClose}
            className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 cursor-pointer"
          >
            İptal
          </button>
        </div>

        {/* Form */}
        <div className="flex-1 overflow-y-auto p-4">
          <ProductForm onSuccess={onClose} />
        </div>
      </div>
    </div>
  )
}
