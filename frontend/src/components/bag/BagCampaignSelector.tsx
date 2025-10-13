"use client"

import { BagCampaign } from "@/types/bag"
import { useBagCampaignSelect, useBagCampaignUnselect } from "@/hooks/useBagQuery"
import { useMe } from "@/hooks/useAuthQuery"
import { toast } from "sonner"
import { Swiper, SwiperSlide } from "swiper/react"
import { Navigation } from 'swiper/modules'
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/outline"


interface BagCampaignSelectorProps {
  activeCampaign: BagCampaign | null
  campaigns: BagCampaign[]
}

export function BagCampaignSelector({ activeCampaign, campaigns }: BagCampaignSelectorProps) {
  const { data: me } = useMe()
  const selectMutation = useBagCampaignSelect(me?.id)
  const unselectMutation = useBagCampaignUnselect(me?.id)
  
  const handleSelect = (campaign: BagCampaign) => {
    const toastId = toast.loading("Kampanya uygulanıyor...")
    selectMutation.mutate(campaign.id, {
      onSuccess: () => {
        toast.success(`${campaign.name} kampanyası uygulandı.`, { id: toastId })
      },
      onError: () => {
        toast.error("Kampanya uygulanamadı.", { id: toastId })
      },
    })
  }

  const handleUnselect = () => {
    if (!activeCampaign) return
    const toastId = toast.loading("Kampanya kaldırılıyor...")
    unselectMutation.mutate(undefined, {
      onSuccess: () => {
        toast.success("Kampanya kaldırıldı.", { id: toastId })
      },
      onError: () => {
        toast.error("Kampanya kaldırılamadı.", { id: toastId })
      },
    })
  }

  return (
    <div className="space-y-3">
      <div className="flex items-center justify-between">
        <div>
          <p className="text-sm text-muted-foreground">Seçili kampanya</p>
          <p className="text-base font-semibold">
            {activeCampaign ? activeCampaign.name : "Kampanya seçilmedi"}
          </p>
        </div>
        {activeCampaign && (
          <button
            className="text-sm px-3 py-2 border rounded-md"
            onClick={handleUnselect}
            disabled={unselectMutation.isPending}
          >
            Kampanyayı Kaldır
          </button>
        )}
      </div>

      <div className="space-y-2">
        <h4 className="text-sm font-semibold">Uygulanabilir kampanyalar</h4>

        <div className="flex items-center gap-3">
          <button
            className="bag-campaign-prev flex h-10 w-10 items-center justify-center rounded-full border border-color bg-card shadow transition hover:border-[var(--accent)] hover:text-[var(--accent)]"
            type="button"
          >
            <ChevronLeftIcon className="h-4 w-4 cursor-pointer" />
          </button>

          <Swiper
            modules={[Navigation]}
            navigation={{
              prevEl: ".bag-campaign-prev",
              nextEl: ".bag-campaign-next",
            }}
            className="flex-1"
          >
            {campaigns.length === 0 ? (
              <SwiperSlide>
                <div className="text-sm text-muted-foreground">
                  Şu an kampanya yok.
                </div>
              </SwiperSlide>
            ) : (
              campaigns.map((campaign) => {
                const isActive = activeCampaign?.id === campaign.id
                return (
                  <SwiperSlide key={campaign.id}>
                    <div
                      className={`rounded-lg border px-4 py-3 transition ${
                        isActive
                          ? "border-emerald-500 bg-emerald-50"
                          : "border-color bg-card"
                      }`}
                    >
                      <div className="flex items-start justify-between gap-4">
                        <div>
                          <h5 className="text-sm font-semibold">{campaign.name}</h5>
                          <p className="text-xs text-muted-foreground">
                            {campaign.description ?? "Açıklama belirtilmemiş"}
                          </p>
                        </div>

                        <button
                          className="rounded-md border border-color px-3 py-2 text-xs font-medium transition hover:border-[var(--accent)] hover:text-[var(--accent)] disabled:opacity-60"
                          onClick={() => handleSelect(campaign)}
                          disabled={selectMutation.isPending || isActive}
                          type="button"
                        >
                          {isActive ? "Seçili" : "Seç"}
                        </button>
                      </div>
                    </div>
                  </SwiperSlide>
                )
              })
            )}
          </Swiper>

          <button
            className="bag-campaign-next flex h-10 w-10 items-center justify-center rounded-full border border-color bg-card shadow transition hover:border-[var(--accent)] hover:text-[var(--accent)]"
            type="button"
          >
            <ChevronRightIcon className="h-4 w-4 cursor-pointer" />
          </button>
        </div>
      </div>
    </div>
  )

}
