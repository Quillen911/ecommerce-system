"use client"
import CategoryFilter from "@/components/product/CategoryFilter"
import ProductList from "@/components/product/ProductList"
import { useState } from "react"
  import { FaFilter } from "react-icons/fa"
    
export default function CategoryPage() {

  const [isOpen, setIsOpen] = useState(true)

  const handleOpen = () => {
    if(isOpen) {
      handleClose()
    } else {
      setIsOpen(true)
    }
  }
  const handleClose = () => {
    setIsOpen(false)
  }

  return (
    <div className="min-h-screen grid grid-cols-12 gap-6 bg-[var(--bg)] p-8">
  {/* Filtre butonu */}
  <div className="col-span-12 flex justify-end">
    <button onClick={handleOpen} type="button" className="flex items-center gap-2">
      <FaFilter className="w-8 h-8 cursor-pointer" />
      <span className="text-lg font-bold cursor-pointer">Filtrelemeyi Gizle</span>
    </button>
  </div>

  {/* Sol Sidebar */}
  <aside className="col-span-12 md:col-span-3 lg:col-span-2 xl:col-span-2 self-start">
    <div className="sticky top-24">
      <CategoryFilter isOpen={isOpen} handleOpen={handleOpen} handleClose={handleClose} />
    </div>
  </aside>

  {/* Sağ içerik */}
  <main className="col-span-12 md:col-span-9 lg:col-span-10 xl:col-span-10 self-start">
    <ProductList />
  </main>
</div>

  )
}
