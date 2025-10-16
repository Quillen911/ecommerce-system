'use client'

type EmptyStateProps = {
  title: string
  description?: string
  actionLabel?: string
  onAction?: () => void
  actionDisabled?: boolean
}

export default function EmptyState({
  title,
  description,
  actionLabel,
  onAction,
  actionDisabled,
}: EmptyStateProps) {
  return (
    <div className="flex min-h-[240px] flex-col items-center justify-center gap-4 rounded-2xl border border-dashed border-gray-200 bg-white p-12 text-center">
      <h2 className="text-xl font-semibold text-gray-900">{title}</h2>
      {description && (
        <p className="max-w-sm text-sm text-gray-500">{description}</p>
      )}
      {actionLabel && onAction && (
        <button
          type="button"
          onClick={onAction}
          disabled={actionDisabled}
          className="rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black disabled:cursor-not-allowed disabled:opacity-70"
        >
          {actionLabel}
        </button>
      )}
    </div>
  )
}
