'use client';

import { useState } from 'react';
import { useCampaignIndex } from '@/hooks/seller/useCampaignQuery';
import CampaignList from '@/components/seller/campaign/list/CampaignList';
import CampaignDrawer from '@/components/seller/campaign/CampaignDrawer';

export default function CampaignsPage() {
  const { data, isLoading, isError, refetch, isFetching } = useCampaignIndex();
  const [selectedCampaignId, setSelectedCampaignId] = useState<number | null>(null);
  const [isDrawerOpen, setDrawerOpen] = useState(false);

  const handleNew = () => {
    setSelectedCampaignId(null);
    setDrawerOpen(true);
  };

  const handleEdit = (id: number) => {
    setSelectedCampaignId(id);
    setDrawerOpen(true);
  };

  const handleClose = () => {
    setDrawerOpen(false);
    setSelectedCampaignId(null);
  };

  return (
    <div className="space-y-6">
      <header className="flex flex-col gap-4 rounded-2xl border border-base-content/10 bg-base-100 p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-semibold text-base-content">Kampanyalar</h1>
        </div>
        <div className="flex items-center gap-3">
          <button className="btn btn-primary text-black bg-white hover:bg-black hover:text-white transition duration-300 w-full p-3 rounded-full cursor-pointer" onClick={handleNew}>
            Yeni Kampanya
          </button>
        </div>
      </header>

      {isLoading ? (
        <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
          {Array.from({ length: 3 }).map((_, index) => (
            <div key={index} className="h-48 animate-pulse rounded-2xl bg-base-200/60" />
          ))}
        </div>
      ) : isError || !data ? (
        <div className="rounded-2xl border border-error/20 bg-error/5 p-6 text-error">
          Kampanyalar yüklenirken bir sorun oluştu. Lütfen tekrar deneyin.
        </div>
      ) : (
        <CampaignList campaigns={data} onEdit={handleEdit} />
      )}

      <CampaignDrawer campaignId={selectedCampaignId} open={isDrawerOpen} onClose={handleClose} />
    </div>
  );
}
