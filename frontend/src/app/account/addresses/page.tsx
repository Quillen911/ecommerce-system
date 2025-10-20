'use client'
import { useState } from 'react'
import { motion } from 'framer-motion'
import { useUserAddressDestroy, useUserAddressIndex, useUserAddressUpdate, useUserAddressStore } from '@/hooks/useUserAddressQuery'
import { useMe } from '@/hooks/useAuthQuery'
import AddressForm from '@/components/forms/AddressForm'

export default function AddressesPage() {
  const { data: me } = useMe()
  const { data: addresses, isLoading, error } = useUserAddressIndex(me?.id)
  const destroyAddressMutation = useUserAddressDestroy(me?.id)
  const updateAddressMutation = useUserAddressUpdate(me?.id)
  const storeAddressMutation = useUserAddressStore(me?.id)
  const [editingAddress, setEditingAddress] = useState<any>(null)
  const [isModalOpen, setIsModalOpen] = useState(false)
  const [isAddModalOpen, setIsAddModalOpen] = useState(false)

  const handleStore = (formData: any, options?: any) => {
    storeAddressMutation.mutate(formData, {
      onSuccess: () => setIsAddModalOpen(false),
      onError: options?.onError
    })
  }

  const handleUpdate = (formData: any, options?: any) => {
    updateAddressMutation.mutate({ id: editingAddress?.id as number, data: formData }, {
      onSuccess: () => {
        setIsModalOpen(false)
        setEditingAddress(null)
      },
      onError: options?.onError
    })
  }

  if (isLoading) {
    return (
      <div className="flex items-center justify-center h-screen">
        <div className="text-center py-12 sm:py-16 px-4">
          <p className="text-lg sm:text-xl font-semibold text-gray-900 mb-2 animate-pulse">Adresler yükleniyor...</p>
        </div>
      </div>
    )
  }

  if (error) {
    return (
      <div className="bg-red-50 border border-red-200 rounded-xl p-5 sm:p-6">
        <div className="flex items-center space-x-2 text-red-700">
          <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
          </svg>
          <span className="text-base sm:text-lg font-medium">Adresler yüklenirken bir hata oluştu</span>
        </div>
        <p className="mt-2 text-sm sm:text-base text-red-600 break-words">{error.message}</p>
      </div>
    )
  }

  if (!addresses || addresses.length === 0) {
    return (
      <>
        <div className="text-center py-12 sm:py-16 px-4">
          <div className="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-5 sm:mb-6 bg-gray-100 rounded-full flex items-center justify-center">
            <svg className="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
          <h3 className="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Henüz adres eklememişsiniz</h3>
          <p className="text-sm sm:text-base text-gray-600 mb-6">İlk adresinizi ekleyerek başlayın</p>
          <button
            className="bg-black text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-medium hover:bg-gray-800 transition-colors duration-200"
            onClick={() => setIsAddModalOpen(true)}
          >
            Adres Ekle
          </button>
        </div>

        {(isAddModalOpen || isModalOpen) && (
          <motion.div initial={{ opacity: 0, y: -20 }} animate={{ opacity: 1, y: 0 }} className="mt-8 max-w-2xl mx-auto px-4">
            <div className="bg-white border border-gray-200 rounded-2xl p-5 sm:p-6 shadow-sm">
              <div className="mb-6">
                <h2 className="text-xl sm:text-2xl font-bold text-gray-900 mb-2">
                  {isModalOpen ? 'Adres Düzenle' : 'Yeni Adres Ekle'}
                </h2>
                <div className="w-12 sm:w-16 h-1 bg-black rounded-full" />
              </div>
              <AddressForm
                initialData={editingAddress}
                onSubmit={isModalOpen ? handleUpdate : handleStore}
                onCancel={() => {
                  setIsModalOpen(false)
                  setIsAddModalOpen(false)
                  setEditingAddress(null)
                }}
                isLoading={isModalOpen ? updateAddressMutation.isPending : storeAddressMutation.isPending}
                submitText={isModalOpen ? 'Güncelle' : 'Kaydet'}
              />
            </div>
          </motion.div>
        )}
      </>
    )
  }

  return (
    <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.5 }}>
      <div className="mb-6 sm:mb-8">
        <h1 className="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Adreslerim</h1>
        <div className="w-12 sm:w-16 h-1 bg-black rounded-full" />
      </div>
      <button
        className="bg-black text-white px-5 sm:px-6 py-2.5 sm:py-3 font-medium hover:bg-gray-800 transition-colors duration-200 rounded-xl"
        onClick={() => setIsAddModalOpen(true)}
      >
        Adres Ekle
      </button>

      <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6 mt-8 sm:mt-10">
        {addresses?.map((address, index) => (
          <motion.div
            key={address.id}
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.4, delay: index * 0.1 }}
            className="bg-white border border-gray-200 rounded-2xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-all duration-200 relative"
          >
            {address.is_default && (
              <div className="mb-4">
                <span className="inline-block bg-green-500 text-green-50 px-3 py-1 rounded-full text-xs font-medium">Varsayılan Adres</span>
              </div>
            )}

            <div className="mb-4">
              <h2 className="text-lg sm:text-xl font-bold text-gray-900 mb-2">{address.title}</h2>
              <div className="space-y-1 text-gray-600">
                <p className="font-medium text-gray-800">{address.first_name} {address.last_name}</p>
                <p className="flex items-center space-x-2">
                  <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  <span>{address.phone}</span>
                </p>
                <div className="flex items-start space-x-2">
                  <svg className="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <div>
                    <p>{address.address_line_1}</p>
                    {address.address_line_2 && <p>{address.address_line_2}</p>}
                    <p className="font-medium">{address.district} / {address.city}</p>
                    <p>{address.country} {address.postal_code}</p>
                  </div>
                </div>
              </div>
            </div>

            <div className="flex gap-3 pt-4 border-t border-gray-100">
              <button
                className="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl font-medium hover:bg-gray-200 transition-colors duration-200 flex items-center justify-center space-x-2"
                onClick={() => {
                  setEditingAddress(address)
                  setIsModalOpen(true)
                }}
              >
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Düzenle</span>
              </button>
              <button
                className="px-4 py-2 text-red-600 hover:bg-red-50 rounded-xl transition-colors duration-200 disabled:opacity-50"
                onClick={() => destroyAddressMutation.mutate(address.id)}
                disabled={destroyAddressMutation.isPending}
              >
                {destroyAddressMutation.isPending ? (
                  <div className="w-4 h-4 border-2 border-red-300 border-t-red-600 rounded-full animate-spin" />
                ) : (
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                )}
              </button>
            </div>
          </motion.div>
        ))}
      </div>

      {(isAddModalOpen || isModalOpen) && (
        <motion.div initial={{ opacity: 0, y: -20 }} animate={{ opacity: 1, y: 0 }} className="mt-8 max-w-2xl mx-auto px-4">
          <div className="bg-white border border-gray-200 rounded-2xl p-5 sm:p-6 shadow-sm">
            <div className="mb-6">
              <h2 className="text-xl sm:text-2xl font-bold text-gray-900 mb-2">
                {isModalOpen ? 'Adres Düzenle' : 'Yeni Adres Ekle'}
              </h2>
              <div className="w-12 sm:w-16 h-1 bg-black rounded-full" />
            </div>
            <AddressForm
              initialData={editingAddress}
              onSubmit={isModalOpen ? handleUpdate : handleStore}
              onCancel={() => {
                setIsModalOpen(false)
                setIsAddModalOpen(false)
                setEditingAddress(null)
              }}
              isLoading={isModalOpen ? updateAddressMutation.isPending : storeAddressMutation.isPending}
              submitText={isModalOpen ? 'Güncelle' : 'Kaydet'}
            />
          </div>
        </motion.div>
      )}
    </motion.div>
  )
}
