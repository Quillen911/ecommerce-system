"use client";

import { useMemo, useState, useEffect } from "react";
import Image from "next/image";
import { useParams } from "next/navigation";
import { FiPackage, FiTag, FiHash, FiTruck, FiDollarSign, FiArrowLeft } from "react-icons/fi";
import { useOrderDetail } from "@/hooks/seller/useOrderQuery";
import LoadingState from '@/components/ui/LoadingState'
import { motion } from "framer-motion";
import { useRouter } from "next/navigation";
import ProductImage from "@/components/ui/ProductImage";

const formatCents = (value?: number | null) =>
  typeof value === "number" ? (value / 100).toLocaleString("tr-TR", { style: "currency", currency: "TRY" }) : "—";

const formatDate = (iso?: string | null) =>
  iso ? new Date(iso).toLocaleString("tr-TR", { dateStyle: "medium", timeStyle: "short" }) : "—";

export default function OrderDetail() {
  const params = useParams<{ id: string }>();
  const orderId = Number(params.id);
  const { data: orderItem, isLoading, isError } = useOrderDetail(orderId);
  const router = useRouter()

  const [isMounted, setIsMounted] = useState(false);

  useEffect(() => {
    setIsMounted(true);
  }, []);

  const selectedVariant = useMemo(() => {
    if (!orderItem?.product?.variants?.length) return undefined;

    const matchFromSize = orderItem.variant_size_id
      ? orderItem.product.variants
          .flatMap((variant) =>
            (variant.sizes ?? []).map((size) => ({
              variant,
              size,
            })) 
          )
          .find(({ size }) => size.id === orderItem.variant_size_id)
      : undefined;

    return (
      matchFromSize ?? {
        variant: orderItem.product.variants.find((variant) => variant.id === orderItem.product?.variants?.[0]?.id),
        size: undefined,
      }
    );
  }, [orderItem]);

    if (!isMounted) {
    return (
      <div className="space-y-6">
        <LoadingState label="Sipariş detayları yükleniyor..." />
      </div>
    );
  }


  if (isLoading) {
    return (
        <div className="space-y-6">
            <LoadingState label="Sipariş detayları yükleniyor..." />
        </div>
    );
  }
    if (!orderItem) {
        return (
            <div className="space-y-6">
                <div onClick={() => router.push('/seller/order')} className="cursor-pointer">
                    <FiArrowLeft className="h-4 w-4" />
                    Geri
                </div>
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                className="text-center py-16"
            >
                <div className="flex items-center justify-center min-h-64">
                    <div className="flex items-center space-x-2 text-gray-600">
                        <span className="text-xl font-semibold">Sipariş bulunamadı</span>
                    </div>
                </div>
            </motion.div>
            </div>
        )
    }

    if (isError) {
        return (
        <div className="rounded-3xl border border-red-200 bg-red-50 p-6 text-sm text-red-700">
            Sipariş detayları alınırken bir sorun oluştu. Lütfen tekrar deneyin.
        </div>
        ); 
    }

  const variant = selectedVariant?.variant;
  const size = selectedVariant?.size;
    console.log(selectedVariant, orderItem );
  const mainImage = variant?.images?.[0]?.image ;
  return (
    <div className="space-y-6">
      <div className="flex flex-col gap-4 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
        <div className="space-y-2">
            <div onClick={() => router.push('/seller/order')} className="cursor-pointer">
                <FiArrowLeft className="h-4 w-4" />
                Geri
            </div>
          <div className="inline-flex items-center gap-2 rounded-full bg-gray-900/10 px-3 py-1 text-xs font-semibold text-gray-900">
            <FiHash className="h-4 w-4" />
            Sipariş #{orderItem.order_number}
          </div>
          <h1 className="text-2xl font-semibold text-gray-900">{orderItem.product_title}</h1>
          <div className="flex flex-wrap items-center gap-4 text-sm text-gray-600">
            <span className="inline-flex items-center gap-2">
              <FiTruck className="h-4 w-4 text-gray-500" />
              Durum:{" "}
              <span className="font-medium text-gray-900">
                {orderItem.status ?? "Bilinmiyor"}
              </span>
            </span>
            <span className="inline-flex items-center gap-2">
              <FiDollarSign className="h-4 w-4 text-gray-500" />
              Ödeme:{" "}
              <span className="font-medium text-gray-900">
                {orderItem.payment_status ?? "—"}
              </span>
            </span>
            <span className="inline-flex items-center gap-2">
              <FiTag className="h-4 w-4 text-gray-500" />
              İşlem ID:{" "}
              <span className="font-medium text-gray-900">
                {orderItem.payment_transaction_id ?? "—"}
              </span>
            </span>
          </div>
        </div>
        <div className="rounded-2xl bg-gray-100 p-4 text-right text-sm text-gray-600">
          <p>Oluşturma: <span className="font-medium text-gray-900">{formatDate(orderItem.created_at)}</span></p>
          <p>Son Güncelleme: <span className="font-medium text-gray-900">{formatDate(orderItem.updated_at)}</span></p>
        </div>
      </div>

      <div className="grid gap-6 lg:grid-cols-[320px,1fr]">
        <div className="rounded-3xl border border-gray-200 bg-white p-4 shadow-sm">
          {mainImage ? (
             <div className="relative h-72 overflow-hidden rounded-2xl bg-gray-50">
                <ProductImage
                product={mainImage}
                alt={orderItem.product_title}
                className="h-full w-full"
                aspectRatio="portrait"
                breakpoint="mobile"
                />
            </div>
          ) : (
            <div className="flex h-72 items-center justify-center rounded-2xl border border-dashed border-gray-300 bg-gray-50 text-sm text-gray-500">
              Görsel bulunamadı
            </div>
          )}
          <div className="mt-4 space-y-2 rounded-2xl bg-gray-50 p-4 text-sm text-gray-700">
            <p>
              <span className="font-semibold text-gray-900">Ürün Adı:</span> {orderItem.product_title ?? "—"}
            </p>
            <p>
              <span className="font-semibold text-gray-900">Kategori:</span>{" "}
              {orderItem.product_category_title ?? "—"}
            </p>
            <p>
              <span className="font-semibold text-gray-900">Seçilen Beden:</span>{" "}
              {size?.size_option?.value ?? orderItem.size_name ?? "—"}
            </p>
            <p>
              <span className="font-semibold text-gray-900">Seçilen Renk:</span>{" "}
              {variant?.color_name ?? orderItem.color_name ?? "—"}
            </p>
          </div>
        </div>

        <div className="space-y-6">
          <div className="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 className="flex items-center gap-2 text-base font-semibold text-gray-900">
              <FiPackage className="h-5 w-5 text-gray-500" />
              Sipariş Özeti
            </h2>

            <dl className="mt-4 grid gap-4 sm:grid-cols-2">
              <div className="rounded-2xl bg-gray-50 p-4">
                <dt className="text-xs uppercase tracking-wide text-gray-500">Adet</dt>
                <dd className="mt-2 text-xl font-semibold text-gray-900">{orderItem.quantity}</dd>
              </div>

              <div className="rounded-2xl bg-gray-50 p-4">
                <dt className="text-xs uppercase tracking-wide text-gray-500">İade Edilen Adet</dt>
                <dd className="mt-2 text-xl font-semibold text-gray-900">{orderItem.refunded_quantity ?? 0}</dd>
              </div>

              <div className="rounded-2xl bg-gray-50 p-4">
                <dt className="text-xs uppercase tracking-wide text-gray-500">Vergi Oranı</dt>
                <dd className="mt-2 text-lg font-semibold text-gray-900">
                  {orderItem.tax_rate ? `${orderItem.tax_rate / 100}%` : "—"}
                </dd>
              </div>

              <div className="rounded-2xl bg-gray-50 p-4">
                <dt className="text-xs uppercase tracking-wide text-gray-500">İade Edilen Tutar</dt>
                <dd className="mt-2 text-lg font-semibold text-gray-900">
                  {formatCents(orderItem.refunded_price_cents)}
                </dd>
              </div>
            </dl>
          </div>

          <div className="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 className="flex items-center gap-2 text-base font-semibold text-gray-900">
              <FiDollarSign className="h-5 w-5 text-gray-500" />
              Fiyatlandırma
            </h2>

            <div className="mt-4 grid gap-3 text-sm text-gray-700">
              <div className="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                <span>Liste Fiyatı</span>
                <span className="font-medium text-gray-900">{formatCents(orderItem.price_cents)}</span>
              </div>
              <div className="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                <span>İndirim</span>
                <span className="font-medium text-gray-900">{formatCents(orderItem.discount_price_cents)}</span>
              </div>
              <div className="flex items-center justify-between rounded-2xl bg-gray-100 px-4 py-3 text-base font-semibold text-gray-900">
                <span>Ödenen Tutar</span>
                <span>{formatCents(orderItem.paid_price_cents)}</span>
              </div>
              <div className="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                <span>Vergi Tutarı</span>
                <span className="font-medium text-gray-900">{formatCents(orderItem.tax_amount_cents)}</span>
              </div>
            </div>
          </div>

          {orderItem.selected_options && (
            <div className="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
              <h2 className="text-base font-semibold text-gray-900">Seçilen Opsiyonlar</h2>
              <pre className="mt-3 overflow-x-auto rounded-2xl bg-gray-50 p-4 text-xs text-gray-800">
                {orderItem.selected_options}
              </pre>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
