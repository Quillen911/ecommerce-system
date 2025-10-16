import { useEffect, useMemo } from 'react';
import {
  useCampaignShow,
  useCampaignStore,
  useCampaignUpdate,
} from '@/hooks/seller/useCampaignQuery';
import { CampaignCreatePayload } from '@/types/seller/campaign';
import CampaignForm, { CampaignFormValues } from '@/components/forms/seller/CampaignForm';

interface CampaignDrawerProps {
  campaignId: number | null;
  open: boolean;
  onClose: () => void;
}

const emptyValues: CampaignFormValues = {
  name: '',
  description: '',
  code: '',
  type: 'percentage',
  discount_value: null,
  buy_quantity: null,
  pay_quantity: null,
  min_subtotal: null,
  usage_limit: null,
  is_active: true,
  starts_at: '',
  ends_at: '',
  product_ids: null,
  category_ids: null,
};

export default function CampaignDrawer({ campaignId, open, onClose }: CampaignDrawerProps) {
  const { data, isLoading } = useCampaignShow(campaignId ?? undefined);
  const createMutation = useCampaignStore();
  const updateMutation = useCampaignUpdate();

  useEffect(() => {
    if (!open) return;
    document.body.classList.add('overflow-hidden');
    return () => document.body.classList.remove('overflow-hidden');
  }, [open]);

  const initialValues = useMemo<CampaignFormValues>(() => {
    if (!campaignId || !data) return emptyValues;

    return {
      name: data.name,
      description: data.description ?? '',
      code: data.code ?? '',
      type: data.type,
      discount_value: data.discount_value ?? null,
      buy_quantity: data.buy_quantity ?? null,
      pay_quantity: data.pay_quantity ?? null,
      min_subtotal: data.min_subtotal ?? null,
      usage_limit: data.usage_limit ?? null,
      is_active: data.is_active,
      starts_at: data.starts_at ?? '',
      ends_at: data.ends_at ?? '',
      product_ids:
        data.products
          ?.map((item) => item.product_id ?? item.id)
          ?.filter((id): id is number => Number.isInteger(id)) ?? null,
      category_ids:
        data.categories
          ?.map((item) => item.category_id ?? item.id)
          ?.filter((id): id is number => Number.isInteger(id)) ?? null,
    };
  }, [campaignId, data]);

  const handleSubmit = async (values: CampaignFormValues) => {
    const payload: CampaignCreatePayload = {
      ...values,
      discount_value: values.discount_value ?? null,
      buy_quantity: values.buy_quantity ?? null,
      pay_quantity: values.pay_quantity ?? null,
      min_subtotal: values.min_subtotal ?? null,
      usage_limit: values.usage_limit ?? null,
      starts_at: values.starts_at || null,
      ends_at: values.ends_at || null,
      product_ids: values.product_ids?.length ? values.product_ids : null,
      category_ids: values.category_ids?.length ? values.category_ids : null,
    };

    if (campaignId) {
      await updateMutation.mutateAsync({ id: campaignId, payload });
    } else {
      await createMutation.mutateAsync(payload);
    }

    onClose();
  };

  const loading =
    createMutation.isPending ||
    updateMutation.isPending ||
    (campaignId ? isLoading : false);

  return (
    <div
      className={`fixed inset-0 z-50 transition ${
        open ? 'pointer-events-auto opacity-100' : 'pointer-events-none opacity-0'
      }`}
    >
      <div
        className={`absolute inset-0 bg-base-content/30 backdrop-blur-sm transition-opacity ${
          open ? 'opacity-100' : 'opacity-0'
        }`}
        onClick={onClose}
      />

      <aside
        className={`absolute right-0 top-0 flex h-full w-full max-w-3xl flex-col bg-white shadow-[0_30px_60px_-25px_rgba(15,23,42,0.45)] transition-all duration-300 md:rounded-l-[32px] ${
          open ? 'translate-x-0' : 'translate-x-full'
        }`}
      >
        <header className="border-b border-base-content/10 px-8 py-6">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-xs uppercase tracking-[0.2em] text-base-content/50">
                Kampanya Yönetimi
              </p>
              <h2 className="mt-1 text-2xl font-semibold text-base-content">
                {campaignId ? 'Kampanya Güncelle' : 'Yeni Kampanya'}
              </h2>
            </div>
            <button className="btn btn-outline btn-sm" onClick={onClose}>
              Kapat
            </button>
          </div>
        </header>

        <div className="flex-1 overflow-y-auto px-8 py-8">
          <CampaignForm
            key={campaignId ?? 'new'}
            initialValues={initialValues}
            loading={loading}
            onSubmit={handleSubmit}
          />
        </div>
      </aside>
    </div>
  );
}
