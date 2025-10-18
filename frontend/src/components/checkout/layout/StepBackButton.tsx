"use client"

import { useRouter } from "next/navigation"
import { ChevronLeftIcon } from "lucide-react"

interface StepBackButtonProps {
  fallbackHref: string
}

export function StepBackButton({ fallbackHref }: StepBackButtonProps) {
  const router = useRouter()

  const handleBack = () => {
    if (window.history.length > 1) {
      router.back()
    } else {
      router.push(fallbackHref)
    }
  }

  return (
    <button
      type="button"
      onClick={handleBack}
      className="flex items-center gap-1 px-2 py-1 sm:py-2 hover:text-[var(--accent-dark)] cursor-pointer transition-transform active:scale-95"
    >
      <ChevronLeftIcon className="text-lg font-medium text-[var(--accent)]" />
      <span className="text-lg font-medium text-[var(--accent)]">Geri</span>
    </button>
  )
}
