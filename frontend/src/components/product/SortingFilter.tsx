'use client'

import { useSearchParams, useRouter } from "next/navigation"
import { useState } from "react"
import { FaSortAmountDownAlt  } from "react-icons/fa"

export default function SortingFilter() {
  const searchParams = useSearchParams()
  const router = useRouter()
  const [open, setOpen] = useState(false)

  const updateSorting = (value: string) => {
    const params = new URLSearchParams(searchParams.toString())
    if (value) params.set("sorting", value)
    else params.delete("sorting")
    router.push(`?${params.toString()}`)
    setOpen(false)
  }

  return (
    <div className="relative">
      <button
        onClick={() => setOpen(!open)}
        className="flex items-center gap-2 cursor-pointer"
      >
        <FaSortAmountDownAlt className="w-8 h-8" />
        <span className="text-lg font-bold">{open ? "Sıralama Ölçütünü Gizle" : "Sıralama Ölçütünü Göster"}</span> 
      </button>

      {open && (
        <div className="absolute right-0 mt-2 w-48 bg-white shadow-md rounded z-50">
          <button
            onClick={() => updateSorting("price_asc")}
            className="block w-full text-left px-4 py-2 hover:bg-gray-100"
          >
            Fiyat: Artan
          </button>
          <button
            onClick={() => updateSorting("price_desc")}
            className="block w-full text-left px-4 py-2 hover:bg-gray-100"
          >
            Fiyat: Azalan
          </button>
          <button
            onClick={() => updateSorting("stock_quantity_desc")}
            className="block w-full text-left px-4 py-2 hover:bg-gray-100"
          >
            Stok: Çoktan Aza
          </button>
        </div>
      )}
    </div>
  )
}
