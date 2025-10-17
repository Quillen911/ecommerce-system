import { useEffect, useMemo } from 'react';
import { useForm } from 'react-hook-form';
import { Campaign, CampaignType } from '@/types/seller/campaign';

export interface CampaignFormValues {
  name: string;
  description?: string | null;
  code?: string | null;
  type: CampaignType;
  discount_value?: number | null;
  buy_quantity?: number | null;
  pay_quantity?: number | null;
  min_subtotal?: number | null;
  usage_limit?: number | null;
  is_active?: boolean;
  starts_at?: string | null;
  ends_at?: string | null;
  product_ids?: number[] | null;
  category_ids?: number[] | null;
}

interface CampaignFormProps {
  initialValues: CampaignFormValues | Campaign;
  onSubmit: (values: CampaignFormValues) => Promise<void> | void;
  loading?: boolean;
}

const typeOptions: { value: CampaignType; label: string; description: string }[] = [
  { value: 'percentage', label: 'Yüzdesel İndirim', description: 'Sepet toplamı üzerinden % düşer.' },
  { value: 'fixed', label: 'Sabit Tutar', description: 'Sepetten belirli tutar düşer.' },
  { value: 'x_buy_y_pay', label: 'X Al Y Öde', description: 'Ürün bazlı kampanyalar için.' },
];

function Section({
  title,
  description,
  children,
}: {
  title: string;
  description: string;
  children: React.ReactNode;
}) {
  return (
    <section className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <header className="mb-6">
        <h3 className="text-base font-semibold text-gray-900">{title}</h3>
        <p className="mt-1 text-sm text-gray-500">{description}</p>
      </header>
      <div className="space-y-4">
        {children}
      </div>
    </section>
  );
}

