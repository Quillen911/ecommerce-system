'use client';

import Image from 'next/image';
import { useRouter } from 'next/navigation';
import type { OrderItem } from '@/types/order';

type OrderItemsListProps = {
  items: OrderItem[];
};

export function OrderItemsList({ items }: OrderItemsListProps) {
  const router = useRouter();

  if (!items?.length) {
    return (
      <p className="rounded-xl bg-white p-4 text-sm text-neutral-500 shadow-sm">
        Bu siparişte ürün bulunmuyor.
      </p>
    );
  }

  return (
    <ul className="space-y-4">
      {items.map(item => {
        const variant = item.product.variants.find(v =>
          v.sizes.some(size => size.id === item.variant_size_id),
        );
        const image = variant?.images?.[0]?.image ?? null;
        const variantSlug = variant?.slug ?? item.product.slug;

        return (
          <li
            key={item.id}
            className="flex flex-col gap-3 rounded-2xl bg-white p-4 shadow-sm xs:flex-row xs:items-center"
          >
            <div className="relative h-24 w-24 flex-shrink-0 overflow-hidden rounded-xl bg-neutral-100">
              {image ? (
                <Image
                  src={image}
                  alt={item.product.title}
                  fill
                  sizes="96px"
                  className="cursor-pointer object-contain"
                  onClick={() => router.push(`/product/${variantSlug}`)}
                />
              ) : null}
            </div>

            <div className="flex flex-1 flex-col gap-2">
              <div className="flex flex-wrap items-center justify-between gap-2">
                <h3 className="text-base font-semibold text-neutral-900">{item.product_title}</h3>
                {item.status === 'refunded' ? (
                  <span className="inline-flex h-7 items-center rounded-full bg-rose-50 px-3 text-xs font-medium text-rose-600">
                    İade Edildi
                  </span>
                ) : null}
              </div>

              <p className="text-sm text-neutral-500">{item.product_category_title ?? 'Kategori'}</p>
              <p className="text-sm text-neutral-600">
                Beden: {item.size_name ?? '-'} • Renk: {item.color_name ?? '-'}
              </p>

              <div className="flex flex-wrap justify-between gap-2 text-sm text-neutral-600">
                <span>Adet: {item.quantity}</span>
                <span>
                  Tutar:{' '}
                  <span className="font-semibold text-neutral-900">
                    {(item.paid_price_cents / 100).toFixed(2)} ₺
                  </span>
                </span>
              </div>
            </div>
          </li>
        );
      })}
    </ul>
  );
}
