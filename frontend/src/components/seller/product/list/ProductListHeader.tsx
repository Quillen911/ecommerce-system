'use client'

type ProductListHeaderProps = {
  total: number
  onCreate: () => void
  disabled?: boolean
}

export default function ProductListHeader({
  total,
  onCreate,
  disabled,
}: ProductListHeaderProps) {
  return (
    <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-8">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Ürün Yönetimi</h1>
        <p className="text-sm text-gray-500">
          Toplam {total} ürün listeleniyor.
        </p>
      </div>

      <button
        type="button"
        onClick={onCreate}
        disabled={disabled}
        className="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-black px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-70"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          className="h-4 w-4"
          viewBox="0 0 24 24"
          stroke="currentColor"
          fill="none"
          strokeWidth={1.5}
        >
          <path strokeLinecap="round" strokeLinejoin="round" d="M12 5v14M5 12h14" />
        </svg>
        Yeni Ürün
      </button>
    </div>
  )
}
