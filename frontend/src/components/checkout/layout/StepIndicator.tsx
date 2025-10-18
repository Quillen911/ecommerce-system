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
    <ol className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-8 w-full max-w-3xl mx-auto">
      {steps.map((step, index) => {
        const isLast = index === steps.length - 1
        const isCompleted = step.status === "completed"
        const isCurrent = step.status === "current"

        return (
          <li key={step.id} className="flex items-center relative w-full sm:w-auto">
            {index > 0 && (
              <div
                className={`hidden sm:block flex-1 h-[2px] ${
                  isCompleted ? "bg-[var(--accent)]" : "bg-gray-300"
                }`}
              ></div>
            )}

            <div className="flex items-center gap-3 sm:gap-4">
              <div
                className={`flex items-center justify-center h-9 w-9 rounded-full border-2 text-sm font-semibold transition-all shrink-0 ${
                  isCompleted
                    ? "bg-[var(--accent)] border-[var(--accent)] text-white"
                    : isCurrent
                    ? "border-[var(--accent)] text-[var(--accent)] bg-white"
                    : "border-gray-300 text-gray-400 bg-white"
                }`}
              >
                {isCompleted ? "✓" : index + 1}
              </div>

              <span
                className={`text-sm sm:text-base font-medium ${
                  isCurrent
                    ? "text-[var(--accent)]"
                    : isCompleted
                    ? "text-gray-700"
                    : "text-gray-400"
                }`}
              >
                {step.label}
              </span>
            </div>

            {!isLast && (
              <div
                className={`absolute left-[calc(50%+18px)] sm:hidden top-10 h-[2px] w-[2px] rounded-full ${
                  isCompleted ? "bg-[var(--accent)]" : "bg-gray-300"
                }`}
              ></div>
            )}
          </li>
        )
      })}
    </ol>
  )
}
