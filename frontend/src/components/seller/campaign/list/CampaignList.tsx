import { useEffect, useMemo, useState } from 'react';
import { Campaign } from '@/types/seller/campaign';
import { useCampaignDestroy } from '@/hooks/seller/useCampaignQuery';
import { FiEdit2, FiTrash2, FiClock, FiTag, FiPercent, FiShoppingCart, FiCalendar, FiCheckCircle, FiXCircle, FiUser } from 'react-icons/fi';

interface CampaignListProps {
  campaigns: Campaign[];
  onEdit: (id: number) => void;
}

interface Countdown {
  label: string;
  expired: boolean;
}

function useCountdown(target?: string | null): Countdown {
  const [label, setLabel] = useState<string>('—');
  const [expired, setExpired] = useState<boolean>(false);

  useEffect(() => {
    if (!target) {
      setLabel('Süresiz');
      setExpired(false);
      return;
    }

    const targetTime = new Date(target).getTime();
    if (Number.isNaN(targetTime)) {
      setLabel('Geçersiz tarih');
      setExpired(false);
      return;
    }

    const tick = () => {
      const now = Date.now();
      const diff = targetTime - now;

      if (diff <= 0) {
        setLabel('Süre Doldu');
        setExpired(true);
        return;
      }

      const seconds = Math.floor(diff / 1000) % 60;
      const minutes = Math.floor(diff / (1000 * 60)) % 60;
      const hours = Math.floor(diff / (1000 * 60 * 60)) % 24;
      const days = Math.floor(diff / (1000 * 60 * 60 * 24));

      const parts = [
        days ? `${days}g` : null,
        hours || days ? `${hours}sa` : null,
        minutes || hours || days ? `${minutes}dk` : null,
        `${seconds}sn`,
      ].filter(Boolean);

      setLabel(parts.join(' '));
      setExpired(false);
    };

    tick();
    const timer = window.setInterval(tick, 1000);
    return () => window.clearInterval(timer);
  }, [target]);

  return { label, expired };
}

