// frontend/src/components/bag/BagCampaignSelector.tsx
"use client"

import { useEffect, useState } from "react"
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/outline"
import { Navigation } from "swiper/modules"
import { Swiper, SwiperSlide } from "swiper/react"
import { AnimatePresence, motion } from "framer-motion"

import { useMe } from "@/hooks/useAuthQuery"
import { useBagCampaignSelect, useBagCampaignUnselect } from "@/hooks/useBagQuery"
import type { BagCampaign } from "@/types/bag"

interface BagCampaignSelectorProps {
  activeCampaign: BagCampaign | null
  campaigns: BagCampaign[]
}

type ErrorBag = Record<string, string[]>
type FeedbackState =
  | { type: "success" | "error"; messages: string[] }
  | null

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

export function BagCampaignSelector({ activeCampaign, campaigns }: BagCampaignSelectorProps) {
  const { data: me } = useMe()
  const selectMutation = useBagCampaignSelect(me?.id)
  const unselectMutation = useBagCampaignUnselect(me?.id)

  const [feedback, setFeedback] = useState<FeedbackState>(null)

  useEffect(() => {
    if (!feedback) return
    const timer = setTimeout(() => setFeedback(null), 3500)
    return () => clearTimeout(timer)
  }, [feedback])

  const dismissFeedback = () => setFeedback(null)

  const handleSelect = (campaign: BagCampaign) => {
    dismissFeedback()
    selectMutation.reset()

    selectMutation.mutate(campaign.id, {
      onSuccess: () => {
        setFeedback({
          type: "success",
          messages: [`${campaign.name} kampanyası uygulandı.`],
        })
      },
      onError: (error) => {
        const parsed = parseApiError(
          (error as any)?.response?.data ?? (error as unknown as Record<string, unknown> | null),
        )
        setFeedback({
          type: "error",
          messages:
            parsed.general.length > 0
              ? parsed.general
              : ["Kampanya uygulanamadı. Lütfen tekrar deneyiniz."],
        })
      },
    })
  }

  const handleUnselect = () => {
    if (!activeCampaign) return

    dismissFeedback()
    unselectMutation.reset()

    unselectMutation.mutate(undefined, {
      onSuccess: () => {
        setFeedback({
          type: "success",
          messages: ["Kampanya kaldırıldı."],
        })
      },
      onError: (error) => {
        const parsed = parseApiError(
          (error as any)?.response?.data ?? (error as unknown as Record<string, unknown> | null),
        )
        setFeedback({
          type: "error",
          messages:
            parsed.general.length > 0
              ? parsed.general
              : ["Kampanya kaldırılamadı. Lütfen tekrar deneyiniz."],
        })
      },
    })
  }

  const isMutating = selectMutation.isPending || unselectMutation.isPending

  return (
    <div className="space-y-4">
      <AnimatePresence initial={false}>
        {feedback && (
          <motion.div
            key={feedback.type + feedback.messages.join("-")}
            initial={{ opacity: 0, y: -12 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -12 }}
            transition={{ duration: 0.25 }}
            className="rounded-lg border border-blue-400 bg-blue-50 p-3 text-sm text-black-700 shadow-sm"
          >
            <ul className="space-y-1">
              {feedback.messages.map((message, index) => (
                <li key={index}>{message}</li>
              ))}
            </ul>
          </motion.div>
        )}
      </AnimatePresence>

      <div className="flex items-center justify-between">
        <div>
          <p className="text-sm text-muted-foreground">Seçili kampanya</p>
          <p className="text-base font-semibold">
            {activeCampaign ? activeCampaign.name : "Kampanya seçilmedi"}
          </p>
        </div>

        {activeCampaign && (
          <button
            className="rounded-md border px-3 py-2 text-sm transition hover:border-[var(--accent)] hover:text-[var(--accent)] disabled:opacity-60"
            onClick={handleUnselect}
            disabled={isMutating}
            type="button"
          >
            Kampanyayı Kaldır
          </button>
        )}
      </div>

      <div className="space-y-2">
        <h4 className="text-sm font-semibold">Uygulanabilir kampanyalar</h4>

        <div className="flex items-center gap-3">
          <button
            className="bag-campaign-prev flex h-10 w-10 items-center justify-center rounded-full border border-color bg-card shadow transition hover:border-[var(--accent)] hover:text-[var(--accent)] disabled:opacity-60"
            type="button"
            disabled={isMutating}
          >
            <ChevronLeftIcon className="h-4 w-4" />
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
                <div className="text-sm text-muted-foreground">Şu an kampanya yok.</div>
              </SwiperSlide>
            ) : (
              campaigns.map((campaign) => {
                const isActive = activeCampaign?.id === campaign.id

                return (
                  <SwiperSlide key={campaign.id}>
                    <div
                      className={`rounded-lg border px-4 py-3 transition ${
                        isActive ? "border-emerald-500 bg-emerald-50" : "border-color bg-card"
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
                          disabled={isMutating || isActive}
                          type="button"
                        >
                          {isActive ? "Seçili" : isMutating ? "Seçiliyor..." : "Seç"}
                        </button>
                      </div>
                    </div>
                  </SwiperSlide>
                )
              })
            )}
          </Swiper>

          <button
            className="bag-campaign-next flex h-10 w-10 items-center justify-center rounded-full border border-color bg-card shadow transition hover:border-[var(--accent)] hover:text-[var(--accent)] disabled:opacity-60"
            type="button"
            disabled={isMutating}
          >
            <ChevronRightIcon className="h-4 w-4" />
          </button>
        </div>
      </div>
    </div>
  )
}
