"use client"

export interface DeliveryMethod {
  id: string
  label: string
}

interface DeliveryMethodCardProps {
  method: DeliveryMethod
  isSelected: boolean
  onSelect: (id: string) => void
}


const label = "Standart"

export function DeliveryMethodCard({ method, isSelected, onSelect }: DeliveryMethodCardProps) {
  
  return (
    <button
      type="button"
      onClick={() => onSelect(method.id)}
      className={`flex h-full flex-col justify-between rounded-lg border px-4 py-3 text-left transition hover:border-[var(--accent)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--accent)] ${
        isSelected ? "border-[var(--accent)] bg-[var(--accent)]/10" : "border-color bg-card"
      }`}
    >
      <div className="flex items-start justify-between gap-3">
        <div>
          <p className="text-sm font-semibold">{label}</p>
        </div>
        <span
          className={`inline-flex h-5 w-5 items-center justify-center rounded-full border ${
            isSelected ? "border-[var(--accent)] bg-[var(--accent)] text-white" : "border-color bg-transparent"
          }`}
        >
          {isSelected && "✓"}
        </span>
      </div>
    </button>
  )
}
