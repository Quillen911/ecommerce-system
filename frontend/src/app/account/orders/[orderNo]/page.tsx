'use client';

import { useRouter, useParams } from 'next/navigation';
import { useOrderDetail } from '@/hooks/useOrderQuery';
import { useMe } from '@/hooks/useAuthQuery';
import { OrderDetailHeader } from '@/components/order/OrderDetailHeader';
import { AddressCard } from '@/components/order/AddressCard';
import { OrderItemsList } from '@/components/order/OrderItemsList';
import { OrderSummary } from '@/components/order/OrderSummary';
import type { OrderDetail } from '@/types/order';
import { ChevronLeftIcon } from '@heroicons/react/24/outline';

export default function OrderDetail() {
  const params = useParams<{ orderNo: string }>();
  const orderId = Number(params.orderNo);

  const { data: me } = useMe();
  const { data: orderDetail, isLoading, isError } = useOrderDetail(orderId, me?.id);
  if (isError) return <div>Hata oluştu.</div>;
  if (!orderDetail) return <div>Sipariş bulunamadı.</div>;

  return <OrderDetailView data={orderDetail} />;
}

type OrderDetailViewProps = {
  data: OrderDetail;
};

function OrderDetailView({ data }: OrderDetailViewProps) {
  console.log(data)
  const firstItem = data.order[0];
  const orderStatus =
    data.order.every(item => item.payment_status === 'refunded')
      ? 'refunded'
      : ((firstItem?.status as 'refunded' | 'processing' | 'completed' | undefined) ?? 'completed');
  const router = useRouter();
  return (
    <div className="min-h-screen bg-neutral-50 pb-12 pt-6">
      <div className="mx-auto flex w-full max-w-4xl flex-col gap-6 px-4">
        <div className="flex justify-between">
          <ChevronLeftIcon className="mb-4 cursor-pointer rounded-xl bg-white p-1 shadow-sm font-semibold text-sm text-neutral-500 w-8 h-8" onClick={() => router.push('/account/orders')} />
          <h2 className="text-base font-semibold text-neutral-900">Sipariş Detayları</h2>
        </div>
        <OrderDetailHeader
          orderNo={firstItem?.order_number ?? ''}
          createdAt={
            firstItem?.created_at
              ? new Date(firstItem.created_at).toLocaleString('tr-TR')
              : undefined
          }
          status={orderStatus}
        />

        <div className="grid gap-4 md:grid-cols-2">
          <AddressCard title="Teslimat Adresi" address={data.userShippingAddress} />
          <AddressCard title="Fatura Adresi" address={data.userBillingAddress} />
        </div>

        <section className="rounded-2xl bg-white p-4 shadow-sm">
          <h2 className="text-base font-semibold text-neutral-900">Sipariş İçeriği</h2>
          <OrderItemsList items={data.order} />
        </section>

        <OrderSummary items={data.order} />
      </div>
    </div>
  );
}
