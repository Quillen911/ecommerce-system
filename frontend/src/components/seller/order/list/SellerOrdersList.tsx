'use client';

import { useEffect, useMemo, useState } from 'react';
import { motion } from 'framer-motion';
import { format } from 'date-fns';
import { tr } from 'date-fns/locale';
import { useRouter } from 'next/navigation';
import type { OrderItem } from '@/types/order';

export interface OrderItemsListProps {
  orderItems: OrderItem[];
}

const statusMap: Record<string, { label: string; badgeClass: string }> = {
  confirmed: { label: 'Onaylandı', badgeClass: 'bg-emerald-500 text-emerald-50' },
  pending: { label: 'Bekliyor', badgeClass: 'bg-amber-500 text-amber-50' },
  shipped: { label: 'Kargoya Teslim Edildi', badgeClass: 'bg-amber-500 text-amber-50' },
  cancelled: { label: 'İptal Edildi', badgeClass: 'bg-red-500 text-red-50' },
};

const STORAGE_KEY = 'seller_seen_orders';

export default function SellerOrdersList({ orderItems }: OrderItemsListProps) {
  const router = useRouter();
  const [dismissedIds, setDismissedIds] = useState<Set<number>>(new Set());

  useEffect(() => {
    if (typeof window === 'undefined') return;

    const stored = window.localStorage.getItem(STORAGE_KEY);
    if (!stored) return;

    try {
      const parsed: number[] = JSON.parse(stored);
      setDismissedIds(new Set(parsed));
    } catch {
      window.localStorage.removeItem(STORAGE_KEY);
    }
  }, []);

  const dismissBadge = (orderId: number) => {
    setDismissedIds(prev => {
      const next = new Set(prev);
      next.add(orderId);
      if (typeof window !== 'undefined') {
        window.localStorage.setItem(STORAGE_KEY, JSON.stringify(Array.from(next)));
      }
      return next;
    });
  };

  if (!orderItems?.length) {
    return (
      <div className="rounded-2xl border border-dashed border-neutral-200 bg-white p-8 text-center text-sm text-neutral-500">
        Henüz sipariş bulunmuyor.
      </div>
    );
  }

  return (
    <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
      {orderItems.map((orderItem, index) => {
        const statusInfo = statusMap[orderItem.status] ?? statusMap.confirmed;
        const createdAt = useMemo(
          () => (orderItem.created_at ? new Date(orderItem.created_at) : null),
          [orderItem.created_at],
        );
        const isNew =
          !!createdAt &&
          Date.now() - createdAt.getTime() < 24 * 60 * 60 * 1000 &&
          !dismissedIds.has(orderItem.id);

        const handleClick = () => {
          dismissBadge(orderItem.id);
          router.push(`/seller/order/${orderItem.id}`);
        };

        return (
          <motion.article
            key={orderItem.id}
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.4, delay: index * 0.06 }}
            className="flex cursor-pointer flex-col gap-5 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md"
            onClick={handleClick}
          >
            <header className="flex items-start justify-between gap-4">
              <div>
                <h2 className="text-xl font-semibold text-gray-900">Sipariş #{orderItem.id}</h2>
                {createdAt ? (
                  <p className="text-gray-500">
                    {format(createdAt, 'd MMMM yyyy, HH:mm', { locale: tr })}
                  </p>
                ) : null}
              </div>

              <div className="flex flex-col items-end gap-2">
                <span
                  className={`inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide ${statusInfo.badgeClass}`}
                >
                  {statusInfo.label}
                </span>
                {isNew ? (
                  <span className="inline-flex h-7 items-center rounded-full bg-sky-500 px-3 text-xs font-semibold text-white">
                    Yeni
                  </span>
                ) : null}
              </div>
            </header>
          </motion.article>
        );
      })}
    </div>
  );
}