export default function CampaignForm({ initialValues, onSubmit, loading }: CampaignFormProps) {
  const preparedDefaults = useMemo<CampaignFormValues>(() => {
    const base = initialValues as CampaignFormValues;
    return {
      ...base,
      description: base.description ?? '',
      code: base.code ?? '',
      discount_value: base.discount_value ?? null,
      buy_quantity: base.buy_quantity ?? null,
      pay_quantity: base.pay_quantity ?? null,
      min_subtotal: base.min_subtotal ?? null,
      usage_limit: base.usage_limit ?? null,
      starts_at: base.starts_at ?? '',
      ends_at: base.ends_at ?? '',
      product_ids: base.product_ids ?? null,
      category_ids: base.category_ids ?? null,
      is_active: base.is_active ?? true,
    };
  }, [initialValues]);

  const {
    register,
    handleSubmit,
    watch,
    reset,
    setError,
    clearErrors,
    formState: { errors },
  } = useForm<CampaignFormValues>({
    defaultValues: preparedDefaults,
  });

  useEffect(() => {
    reset(preparedDefaults);
  }, [preparedDefaults, reset]);

  const type = watch('type');
  const productIds = watch('product_ids');
  const categoryIds = watch('category_ids');

  useEffect(() => {
    const hasProducts = Array.isArray(productIds) && productIds.filter(Boolean).length > 0;
    const hasCategories = Array.isArray(categoryIds) && categoryIds.filter(Boolean).length > 0;

    if (hasProducts || hasCategories) {
      clearErrors(['product_ids', 'category_ids']);
    }
  }, [productIds, categoryIds, clearErrors]);

  const ensureProductOrCategory = (values: CampaignFormValues) => {
    const hasProducts = Array.isArray(values.product_ids) && values.product_ids.filter(Boolean).length > 0;
    const hasCategories = Array.isArray(values.category_ids) && values.category_ids.filter(Boolean).length > 0;

    if (!hasProducts && !hasCategories) {
      const message = 'En az bir ürün ID veya kategori ID girmelisiniz.';
      setError('product_ids', { type: 'manual', message });
      setError('category_ids', { type: 'manual', message });
      return false;
    }
    return true;
  };

  const submitHandler = handleSubmit(async (values) => {
    if (!ensureProductOrCategory(values)) return;

    const sanitized: CampaignFormValues = {
      ...values,
      product_ids: Array.isArray(values.product_ids)
        ? values.product_ids.filter((id): id is number => Number.isInteger(id))
        : null,
      category_ids: Array.isArray(values.category_ids)
        ? values.category_ids.filter((id): id is number => Number.isInteger(id))
        : null,
      discount_value: values.discount_value ?? null,
      buy_quantity: values.buy_quantity ?? null,
      pay_quantity: values.pay_quantity ?? null,
      min_subtotal: values.min_subtotal ?? null,
      usage_limit: values.usage_limit ?? null,
      starts_at: values.starts_at || null,
      ends_at: values.ends_at || null,
    };

    await onSubmit(sanitized);
  });

  const showDiscountField = type === 'percentage' || type === 'fixed';
  const showQuantityFields = type === 'x_buy_y_pay';

  return (
    <form className="flex flex-col gap-7" onSubmit={submitHandler}>
       <Section
        title="Genel Bilgiler"
        description="Kampanya adı, kodu ve kısa açıklama gibi temel bilgileri giriniz."
      >
        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Kampanya Adı <span className="text-red-500">*</span>
            </label>
            <input
              className={`block w-full rounded-md border ${
                errors.name ? 'border-red-300' : 'border-gray-300'
              } shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5`}
              placeholder="Örn. Yaz İndirimi"
              {...register('name', { required: 'Bu alan zorunludur' })}
            />
            {errors.name && (
              <p className="mt-1 text-xs text-red-600">{errors.name.message}</p>
            )}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Kampanya Kodu
            </label>
            <input
              className="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5"
              placeholder="Örn: YAZ25"
              {...register('code')}
            />
            <p className="mt-1 text-xs text-gray-500">
              Kodsuz kampanya oluşturmak için boş bırakın
            </p>
          </div>

          <div className="md:col-span-2">
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Açıklama
            </label>
            <textarea
              rows={3}
              className="block w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5"
              placeholder="Kampanya detaylarını yazın..."
              {...register('description')}
            />
          </div>
        </div>
      </Section>

      <Section
        title="Kampanya Kuralları"
        description="Kampanya tipini seç, gerekiyorsa indirim tutarını ve özel koşulları tanımla."
      >
        <div className="grid gap-4">
          <div className="grid gap-3 md:grid-cols-3">
            {typeOptions.map((option) => {
              const selected = type === option.value;
              return (
                <label
                  key={option.value}
                  className={`flex cursor-pointer items-start gap-3 rounded-2xl border p-4 transition ${
                    selected
                      ? 'border-primary bg-primary/5 text-primary'
                      : 'border-base-content/10 bg-base-100 hover:border-primary/40'
                  }`}
                >
                  <input
                    type="radio"
                    value={option.value}
                    {...register('type', { required: 'Kampanya tipi seçilmelidir.' })}
                    className="radio radio-primary mt-1"
                  />
                  <div>
                    <p className="text-sm font-semibold">{option.label}</p>
                    <p className="text-xs text-base-content/60">{option.description}</p>
                  </div>
                </label>
              );
            })}
          </div>

          {errors.type && (
            <span className="text-xs text-error">{errors.type.message?.toString()}</span>
          )}

          <div className="grid gap-4 md:grid-cols-3">
            {showDiscountField && (
              <label className="form-control md:col-span-1">
                <span className="label-text text-sm font-medium">İndirim Değeri</span>
                <input
                  type="number"
                  step="0.1"
                  max={type === 'percentage' ? 100 : undefined}
                  min={type === 'percentage' ? 0 : undefined}
                  className="input input-bordered input-lg border border-gray-300 bg-white shadow-lg rounded-lg p-2"
                  placeholder={type === 'percentage' ? '% oran' : 'TL tutar'}
                  {...register('discount_value', {
                    setValueAs: (value) =>
                      value === '' || value === null || value === undefined
                        ? undefined
                        : Number(value),
                    min: { value: 0, message: '0’dan küçük olamaz.' },
                    max: { value: 100, message: '%100’den büyük olamaz.' },
                  })}
                />
                {errors.discount_value && (
                  <span className="mt-1 text-xs text-error">{errors.discount_value.message}</span>
                )}
                <span className="mt-1 text-sm text-base-content/60">
                  Yüzdesel kampanyalarda % değer; sabit kampanyalarda TL olarak düşün. En fazla %100 olmalıdır.
                </span>
              </label>
            )}

            {showQuantityFields && (
              <>
              <div className="flex flex-rows gap-4 md:flex-cols">
                <label className="form-control md:col-span-1">
                  <span className="label-text text-sm font-medium">Alınacak Ürün Adedi</span>
                  <input
                    type="number"
                    className="input input-bordered input-lg border border-gray-300 bg-white shadow-lg rounded-lg p-2"
                    placeholder="Örn. 3"
                    {...register('buy_quantity', {
                      setValueAs: (value) =>
                        value === '' || value === null || value === undefined
                          ? undefined
                          : Number(value),
                      required: 'Alınacak adet zorunludur.',
                      min: { value: 1, message: 'En az 1 olmalıdır.' },
                    })}
                  />
                  {errors.buy_quantity && (
                    <span className="mt-1 text-xs text-error">{errors.buy_quantity.message}</span>
                  )}
                </label>

                <label className="form-control md:col-span-1">
                  <span className="label-text text-sm font-medium">Ödenecek Ürün Adedi</span>
                  <input
                    type="number"
                    className="input input-bordered input-lg border border-gray-300 bg-white shadow-lg rounded-lg p-2"
                    placeholder="Örn. 2"
                    {...register('pay_quantity', {
                      setValueAs: (value) =>
                        value === '' || value === null || value === undefined
                          ? undefined
                          : Number(value),
                      required: 'Ödenecek adet zorunludur.',
                      min: { value: 0, message: 'Negatif olamaz.' },
                    })}
                  />
                  {errors.pay_quantity && (
                    <span className="mt-1 text-xs text-error">{errors.pay_quantity.message}</span>
                  )}
                </label>
              </div>
              </>
            )}
          </div>
        </div>
      </Section>

      <Section
        title="Geçerlilik ve Limitler"
        description="Kampanyanın zaman aralığını ve varsa kullanım limiti ile minimum sepet tutarını belirt."
      >
        <div className="grid gap-4 md:grid-cols-2">
          <label className="form-control">
            <span className="label-text text-sm font-medium">Minimum Sepet Tutarı (TL)</span>
            <input
              type="number"
              step="0.01"
              className="input input-bordered input-lg border border-gray-300 bg-white shadow-lg rounded-lg p-2"
              placeholder="Opsiyonel"
              {...register('min_subtotal', {
                setValueAs: (value) =>
                  value === '' || value === null || value === undefined
                    ? undefined
                    : Number(value),
              })}
            />
          </label>

          <label className="form-control">
            <span className="label-text text-sm font-medium">Kullanım Limiti</span>
            <input
              type="number"
              className="input input-bordered input-lg border border-gray-300 bg-white shadow-lg rounded-lg p-2"
              placeholder="Sınırsız bırakmak için boş bırak"
              {...register('usage_limit', {
                setValueAs: (value) =>
                  value === '' || value === null || value === undefined
                    ? undefined
                    : Number(value),
              })}
            />
          </label>

          <label className="form-control">
            <span className="label-text text-sm font-medium">Başlangıç Tarihi</span>
            <input type="date" className="input input-bordered input-lg bg-white" {...register('starts_at')} />
          </label>

          <label className="form-control">
            <span className="label-text text-sm font-medium">Bitiş Tarihi</span>
            <input type="date" className="input input-bordered input-lg bg-white" {...register('ends_at')} />
          </label>

          <label className="flex items-center gap-3 rounded-2xl border border-base-content/10 bg-base-100 p-4 md:col-span-2">
            <input type="checkbox" className="toggle toggle-primary" {...register('is_active')} />
            <div>
              <p className="text-sm font-semibold text-base-content">Kampanya Aktif</p>
              <p className="text-xs text-base-content/60">
                Pasif kampanyalar müşterilere gösterilmez ancak kayıtlarda tutulur.
              </p>
            </div>
          </label>
        </div>
      </Section>

      <Section
        title="Ürün / Kategori Ataması"
        description="Backend’deki kurala uygun olarak en az bir ürün ID veya kategori ID gir. Virgülle ayrılmış değerler bekleniyor."
      >
        <div className="grid gap-6 md:grid-cols-2">
          <label className="form-control">
            <span className="label-text mb-2 text-sm font-medium">Ürün ID’leri</span>
            <input
              className="input input-bordered input-lg border border-gray-300 bg-white shadow-lg rounded-lg p-2"
              placeholder="Örn. 12, 45, 78"
              {...register('product_ids', {
                setValueAs: (raw) => {
                  if (typeof raw !== 'string') return raw;
                  const parts = raw
                    .split(',')
                    .map((val) => parseInt(val.trim(), 10))
                    .filter((num) => Number.isInteger(num));
                  return parts.length ? parts : null;
                },
              })}
            />
            {errors.product_ids && (
              <span className="mt-1 text-xs text-error">{errors.product_ids.message}</span>
            )}
            <span className="mt-1 text-sm text-base-content/60">
              Kampanya sadece belirli ürünlerde geçerliyse ürün ID’lerini virgülle ayırarak gir.
            </span>
          </label>

          <label className="form-control">
            <span className="label-text mb-2 text-sm font-medium">Kategori ID’leri</span>
            <input
              className="input input-bordered input-lg border border-gray-300 bg-white shadow-lg rounded-lg p-2"
              placeholder="Örn. 5, 9"
              {...register('category_ids', {
                setValueAs: (raw) => {
                  if (typeof raw !== 'string') return raw;
                  const parts = raw
                    .split(',')
                    .map((val) => parseInt(val.trim(), 10))
                    .filter((num) => Number.isInteger(num));
                  return parts.length ? parts : null;
                },
              })}
            />
            {errors.category_ids && (
              <span className="mt-1 text-xs text-error">{errors.category_ids.message}</span>
            )}
            <span className="ml-2 mt-1 text-sm text-base-content/60">
              Kategori seçersen, o kategoriye ait tüm ürünler kampanyaya dahil edilir.
            </span>
          </label>
        </div>
      </Section>

      <footer className="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
        <button
          type="button"
          className="btn btn-outline sm:w-auto  cursor-pointer"
          onClick={() => reset(preparedDefaults)}
          disabled={loading}
        >
          Temizle
        </button>
        <button type="submit" className="btn btn-primary sm:w-auto flex items-center justify-center p-3 w-10 h-10 rounded-xl text-white bg-black cursor-pointer" disabled={loading}>
          {loading ? 'Kaydediliyor...' : 'Kampanyayı Kaydet'}
        </button>
      </footer>
    </form>
  );
}
