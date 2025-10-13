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
    <button className="flex items-center hover:text-[var(--accent-dark)] cursor-pointer" 
      type="button" 
      onClick={handleBack}
    >
      <ChevronLeftIcon
        className="text-lg font-medium text-[var(--accent)]"
      />
      <span className="text-lg font-medium text-[var(--accent)]">Geri</span>
    </button>
  )
}
