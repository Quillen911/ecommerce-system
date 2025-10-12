"use client"

import { useEffect, useState, useMemo } from "react"
import { motion } from "framer-motion"

import { useUserAddressIndex, useUserAddressStore } from "@/hooks/useUserAddressQuery"
import type { UserAddress, AddressStoreRequest } from "@/types/userAddress"
import AddressFormModal from "./AddressFormModal"

interface AddressSelectorProps {
  userId: number
  onSelect: (address: UserAddress) => void
  selectedAddressId?: number
}

export default function AddressSelector({ userId, onSelect, selectedAddressId }: AddressSelectorProps) {
  const { data: addresses, isLoading } = useUserAddressIndex(userId)
  const [isModalOpen, setIsModalOpen] = useState(false)
  const storeMutation = useUserAddressStore(userId)

  useEffect(() => {
    if (!addresses || addresses.length === 0) return
    if (selectedAddressId) return
    const preferred =
      addresses.find((address) => address.is_default) ?? addresses[0]
    onSelect(preferred)
  }, [addresses, selectedAddressId, onSelect])

  const handleAddNew = (payload: AddressStoreRequest) => {
    storeMutation.mutate(payload, {
      onSuccess: (response: { UserAddress: UserAddress }) => {
        setIsModalOpen(false)
        if (response?.UserAddress) {
          onSelect(response.UserAddress)
        }
      },
    })
  }

  if (isLoading) {
    return (
      <div className="flex items-center justify-center py-12">
        <div className="flex items-center space-x-3 text-gray-600">
          <div className="w-6 h-6 border-2 border-gray-300 border-top-black rounded-full animate-spin" />
          <span className="text-lg">Adresler yükleniyor...</span>
        </div>
      </div>
    )
  }

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="space-y-6"
    >
      <div className="flex justify-between items-center">
        <div>
          <h3 className="text-2xl font-bold text-gray-900">Adres Seç</h3>
          <p className="text-gray-600 mt-1">Siparişinin teslim edileceği adresi seç</p>
        </div>
        <motion.button
          whileHover={{ scale: 1.05 }}
          whileTap={{ scale: 0.95 }}
          onClick={() => setIsModalOpen(true)}
          className="bg-black text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-800 transition-colors duration-200 flex items-center space-x-2"
        >
          <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
          </svg>
          <span>Yeni Adres Ekle</span>
        </motion.button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {addresses?.map((address, index) => (
          <motion.div
            key={address.id}
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.4, delay: index * 0.1 }}
            className={`relative border-2 rounded-2xl p-6 cursor-pointer transition-all duration-200 group
              ${
                selectedAddressId === address.id
                  ? "border-black bg-gray-50 shadow-lg"
                  : "border-gray-200 hover:border-gray-300 hover:shadow-md"
              }`}
            onClick={() => onSelect(address)}
            whileHover={{ scale: 1.02 }}
            whileTap={{ scale: 0.98 }}
          >
            {selectedAddressId === address.id && (
              <motion.div
                initial={{ scale: 0 }}
                animate={{ scale: 1 }}
                className="absolute -top-2 -right-2 w-8 h-8 bg-black rounded-full flex items-center justify-center"
              >
                <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                </svg>
              </motion.div>
            )}

            {address.is_default && (
              <div className="absolute -top-2 -left-2">
                <span className="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg">
                  Varsayılan
                </span>
              </div>
            )}

            <div className="space-y-3">
              <h4 className="text-xl font-bold text-gray-900">{address.title}</h4>

              <div className="space-y-2">
                <p className="font-medium text-gray-800">
                  {address.first_name} {address.last_name}
                </p>
                <p className="text-gray-600 flex items-center space-x-2">
                  <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  <span>{address.phone}</span>
                </p>
              </div>

              <div className="text-gray-600 space-y-1">
                <p className="flex items-start space-x-2">
                  <svg className="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <span>{address.address_line_1}</span>
                </p>
                {address.address_line_2 && <p className="ml-6 text-sm">{address.address_line_2}</p>}
                <p className="ml-6 font-medium">
                  {address.district} / {address.city}
                </p>
                <p className="ml-6 text-sm">
                  {address.country} {address.postal_code}
                </p>
              </div>
            </div>

            <div
              className={`absolute rounded-2xl transition-opacity duration-200 ${
                selectedAddressId === address.id
                  ? "bg-black/5"
                  : "bg-gray-100 bg-opacity-100 group-hover:bg-opacity-30"
              }`}
            />
          </motion.div>
        ))}
      </div>

      {addresses?.length === 0 && (
        <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="text-center py-16">
          <div className="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
            <svg className="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
          <h3 className="text-xl font-semibold text-gray-900 mb-2">Henüz adres eklenmemiş</h3>
          <p className="text-gray-600 mb-6">Siparişin için bir adres eklemen gerekiyor.</p>
          <button
            onClick={() => setIsModalOpen(true)}
            className="bg-black text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-800 transition-colors duration-200"
          >
            İlk Adresinizi Ekleyin
          </button>
        </motion.div>
      )}

      <AddressFormModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        onSubmit={handleAddNew}
        isLoading={storeMutation.isPending}
        title="Yeni Adres Ekle"
        submitText="Ekle"
      />
    </motion.div>
  )
}
