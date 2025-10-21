import type { OrderItem } from '@/types/order';

type OrderSummaryProps = {
  items: OrderItem[];
};

export function OrderSummary({ items }: OrderSummaryProps) {
  const paidTotal = items.reduce((acc, item) => acc + item.paid_price_cents, 0);
  const refundedTotal = items.reduce((acc, item) => acc + item.refunded_price_cents, 0);
  const net = paidTotal - refundedTotal;

  return (
    <aside className="rounded-2xl bg-white p-4 shadow-sm">
      <h2 className="text-base font-semibold text-neutral-900">Özet</h2>
      <dl className="mt-3 space-y-2 text-sm text-neutral-600">
        <div className="flex justify-between">
          <dt>Toplam Ödenen</dt>
          <dd className="font-medium text-neutral-900">{(paidTotal / 100).toFixed(2)} ₺</dd>
        </div>
        <div className="flex justify-between">
          <dt>İade Edilen</dt>
          <dd className="font-medium text-rose-600">-{(refundedTotal / 100).toFixed(2)} ₺</dd>
        </div>
        <div className="flex justify-between border-t border-neutral-100 pt-3 text-base font-semibold text-neutral-900">
          <dt>Net</dt>
          <dd>{(net / 100).toFixed(2)} ₺</dd>
        </div>
      </dl>
    </aside>
  );
}
