import { useEffect, useMemo, useState } from 'react';
import { Campaign } from '@/types/seller/campaign';
import { useCampaignDestroy } from '@/hooks/seller/useCampaignQuery';

interface CampaignListProps {
  campaigns: Campaign[];
  onEdit: (id: number) => void;
}

interface Countdown {
  label: string;
  expired: boolean;
}

const gradients = [
  'from-[#6366F1] via-[#8B5CF6] to-[#EC4899]',
  'from-[#0EA5E9] via-[#22D3EE] to-[#A855F7]',
  'from-[#F97316] via-[#FACC15] to-[#4ADE80]',
];

function useCountdown(target?: string | null): Countdown {
  const [label, setLabel] = useState<string>('—');
  const [expired, setExpired] = useState<boolean>(false);

  useEffect(() => {
    if (!target) {
      setLabel('Tarih belirtilmemiş');
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
        setLabel('Süresi doldu');
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
  index,
  onEdit,
  onDelete,
  deleting,
}: {
  campaign: Campaign;
  index: number;
  onEdit: (id: number) => void;
  onDelete: (id: number) => void;
  deleting: boolean;
}) {
  const countdown = useCountdown(campaign.ends_at);

  const durationLabel = useMemo(() => {
    if (!campaign.starts_at && !campaign.ends_at) {
      return 'Başlangıç/Bitiş tarihi belirtilmemiş';
    }

    const start = campaign.starts_at
      ? new Date(campaign.starts_at).toLocaleString('tr-TR')
      : '—';
    const end = campaign.ends_at ? new Date(campaign.ends_at).toLocaleString('tr-TR') : '—';

    return `${start} ↦ ${end}`;
  }, [campaign.starts_at, campaign.ends_at]);

  return (
    <article className="flex flex-col rounded-3xl border border-base-content/12 bg-white p-6 shadow-lg shadow-base-content/5 transition hover:-translate-y-[2px] hover:shadow-xl">
      <header className="flex items-start gap-3">
        <div className="flex-1">
          <div className="flex items-start justify-between gap-2">
            <div>
              <h3 className="mt-2 text-lg font-semibold text-base-content">{campaign.name}</h3>
              <p className="mt-1 text-xs text-base-content/60">
                Kod: {campaign.code ?? 'Tanımlı değil'}
              </p>
            </div>
            <span
              className={`inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ${
                campaign.is_active ? 'bg-success/10 text-success' : 'bg-base-300 text-base-content/70'
              }`}
            >
              <span
                className={`h-3 w-3 rounded-full ${
                  campaign.is_active ? 'bg-success' : 'bg-base-content/40'
                }`}
              />
              {campaign.is_active ? 'Aktif' : 'Pasif'}
            </span>
          </div>
        </div>
      </header>

      {campaign.description && (
        <p className="mt-4 rounded-2xl bg-primary/5 p-3 text-sm text-base-content/70">
          {campaign.description}
        </p>
      )}

      <section className="mt-5 grid grid-cols-2 gap-4 text-sm text-base-content/70 md:grid-cols-3">
        <div>
          <p className="text-xs uppercase tracking-wider text-base-content/50">Tip</p>
          <p className="mt-1 font-medium capitalize">{campaign.type.replace(/_/g, ' ')}</p>
        </div>
        <div>
          <p className="text-xs uppercase tracking-wider text-base-content/50">İndirim / Değer</p>
          <p className="mt-1 font-medium">
            {campaign.type === 'x_buy_y_pay'
              ? `${campaign.buy_quantity ?? '-'} al ${campaign.pay_quantity ?? '-'} öde`
              : campaign.discount_value ?? '—'}
          </p>
        </div>
        <div>
          <p className="text-xs uppercase tracking-wider text-base-content/50">Kullanım</p>
          <p className="mt-1 font-medium">
            {campaign.usage_count} / {campaign.usage_limit ?? '∞'}
          </p>
        </div>
        <div className="md:col-span-2">
          <p className="text-xs uppercase tracking-wider text-base-content/50">Zaman Aralığı</p>
          <p className="mt-1 font-medium">{durationLabel}</p>
        </div>
        <div className="md:col-span-1">
          <p className="text-xs uppercase tracking-wider text-base-content/50">Kalan Süre</p>
          <p
            className={`mt-1 inline-flex items-center gap-2 rounded-full px-2 py-1 text-xs font-semibold ${
              countdown.expired ? 'bg-error/10 text-error' : 'bg-primary/10 text-primary'
            }`}
          >
            ⏳ {countdown.label}
          </p>
        </div>
      </section>

      <footer className="mt-auto flex gap-3 pt-6">
        <button className="btn btn-primary btn-sm flex-1 shadow-sm cursor-pointer" onClick={() => onEdit(campaign.id)}>
          ✏️ Düzenle
        </button>
        <button
          className="btn btn-error btn-sm flex-1 shadow-sm cursor-pointer"
          onClick={() => onDelete(campaign.id)}
          disabled={deleting}
        >
          🗑️ Sil
        </button>
      </footer>
    </article>
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
      <div className="rounded-3xl border border-dashed border-base-content/20 bg-white p-12 text-center shadow-lg shadow-base-content/5">
        <div className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1 text-sm font-semibold text-primary">
          🎯 Kampanya Yönetimi
        </div>
        <h3 className="mt-4 text-xl font-semibold text-base-content">Henüz kampanya yok</h3>
        <p className="mt-2 text-sm text-base-content/60">
          Sağ üstteki “Yeni Kampanya” butonuyla ilk kampanyanı oluşturabilirsin.
        </p>
      </div>
    );
  }

  return (
    <div className="grid gap-5 lg:grid-cols-2 xl:grid-cols-3">
      {campaigns.map((campaign, index) => (
        <CampaignCard
          key={campaign.id}
          campaign={campaign}
          index={index}
          onEdit={onEdit}
          onDelete={handleDelete}
          deleting={isPending}
        />
      ))}
    </div>
  );
}
