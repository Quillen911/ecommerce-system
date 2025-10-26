'use client';

import { useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import { useCreateVariant, useUpdateVariant } from '@/hooks/seller/useProductQuery';
import type {
  ProductVariant,
  StoreProductVariantSizeInventoryRequest,
  StoreProductVariantSizeRequest,
} from '@/types/seller/product';

type VariantEditFormValues = {
  color_name: string;
  color_code: string;
  price_cents: number;
  is_popular: boolean;
  is_active: boolean;
  create_size_option_id: number;
  create_size_price_cents: number;
  create_size_on_hand: number;
  create_size_reserved: number;
  create_size_min_stock: number;
  create_size_warehouse_id: number | null;
};

type VariantEditModalProps = {
  productId: number;
  variant: ProductVariant | null;
  onClose: () => void;
  onUpdated: () => Promise<void> | void;
};

export default function VariantEditModal({
  productId,
  variant,
  onClose,
  onUpdated,
}: VariantEditModalProps) {
  const isEdit = Boolean(variant?.id);
  const { mutateAsync: createVariant, isPending: isCreatePending } = useCreateVariant();
  const { mutateAsync: updateVariant, isPending: isUpdatePending } = useUpdateVariant();

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<VariantEditFormValues>({
    mode: 'onBlur',
    defaultValues: {
      color_name: variant?.color_name ?? '',
      color_code: variant?.color_code ?? '#000000',
      price_cents: variant?.price_cents ?? 0,
      is_popular: variant?.is_popular ?? false,
      is_active: variant?.is_active ?? true,
      create_size_option_id: 0,
      create_size_price_cents: variant?.price_cents ?? 0,
      create_size_on_hand: 0,
      create_size_reserved: 0,
      create_size_min_stock: 0,
      create_size_warehouse_id: null,
    },
  });

  useEffect(() => {
    reset({
      color_name: variant?.color_name ?? '',
      color_code: variant?.color_code ?? '#000000',
      price_cents: variant?.price_cents ?? 0,
      is_popular: variant?.is_popular ?? false,
      is_active: variant?.is_active ?? true,
      create_size_option_id: 0,
      create_size_price_cents: variant?.price_cents ?? 0,
      create_size_on_hand: 0,
      create_size_reserved: 0,
      create_size_min_stock: 0,
      create_size_warehouse_id: null,
    });
  }, [variant, reset]);

  const buildCreateSizesPayload = (values: VariantEditFormValues): StoreProductVariantSizeRequest[] => {
    const inventoryEntry: StoreProductVariantSizeInventoryRequest = {
      warehouse_id:
        values.create_size_warehouse_id !== null && Number.isFinite(values.create_size_warehouse_id)
          ? values.create_size_warehouse_id
          : null,
      on_hand: Number.isFinite(values.create_size_on_hand) ? values.create_size_on_hand : 0,
      reserved: Number.isFinite(values.create_size_reserved) ? values.create_size_reserved : 0,
      min_stock_level: Number.isFinite(values.create_size_min_stock) ? values.create_size_min_stock : 0,
    };

    const sizePayload: StoreProductVariantSizeRequest = {
      size_option_id: Number.isFinite(values.create_size_option_id) ? values.create_size_option_id : 0,
      price_cents:
        Number.isFinite(values.create_size_price_cents) && values.create_size_price_cents > 0
          ? values.create_size_price_cents
          : values.price_cents,
      inventory: inventoryEntry,
    };

    return [sizePayload];
  };

  const onSubmit = async (values: VariantEditFormValues) => {
    if (isEdit && variant) {
      await updateVariant(
        {
          productId,
          variantId: variant.id,
          id: variant.id,
          color_name: values.color_name,
          color_code: values.color_code,
          price_cents: values.price_cents,
          is_popular: values.is_popular,
          is_active: values.is_active,
        },
        {
          onSuccess: async () => {
            toast.success('Varyant güncellendi');
            await onUpdated();
          },
          onError: (error: unknown) =>
            toast.error(error instanceof Error ? error.message : 'Güncelleme başarısız.'),
        },
      );
      return;
    }

    const sizesPayload = buildCreateSizesPayload(values);

    await createVariant(
      {
        productId,
        color_name: values.color_name,
        color_code: values.color_code,
        price_cents: values.price_cents,
        is_popular: values.is_popular ?? false,
        is_active: values.is_active ?? true,
        images: null,
        sizes: sizesPayload,
      },
      {
        onSuccess: async () => {
          toast.success('Varyant oluşturuldu');
          await onUpdated();
        },
        onError: (error: unknown) =>
          toast.error(error instanceof Error ? error.message : 'Oluşturma başarısız.'),
      },
    );
  };

  const isBusy = isSubmitting || isCreatePending || isUpdatePending;

  const fieldClass = (hasError: boolean) =>
    `w-full rounded-xl border px-3 py-2 text-sm transition focus:outline-none ${
      hasError
        ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-200'
        : 'border-gray-200 focus:border-gray-900 focus:ring-2 focus:ring-gray-200'
    }`;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-2 sm:p-4">
      <div className="w-full max-w-md sm:max-w-lg rounded-3xl bg-white p-4 sm:p-6 shadow-2xl">
        <header className="mb-4 flex items-center justify-between">
          <div>
            <h3 className="text-base sm:text-lg font-semibold text-gray-900">
              {isEdit ? 'Varyantı Düzenle' : 'Yeni Varyant Ekle'}
            </h3>
            {isEdit && <p className="text-[11px] sm:text-xs text-gray-500">SKU: {variant?.sku ?? '—'}</p>}
          </div>
          <button type="button" onClick={onClose} className="cursor-pointer text-sm text-gray-400 hover:text-gray-600">
            Kapat
          </button>
        </header>

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <div>
            <label className="block text-xs font-medium text-gray-600">Renk Adı</label>
            <input
              type="text"
              className={fieldClass(Boolean(errors.color_name))}
              {...register('color_name', {
                required: 'Renk adı zorunlu.',
                minLength: { value: 2, message: 'Renk adı en az 2 karakter olmalı.' },
                maxLength: { value: 120, message: 'Renk adı en fazla 120 karakter olmalı.' },
              })}
            />
            {errors.color_name && <p className="mt-1 text-xs text-red-500">{errors.color_name.message}</p>}
          </div>

          <div>
            <label className="block text-xs font-medium text-gray-600">Renk Kodu (HEX)</label>
            <input
              type="text"
              className={fieldClass(Boolean(errors.color_code))}
              {...register('color_code', {
                required: 'Renk kodu zorunlu.',
                pattern: { value: /^#[0-9A-Fa-f]{6}$/, message: 'Geçerli bir HEX kodu girin. Örn: #1A2B3C' },
              })}
            />
            {errors.color_code && <p className="mt-1 text-xs text-red-500">{errors.color_code.message}</p>}
          </div>

          <div>
            <label className="block text-xs font-medium text-gray-600">Fiyat (kuruş)</label>
            <input
              type="number"
              className={fieldClass(Boolean(errors.price_cents))}
              {...register('price_cents', {
                valueAsNumber: true,
                required: 'Fiyat zorunlu.',
                validate: (value) => {
                  if (!Number.isFinite(value)) return 'Geçerli bir sayı girin.';
                  return value > 0 || 'Fiyat 0’dan büyük olmalı.';
                },
              })}
            />
            {errors.price_cents && <p className="mt-1 text-xs text-red-500">{errors.price_cents.message}</p>}
          </div>

          <div className="flex flex-wrap gap-4 sm:gap-6">
            <label className="cursor-pointer flex items-center gap-2 text-xs text-gray-600">
              <input type="checkbox" {...register('is_popular')} />
              Popüler
            </label>
            <label className="cursor-pointer flex items-center gap-2 text-xs text-gray-600">
              <input type="checkbox" {...register('is_active')} />
              Aktif
            </label>
          </div>

          {!isEdit && (
            <div className="space-y-3 rounded-2xl border border-gray-200 bg-gray-50 p-3 sm:p-4">
              <h4 className="text-xs font-semibold text-gray-700">İlk Beden Bilgileri</h4>

              <div>
                <label className="block text-[11px] font-medium text-gray-600">Beden ID</label>
                <input
                  type="number"
                  className={fieldClass(Boolean(errors.create_size_option_id))}
                  {...register('create_size_option_id', {
                    valueAsNumber: true,
                    required: 'Beden seçimi zorunlu.',
                    validate: (value) => {
                      if (!Number.isFinite(value)) return 'Geçerli bir sayı girin.';
                      return value >= 0 || 'Beden ID negatif olamaz.';
                    },
                  })}
                />
                {errors.create_size_option_id && (
                  <p className="mt-1 text-xs text-red-500">{errors.create_size_option_id.message}</p>
                )}
              </div>

              <div>
                <label className="block text-[11px] font-medium text-gray-600">Beden Fiyatı (kuruş)</label>
                <input
                  type="number"
                  className={fieldClass(Boolean(errors.create_size_price_cents))}
                  {...register('create_size_price_cents', {
                    valueAsNumber: true,
                    validate: (value) => {
                      if (!Number.isFinite(value)) return true;
                      return value >= 0 || 'Fiyat negatif olamaz.';
                    },
                  })}
                />
                <p className="mt-1 text-[11px] text-gray-500">Boş bırakırsanız ana fiyat kullanılacak.</p>
                {errors.create_size_price_cents && (
                  <p className="mt-1 text-xs text-red-500">{errors.create_size_price_cents.message}</p>
                )}
              </div>

              <div className="grid gap-3 sm:grid-cols-2">
                <div>
                  <label className="block text-[11px] font-medium text-gray-600">Stok (On Hand)</label>
                  <input
                    type="number"
                    className={fieldClass(Boolean(errors.create_size_on_hand))}
                    {...register('create_size_on_hand', {
                      valueAsNumber: true,
                      required: 'Stok miktarı zorunlu.',
                      validate: (value) => {
                        if (!Number.isFinite(value)) return 'Geçerli bir sayı girin.';
                        return value >= 0 || 'Stok negatif olamaz.';
                      },
                    })}
                  />
                  {errors.create_size_on_hand && (
                    <p className="mt-1 text-xs text-red-500">{errors.create_size_on_hand.message}</p>
                  )}
                </div>

                <div>
                  <label className="block text-[11px] font-medium text-gray-600">Rezerve</label>
                  <input
                    type="number"
                    className={fieldClass(Boolean(errors.create_size_reserved))}
                    {...register('create_size_reserved', {
                      valueAsNumber: true,
                      required: 'Rezerve miktarı zorunlu.',
                      validate: (value) => {
                        if (!Number.isFinite(value)) return 'Geçerli bir sayı girin.';
                        return value >= 0 || 'Rezerve negatif olamaz.';
                      },
                    })}
                  />
                  {errors.create_size_reserved && (
                    <p className="mt-1 text-xs text-red-500">{errors.create_size_reserved.message}</p>
                  )}
                </div>

                <div>
                  <label className="block text-[11px] font-medium text-gray-600">Minimum Stok</label>
                  <input
                    type="number"
                    className={fieldClass(Boolean(errors.create_size_min_stock))}
                    {...register('create_size_min_stock', {
                      valueAsNumber: true,
                      required: 'Minimum stok zorunlu.',
                      validate: (value) => {
                        if (!Number.isFinite(value)) return 'Geçerli bir sayı girin.';
                        return value >= 0 || 'Minimum stok negatif olamaz.';
                      },
                    })}
                  />
                  {errors.create_size_min_stock && (
                    <p className="mt-1 text-xs text-red-500">{errors.create_size_min_stock.message}</p>
                  )}
                </div>

                <div>
                  <label className="block text-[11px] font-medium text-gray-600">Depo ID (opsiyonel)</label>
                  <input
                    type="number"
                    className={fieldClass(Boolean(errors.create_size_warehouse_id))}
                    {...register('create_size_warehouse_id', {
                      valueAsNumber: true,
                      validate: (value) => {
                        if (Number.isNaN(value) || value === null) return true;
                        return value >= 0 || 'Depo ID negatif olamaz.';
                      },
                    })}
                  />
                  {errors.create_size_warehouse_id && (
                    <p className="mt-1 text-xs text-red-500">{errors.create_size_warehouse_id.message}</p>
                  )}
                </div>
              </div>
            </div>
          )}

          <footer className="mt-4 flex justify-end gap-2 pt-2">
            <button
              type="submit"
              disabled={isBusy}
              className="cursor-pointer w-full sm:w-auto rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black disabled:cursor-not-allowed disabled:opacity-70"
            >
              {isBusy ? 'Kaydediliyor...' : isEdit ? 'Güncelle' : 'Oluştur'}
            </button>
          </footer>
        </form>
      </div>
    </div>
  );
}
