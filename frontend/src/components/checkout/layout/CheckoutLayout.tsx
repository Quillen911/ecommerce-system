"use client"

import { ReactNode, useMemo } from "react"
import { StepIndicator } from "./StepIndicator"
import { OrderSummary } from "../review/OrderSummary"
import type { Bag } from "@/types/checkout"

type CheckoutStep = "shipping" | "payment" | "success"

interface CheckoutLayoutProps {
  children: ReactNode
  currentStep: CheckoutStep
  bag?: Bag | null
  showSummary?: boolean
}

const STEP_META: Array<{ id: CheckoutStep; label: string }> = [
  { id: "shipping", label: "Teslimat" },
  { id: "payment", label: "Ödeme" },
  { id: "success", label: "Tamamlandı" },
]

export function CheckoutLayout({
  children,
  currentStep,
  bag,
  showSummary = true,
}: CheckoutLayoutProps) {
  const stepsWithState = useMemo(() => {
    let passedCurrent = false

    return STEP_META.map((step) => {
      if (step.id === currentStep) {
        passedCurrent = true
        return { ...step, status: "current" as const }
      }
      if (!passedCurrent) {
        return { ...step, status: "completed" as const }
      }
      return { ...step, status: "upcoming" as const }
    })
  }, [currentStep])

  return (
    <div className="min-h-screen bg-[var(--bg)]">
      <div className="mx-auto w-full max-w-6xl px-4 sm:px-6 md:px-8 py-6 sm:py-8">
        <header className="mb-6 sm:mb-8 text-center sm:text-left">
          <h1 className="text-xl sm:text-2xl font-bold">Siparişi Tamamla</h1>
          <p className="text-xs sm:text-sm text-muted-foreground">
            Adımları sırayla takip ederek siparişinizi tamamlayın.
          </p>
        </header>

        <div className="overflow-x-auto sm:overflow-visible">
          <StepIndicator steps={stepsWithState} />
        </div>

        <div className="mt-6 sm:mt-8 grid grid-cols-1 gap-6 sm:gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
          <section className="surface rounded-lg border border-color p-4 sm:p-6 shadow-md">
            {children}
          </section>

          {showSummary && bag && (
            <aside className="h-fit space-y-4 lg:sticky lg:top-6">
              <OrderSummary bag={bag} />
            </aside>
          )}
        </div>
      </div>
    </div>
  )
}
