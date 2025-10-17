"use client"

import { BagCampaign } from "@/types/bag"
import { useBagCampaignSelect, useBagCampaignUnselect } from "@/hooks/useBagQuery"
import { useMe } from "@/hooks/useAuthQuery"
import { toast } from "sonner"
import { Swiper, SwiperSlide } from "swiper/react"
import { Navigation } from 'swiper/modules'
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/outline"
import { useMemo, useState } from "react"
import { AnimatePresence, motion } from "framer-motion"

interface BagCampaignSelectorProps {
  activeCampaign: BagCampaign | null
  campaigns: BagCampaign[]
}
type ErrorBag = Record<string, string[]>
const stripCombinedSuffix = (value: string) =>
  value.replace(/\s*\(and\s+\d+\s+more\s+errors?\)\s*$/i, "")

const normalizeMessages = (value: unknown): string[] => {
  if (!value) return []
  const list = Array.isArray(value) ? value : [value]
  return list
    .map((item) => (typeof item === "string" ? stripCombinedSuffix(item.trim()) : ""))
    .filter(Boolean)
}

const parseApiError = (payload: unknown): { general: string[]; fields: ErrorBag } => {
  if (!payload || typeof payload !== "object") {
    return { general: [], fields: {} }
  }

  const general = new Set<string>()
  const fields: ErrorBag = {}
  const data = payload as Record<string, unknown>

  if (typeof data.error === "string" && data.error.trim()) {
    general.add(stripCombinedSuffix(data.error.trim()))
  }
  if (typeof data.message === "string" && data.message.trim()) {
    general.add(stripCombinedSuffix(data.message.trim()))
  }

  if (data.errors && typeof data.errors === "object") {
    Object.entries(data.errors as Record<string, unknown>).forEach(([key, value]) => {
      const messages = normalizeMessages(value)
      if (messages.length) {
        fields[key] = messages
      }
    })
  }

  return {
    general: Array.from(general),
    fields,
  }
}

const mergeErrorBags = (primary: ErrorBag, secondary: ErrorBag): ErrorBag => {
  const merged: ErrorBag = {}

  const append = (key: string, messages: string[]) => {
    if (!messages || messages.length === 0) return
    if (!merged[key]) {
      merged[key] = []
    }
    messages.forEach((message) => {
      if (message && !merged[key].includes(message)) {
        merged[key].push(message)
      }
    })
  }

  Object.entries(primary).forEach(([key, messages]) => append(key, messages))
  Object.entries(secondary).forEach(([key, messages]) => append(key, messages))

  return merged
}
export function BagCampaignSelector({ activeCampaign, campaigns }: BagCampaignSelectorProps) {
  const { data: me } = useMe()
  const selectMutation = useBagCampaignSelect(me?.id)
  const unselectMutation = useBagCampaignUnselect(me?.id)
  const [manualGeneralErrors, setManualGeneralErrors] = useState<string[]>([])

  const activeMutationError = selectMutation.error
  const activePayload = (activeMutationError as any)?.response?.data ?? activeMutationError
  const parsedMutationError = useMemo(() => parseApiError(activePayload), [activePayload])

  const combinedGeneralErrors = useMemo(() => {
    const generalBag = new Set<string>([
      ...parsedMutationError.general,
      ...manualGeneralErrors,
    ])
    return Array.from(generalBag)
  }, [parsedMutationError.general, manualGeneralErrors])

  const removeManualGeneralErrors = () => {
    setManualGeneralErrors([])
  }

  const handleSelect = (campaign: BagCampaign) => {
    selectMutation.mutate(campaign.id, {
      onSuccess: () => {
        toast.success(`${campaign.name} kampanyası uygulandı.`)
        removeManualGeneralErrors()
      },
      onError: () => {
        setManualGeneralErrors(parsedMutationError.general)
      },
    })
  }

  const handleUnselect = () => {
    if (!activeCampaign) return
    unselectMutation.mutate(undefined, {
      onSuccess: () => {
        toast.success("Kampanya kaldırıldı.")
      },
      onError: () => {
        setManualGeneralErrors(parsedMutationError.general)
      },
    })
  }

  return (
    <div className="space-y-3">
      <div className="flex items-center justify-between">
        <div>
          <p className="text-sm text-muted-foreground">Seçili kampanya</p>
          <AnimatePresence initial={false}>
                  {combinedGeneralErrors.length > 0 && (
                    <motion.div
                      key="login-errors"
                      initial={{ opacity: 0, y: -12 }}
                      animate={{ opacity: 1, y: 0 }}
                      exit={{ opacity: 0, y: -12 }}
                      transition={{ duration: 0.4 }}
                      className="mb-4 rounded-lg border border-blue-400 bg-blue-50 p-3 text-sm text-black-700"
                    >
                      <ul className="space-y-1">
                        {combinedGeneralErrors.map((message, index) => (
                          <li key={index}>{message}</li>
                        ))}
                      </ul>
                    </motion.div>
                  )}
                </AnimatePresence>
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
                          {selectMutation.isPending ? "Seçiliyor..." : isActive ? "Seçili" : "Seç"}
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
