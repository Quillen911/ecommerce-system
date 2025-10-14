'use client'

import { motion } from 'framer-motion'
import { Order } from '@/types/order'
import { format } from 'date-fns'
import { tr } from 'date-fns/locale'

export interface OrdersListProps {
  orders: Order[]
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
export default function OrdersList({ orders }: OrdersListProps) {
  return (
    <div className="grid grid-cols-1 xl:grid-cols-2 gap-6">
      {orders.map((order, index) => {
        const statusInfo =
          statusMap[order.status] ??
          statusMap.confirmed

        return (
          <motion.div
            key={order.id}
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.4, delay: index * 0.06 }}
            className="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col gap-5"
          >
            <div className="flex items-start justify-between gap-4">
              <div>
                <h2 className="text-xl font-semibold text-gray-900">
                  Sipariş #{order.order_number}
                </h2>
                <p className="text-gray-500">
                  {format(new Date(order.created_at), "d MMMM yyyy, HH:mm", {
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

            <div className="grid grid-cols-2 gap-4 text-sm text-gray-700">
              <div>
                <p className="font-medium text-gray-900">Ödenen Tutar</p>
                <p className="text-lg font-semibold text-gray-900">
                  {(order.grand_total_cents / 100).toLocaleString('tr-TR', {
                    style: 'currency',
                    currency: order.currency ?? 'TRY',
                  })}
                </p>
              </div>
              <div>
                <p className="font-medium text-gray-900">Ara Toplam</p>
                <p>
                  {(order.subtotal_cents / 100).toLocaleString('tr-TR', {
                    style: 'currency',
                    currency: order.currency ?? 'TRY',
                  })}
                </p>
              </div>
              <div>
                <p className="font-medium text-gray-900">İndirim</p>
                <p>
                  {(order.discount_cents / 100).toLocaleString('tr-TR', {
                    style: 'currency',
                    currency: order.currency ?? 'TRY',
                  })}
                </p>
              </div>
              <div>
                <p className="font-medium text-gray-900">Kargo Ücreti</p>
                <p>
                  {(order.cargo_price_cents === 0 ? "Ücretsiz" : order.cargo_price_cents / 100).toLocaleString('tr-TR', {
                    style: 'currency',
                    currency: order.currency ?? 'TRY',
                  })}
                </p>
              </div>
              <div>
                <p className="font-medium text-gray-900">Durum</p>
                <p>{statusInfo.label}</p>
              </div>
            </div>
          </motion.div>
        )
      })}
    </div>
  )
}