function CampaignCard({
  campaign,
  onEdit,
  onDelete,
  deleting,
}: {
  campaign: Campaign;
  onEdit: (id: number) => void;
  onDelete: (id: number) => void;
  deleting: boolean;
}) {
  const countdown = useCountdown(campaign.ends_at);

  const durationLabel = useMemo(() => {
    if (!campaign.starts_at && !campaign.ends_at) {
      return 'Süre sınırı yok';
    }

    const formatDate = (dateString?: string) => {
      if (!dateString) return '—';
      const date = new Date(dateString);
      return date.toLocaleDateString('tr-TR', {
        day: '2-digit',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit'
      });
    };

    return `${formatDate(campaign.starts_at ?? '')} - ${formatDate(campaign.ends_at ?? '')}`;
  }, [campaign.starts_at, campaign.ends_at]);

  const getCampaignTypeLabel = () => {
    switch (campaign.type) {
      case 'percentage':
        return 'Yüzde İndirim';
      case 'fixed':
        return 'Sabit İndirim';
      case 'x_buy_y_pay':
        return `${campaign.buy_quantity} Al ${campaign.pay_quantity} Öde`;
      default:
        return campaign.type;
    }
  };

  const getCampaignValue = () => {
    if (campaign.type === 'x_buy_y_pay') {
      return (
        <span className="text-lg font-bold text-primary">
          {campaign.buy_quantity} Al {campaign.pay_quantity} Öde
        </span>
      );
    }
    return (
      <span className="text-lg font-bold text-primary">
        {campaign.type === 'percentage' ? '%' : '₺'}{campaign.discount_value}
      </span>
    );
  };

  return (
    <div className="relative overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition-all hover:shadow-md">
      {/* Header with status */}
      <div className="border-b border-gray-100 p-5 pb-4">
        <div className="flex items-start justify-between">
          <div>
            <div className="flex items-center gap-2">
              <h3 className="text-lg font-semibold text-gray-900">{campaign.name}</h3>
              <span
                className={`inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium ${
                  campaign.is_active
                    ? 'bg-green-100 text-green-800'
                    : 'bg-gray-100 text-gray-800'
                }`}
              >
                {campaign.is_active ? (
                  <FiCheckCircle className="h-3 w-3" />
                ) : (
                  <FiXCircle className="h-3 w-3" />
                )}
                {campaign.is_active ? 'Aktif' : 'Pasif'}
              </span>
            </div>
            {campaign.code && (
              <p className="mt-1 text-sm text-gray-500">Kod: {campaign.code}</p>
            )}
          </div>
          <div className="flex items-center gap-2">
            <button
              onClick={() => onEdit(campaign.id)}
              className="rounded-lg p-2 text-gray-500 hover:bg-gray-50 hover:text-primary cursor-pointer"
              title="Düzenle"
            >
              <FiEdit2 className="h-4 w-4" />
            </button>
            <button
              onClick={() => onDelete(campaign.id)}
              disabled={deleting}
              className="rounded-lg p-2 text-gray-500 hover:bg-red-50 hover:text-red-600 cursor-pointer"
              title="Sil"
            >
              <FiTrash2 className="h-4 w-4" />
            </button>
          </div>
        </div>
      </div>

      {/* Campaign Description */}
      {campaign.description && (
        <div className="border-b border-gray-100 bg-gray-50 p-4 text-sm text-gray-600">
          {campaign.description}
        </div>
      )}

      {/* Campaign Details */}
      <div className="grid grid-cols-2 gap-7 p-5 sm:grid-cols-3">
        <div className="space-y-1">
          <div className="flex items-center text-sm text-gray-500">
            <FiTag className="mr-2 h-4 w-4 text-gray-400" />
            <span>Tip</span>
          </div>
          <div className="text-sm font-medium text-gray-900">
            {getCampaignTypeLabel()}
          </div>
        </div>

        <div className="space-y-1">
          <div className="flex items-center text-sm text-gray-500">
            <FiPercent className="mr-2 h-4 w-4 text-gray-400" />
            <span>İndirim</span>
          </div>
          {getCampaignValue()}
        </div>

        <div className="space-y-1">
          <div className="flex items-center text-sm text-gray-500">
            <FiShoppingCart className="mr-2 h-4 w-4 text-gray-400" />
            <span>Kullanım</span>
          </div>
          <div className="text-sm font-medium text-gray-900">
            {campaign.usage_count} / {campaign.usage_limit ?? '∞'}
          </div>
        </div>

        <div className="space-y-1">
          <div className="flex items-center text-sm text-gray-500">
            <FiUser className="mr-2 h-4 w-4 text-gray-400" />
            <span>Kullanıcı Limiti</span>
          </div>
          <div className="text-sm font-medium text-gray-900">
            {campaign.per_user_limit ?? '∞'}
          </div>
        </div>

        <div className="space-y-1 sm:col-span-2">
          <div className="flex items-center text-sm text-gray-500">
            <FiCalendar className="mr-2 h-4 w-4 text-gray-400" />
            <span>Geçerlilik</span>
          </div>
          <div className="text-sm font-medium text-gray-900">
            {durationLabel}
          </div>
        </div>

        <div className="space-y-1">
          <div className="flex items-center text-sm text-gray-500">
            <FiClock className="mr-2 h-4 w-4 text-gray-400" />
            <span>Kalan Süre</span>
          </div>
          <div
            className={`inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ${
              countdown.expired
                ? 'bg-red-100 text-red-800'
                : 'bg-blue-100 text-blue-800'
            }`}
          >
            {countdown.label}
          </div>
        </div>
      </div>
    </div>
  );
}

export default function CampaignList({ campaigns, onEdit }: CampaignListProps) {
  const { mutateAsync, isPending } = useCampaignDestroy();

  const handleDelete = async (id: number) => {
    const confirmed = window.confirm(
      'Kampanyayı silmek istediğinize emin misiniz?\nBu işlem geri alınamaz.'
    );
    if (!confirmed) return;
    await mutateAsync(id);
  };

  if (!campaigns.length) {
    return (
      <div className="flex min-h-[400px] flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 bg-white p-8 text-center">
        <div className="mb-4 rounded-full bg-blue-100 p-4">
          <FiTag className="h-8 w-8 text-blue-600" />
        </div>
        <h3 className="mb-2 text-lg font-medium text-gray-900">Henüz kampanya yok</h3>
        <p className="mb-6 max-w-md text-gray-500">
          "Yeni Kampanya" butonuna tıklayarak ilk kampanyanızı oluşturabilir ve satışlarınızı artırabilirsiniz.
        </p>
      </div>
    );
  }

  return (
    <div className="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
      {campaigns.map((campaign) => (
        <CampaignCard
          key={campaign.id}
          campaign={campaign}
          onEdit={onEdit}
          onDelete={handleDelete}
          deleting={isPending}
        />
      ))}
    </div>
  );
}