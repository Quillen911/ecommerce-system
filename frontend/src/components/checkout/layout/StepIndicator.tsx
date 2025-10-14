"use client"

interface StepItem extends StepDescriptor {
  status: "completed" | "current" | "upcoming"
}

export interface StepDescriptor {
  id: string
  label: string
}

interface StepIndicatorProps {
  steps: StepItem[]
}

export function StepIndicator({ steps }: StepIndicatorProps) {
  return (
    <ol className="grid grid-cols-12 gap-5 text-sm font-medium md:grid-cols-12">
      {steps.map((step, index) => {
        const isLast = index === steps.length - 1

        const baseClasses =
          "flex items-center gap-3 rounded-lg border border-transparent bg-muted/40 px-4 py-3 transition"
        const stateClasses =
          step.status === "completed"
            ? "border-green-500 bg-green-50 text-green-700"
            : step.status === "current"
            ? "border-[var(--accent)] bg-[var(--accent)]/10 text-[var(--accent)]"
            : "border-muted bg-muted/20 text-muted-foreground"

        return (
          <li key={step.id} className={`${baseClasses} ${stateClasses} col-span-4`}>
            <span className="flex h-8 w-8 items-center justify-center rounded-full border border-current text-sm font-semibold">
              {index + 1}
            </span>
            <span>{step.label}</span>
            {!isLast && (
              <span className="sr-only">
                {step.status === "completed" ? "Tamamlandı" : step.status === "current" ? "Şu an" : "Sırada"}
              </span>
            )}
          </li>
        )
      })}
    </ol>
  )
}
