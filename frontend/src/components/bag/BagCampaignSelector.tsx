"use client"

import { BagCampaign } from "@/types/bag"
import { useBagCampaignSelect, useBagCampaignUnselect } from "@/hooks/useBagQuery"
import { useMe } from "@/hooks/useAuthQuery"
import { toast } from "sonner"

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
    <div className="space-y-3 border border-color rounded-lg p-4">
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
        {campaigns.length === 0 ? (
          <div className="text-sm text-muted-foreground">Şu an kampanya yok.</div>
        ) : (
          campaigns.map((campaign) => {
            const isActive = activeCampaign?.id === campaign.id
            return (
              <div
                key={campaign.id}
                className={`border rounded-md p-3 ${isActive ? "border-green-500 bg-green-50" : "border-color"}`}
              >
                <div className="flex items-center justify-between">
                  <div>
                    <h5 className="font-medium">{campaign.name}</h5>
                    <p className="text-sm text-muted-foreground">
                      {campaign.description ?? "Açıklama belirtilmemiş"}
                    </p>
                  </div>
                  <button
                    className="text-sm px-3 py-2 border rounded-md"
                    onClick={() => handleSelect(campaign)}
                    disabled={selectMutation.isPending || isActive}
                  >
                    {isActive ? "Seçili" : "Seç"}
                  </button>
                </div>
              </div>
            )
          })
        )}
      </div>
    </div>
  )
}
