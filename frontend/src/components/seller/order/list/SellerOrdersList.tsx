"use client"
import type { OrderItem } from '@/types/order'
import { motion } from 'framer-motion'
import { format } from 'date-fns'
import { tr } from 'date-fns/locale'
import { useRouter } from 'next/navigation'

export interface OrderItemsListProps {
  orderItems: OrderItem[]
}

const statusMap: Record<string, { label: string; badgeClass: string }> = {
    confirmed: {
      label: 'Onaylandı',
      badgeClass: 'bg-emerald-500 text-emerald-50',
    },
    pending: {
      label: 'Bekliyor',
      badgeClass: 'bg-amber-500 text-amber-50',
    },
    shipped: {
      label: 'Kargoya Teslim Edildi',
      badgeClass: 'bg-amber-500 text-amber-50',
    },
    cancelled: {
      label: 'İptal Edildi',
      badgeClass: 'bg-red-500 text-red-50',
    },
  }

export default function SellerOrdersList({ orderItems }: OrderItemsListProps) {
    const router = useRouter()
    return (
        <div className="grid grid-cols-1 xl:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-6">

            {orderItems.map((orderItem, index) => {
                const statusInfo =
                statusMap[orderItem.status] ??
                statusMap.confirmed
                return (
                    <motion.div
                        key={orderItem.id}
                        initial={{ opacity: 0, y: 30 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.4, delay: index * 0.06 }}
                        className="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col gap-5 cursor-pointer"
                        onClick={() => router.push(`/seller/order/${orderItem.id}`)}
                    >
                        <div className="flex items-start justify-between gap-4">
                            <div>
                                <h2 className="text-xl font-semibold text-gray-900">
                                    Sipariş #{orderItem.id}
                                </h2>
                                <p className="text-gray-500">
                                    {format(new Date(orderItem.created_at), "d MMMM yyyy, HH:mm", {
                                        locale: tr,
                                    })}
                                </p>
                            </div>
                            <span
                                className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide ${statusInfo.badgeClass}`}
                            >
                                {statusInfo.label}
                            </span>
                        </div>
                    </motion.div>
                )
            })}
            
        </div>
    )
}