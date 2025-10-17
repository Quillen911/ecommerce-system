"use client"
import { useMySeller } from '@/hooks/seller/useSellerAuthQuery'
import { useOrderList } from '@/hooks/seller/useOrderQuery'
import { motion } from 'framer-motion'
import SellerOrdersList from '@/components/seller/order/list/SellerOrdersList'
import { useState, useEffect } from 'react'
import LoadingState from '@/components/ui/LoadingState'

export default function Orders() {
    const { data: me } = useMySeller()
    const sellerId = me?.id
    const { data: orderItems, isLoading, error } = useOrderList(sellerId)
  const [hydrated, setHydrated] = useState(false)

  useEffect(() => {
    setHydrated(true)
  }, [])

  if (!hydrated) {
    return (
      <div className="space-y-6">
        <LoadingState label="Siparişler yükleniyor..." />
      </div>
    )
  }
    if (isLoading) {
        return (
            <div className="space-y-6">
                <LoadingState label="Siparişler yükleniyor..." />
            </div>
        )
    }
    
    if (error) {
        return (
            <div className="bg-red-50 border border-red-200 rounded-xl p-6">
                <div className="flex items-center space-x-2 text-red-700">
                    <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fillRule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        />
                    </svg>
                    <span className="text-lg font-medium">
                        Siparişler yüklenirken bir hata oluştu
                    </span>
                </div>
            </div>
        )
    }

    if (!orderItems) {
        return (
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                className="text-center py-16"
            >
                <div className="flex items-center justify-center min-h-64">
                    <div className="flex items-center space-x-2 text-gray-600">
                        <div className="w-4 h-4 border-2 border-gray-300 border-t-black rounded-2xl animate-spin" />
                        <span className="text-lg">Henüz siparişiniz yok</span>
                    </div>
                </div>
            </motion.div>
        )
    }
    
    return (
        <SellerOrdersList orderItems={orderItems} />
    )
}