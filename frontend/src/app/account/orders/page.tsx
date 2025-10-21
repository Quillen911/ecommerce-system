'use client'
import { motion } from 'framer-motion'
import { useMe } from '@/hooks/useAuthQuery'
import { useOrder } from '@/hooks/useOrderQuery'
import OrdersList from '@/components/order/OrderList'

export default function OrdersPage() {
  const { data: me } = useMe()
  const { data: orders, isLoading, error } = useOrder(me?.id)

  if (error) {
    return (
      <div className="bg-red-50 border border-red-200 rounded-xl p-5 sm:p-6">
        <div className="flex items-center space-x-2 text-red-700">
          <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path
              fillRule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
            />
          </svg>
          <span className="text-base sm:text-lg font-medium">Siparişler yüklenirken bir hata oluştu</span>
        </div>
        <p className="mt-2 text-sm sm:text-base text-red-600 break-words">{error.message}</p>
      </div>
    )
  }

  if (!orders || orders.length === 0) {
    return (
      <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="text-center py-12 sm:py-16 px-4">
        <div className="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-5 sm:mb-6 bg-gray-100 rounded-full flex items-center justify-center">
          <svg className="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 111.314 0z" />
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>
        <h3 className="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Henüz siparişiniz yok</h3>
        <p className="text-sm sm:text-base text-gray-600">Alışverişe devam ederek ilk siparişinizi oluşturabilirsiniz.</p>
      </motion.div>
    )
  }

  return (
    <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.5 }}>
      <div className="mb-6 sm:mb-8">
        <h1 className="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Siparişlerim</h1>
        <div className="w-12 sm:w-16 h-1 bg-black rounded-full" />
        <p className="mt-3 text-sm sm:text-base text-gray-600">
          Son siparişleriniz aşağıda listeleniyor. Detayları sipariş kartlarının içinde bulabilirsiniz.
        </p>
      </div>
      <OrdersList orders={orders} />
    </motion.div>
  )
}
