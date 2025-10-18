import { useEffect, useMemo } from 'react';
import {
  useCampaignShow,
  useCampaignStore,
  useCampaignUpdate,
} from '@/hooks/seller/useCampaignQuery';
import { CampaignCreatePayload } from '@/types/seller/campaign';
import CampaignForm, { CampaignFormValues } from '@/components/forms/seller/CampaignForm';
import { FiX } from 'react-icons/fi';

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
  per_user_limit: null,
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
      per_user_limit: data.per_user_limit ?? null,
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
    const trimmedCode = values.code?.trim();

    const productIds =
      Array.isArray(values.product_ids) && values.product_ids.length
        ? values.product_ids
        : undefined;

    const categoryIds =
      Array.isArray(values.category_ids) && values.category_ids.length
        ? values.category_ids
        : undefined;

    const payload: CampaignCreatePayload = {
      ...values,
      code: trimmedCode ? trimmedCode : undefined,
      discount_value: values.discount_value ?? undefined,
      buy_quantity: values.buy_quantity ?? undefined,
      pay_quantity: values.pay_quantity ?? undefined,
      min_subtotal: values.min_subtotal ?? undefined,
      usage_limit: values.usage_limit ?? undefined,
      per_user_limit: values.per_user_limit ?? undefined,
      starts_at: values.starts_at || undefined,
      ends_at: values.ends_at || undefined,
      product_ids: productIds,
      category_ids: categoryIds,
    };

    if (campaignId && data?.code && payload.code === data.code) {
      delete payload.code;
    }

    try {
      if (campaignId) {
        await updateMutation.mutateAsync({ id: campaignId, payload });
      } else {
        await createMutation.mutateAsync(payload);
      }
      onClose();
    } catch (error: any) {
      console.error('Campaign update failed:', error?.response?.data ?? error);
      throw error;
    }
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
        className={`absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity ${
          open ? 'opacity-100' : 'opacity-0'
        }`}
        onClick={onClose}
      />

      <aside
        className={`absolute right-0 top-0 flex h-full w-full max-w-2xl flex-col bg-white shadow-2xl transition-all duration-300 ease-in-out ${
          open ? 'translate-x-0' : 'translate-x-full'
        }`}
      >
        <header className="flex items-center justify-between border-b border-gray-200 px-6 py-4">
          <div>
            <h2 className="text-lg font-semibold text-gray-900">
              {campaignId ? 'Kampanya Düzenle' : 'Yeni Kampanya Oluştur'}
            </h2>
          </div>
          <button
            onClick={onClose}
            className="rounded-full p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
          >
            <FiX className="h-5 w-5" />
          </button>
        </header>

        <div className="flex-1 overflow-y-auto p-6">
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