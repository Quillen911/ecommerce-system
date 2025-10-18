'use client'

import { useSearchParams, useRouter } from "next/navigation"
import { useState, useRef, useEffect } from "react"
import { FaSortAmountDownAlt } from "react-icons/fa"

export default function SortingFilter() {
  const searchParams = useSearchParams()
  const router = useRouter()
  const [open, setOpen] = useState(false)
  const dropdownRef = useRef<HTMLDivElement>(null)

  const updateSorting = (value: string) => {
    const params = new URLSearchParams(searchParams.toString())
    if (value) params.set("sorting", value)
    else params.delete("sorting")
    router.push(`?${params.toString()}`)
    setOpen(false)
  }

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setOpen(false)
      }
    }
    document.addEventListener("mousedown", handleClickOutside)
    return () => document.removeEventListener("mousedown", handleClickOutside)
  }, [])

  return (
    <div className="relative inline-block" ref={dropdownRef}>
      <button
        onClick={() => setOpen(!open)}
        className="flex items-center gap-2 text-sm sm:text-base px-3 py-2 rounded-md border border-gray-200 hover:bg-gray-100 transition-colors duration-150"
      >
        <FaSortAmountDownAlt className="w-5 h-5 sm:w-6 sm:h-6 text-gray-700" />
        <span className="font-semibold text-gray-800">
          {open ? "Sıralama Ölçütünü Gizle" : "Sıralama Ölçütü"}
        </span>
      </button>

      {open && (
        <div className="absolute right-0 mt-2 w-44 sm:w-52 bg-white shadow-lg rounded-md border border-gray-200 z-50">
          <button
            onClick={() => updateSorting("price_asc")}
            className="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
          >
            Fiyat: Artan
          </button>
          <button
            onClick={() => updateSorting("price_desc")}
            className="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
          >
            Fiyat: Azalan
          </button>
          <button
            onClick={() => updateSorting("stock_quantity_desc")}
            className="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
          >
            Stok: Çoktan Aza
          </button>
        </div>
      )}
    </div>
  )
}
