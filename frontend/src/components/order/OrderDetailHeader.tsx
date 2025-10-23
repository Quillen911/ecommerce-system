// components/order/OrderDetailHeader.tsx
'use client';

import { motion } from 'framer-motion';

type OrderDetailHeaderProps = {
  orderNo: string;
  createdAt?: string;
  status: 'refunded' | 'pending' | 'confirmed' | 'shipped';
};

export function OrderDetailHeader({ orderNo, createdAt, status }: OrderDetailHeaderProps) {
  const statusLabel =
    status === 'refunded' ? 'İade Edildi' : status === 'pending' ? 'Hazırlanıyor' : status === 'confirmed' ? 'Onaylandı' : status === 'shipped' ? 'Kargoya Verildi' : 'Tamamlandı';

  return (
    <motion.header
      className="flex flex-col gap-2 rounded-2xl bg-white p-4 shadow-sm md:flex-row md:items-center md:justify-between"
      initial={{ opacity: 0, y: 8 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.35 }}
    >
      <div>
        <p className="text-sm text-neutral-500">Sipariş Numarası</p>
        <h1 className="text-xl font-semibold text-neutral-900 md:text-2xl">#{orderNo}</h1>
        {createdAt ? <p className="text-sm text-neutral-500">Oluşturulma: {createdAt}</p> : null}
      </div>

      <span
        className={`inline-flex h-9 items-center justify-center rounded-full px-4 text-sm font-medium ${
          status === 'refunded'
            ? 'bg-rose-50 text-rose-600'
            : status === 'pending'
            ? 'bg-amber-50 text-amber-600'
            : status === 'confirmed'
            ? 'bg-green-50 text-green-600'
            : status === 'shipped'
            ? 'bg-blue-50 text-blue-600'
            : 'bg-emerald-50 text-emerald-600'
        }`}
      >
        {statusLabel}
      </span>
    </motion.header>
  );
}
