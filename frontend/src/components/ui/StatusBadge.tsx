'use client'

type StatusBadgeProps = {
  active?: boolean
}

export default function StatusBadge({ active }: StatusBadgeProps) {
  const color = active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600'
  const label = active ? 'Yayında' : 'Taslak'

  return (
    <span className={`inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ${color}`}>
      <span
        className={`mr-2 h-2 w-2 rounded-full ${active ? 'bg-emerald-500' : 'bg-gray-400'}`}
      />
      {label}
    </span>
  )
}
