"use client"
import CategoryFilter from "@/components/product/CategoryFilter"
import ProductList from "@/components/product/ProductList"
import SortingFilter from "@/components/product/SortingFilter"

import { useState } from "react"
import { FaFilter } from "react-icons/fa"
import { motion } from "framer-motion"
    
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
      <div className="col-span-12 flex justify-end gap-5">
        <div className="flex items-center gap-2"> 
          <SortingFilter />
        </div>
        <button onClick={handleOpen} type="button" className="flex items-center gap-2">
          <FaFilter className="w-8 h-8 cursor-pointer" />
          <span className="text-lg font-bold cursor-pointer">
            {isOpen ? "Filtrelemeyi Gizle" : "Filtrelemeyi Göster"}
          </span>
        </button>
      </div>

      {/* Sol Sidebar */}
      {isOpen && (
        <motion.aside 
          initial={{ opacity: 0, y: 0, x: -150}}
          animate={{ opacity: 1, y: 0, x: 0 }}
          transition={{ duration: 0.3 }}
          className="col-span-12 md:col-span-3 lg:col-span-2 xl:col-span-2 self-start"
        >
          <div className="sticky top-24">
            <CategoryFilter isOpen={isOpen} handleOpen={handleOpen} handleClose={handleClose} />
          </div>
        </motion.aside>
      )}

      {/* Sağ içerik */}
      <motion.main
        layout
        initial={{ opacity: 0, y: 20, x: 20 }}
        animate={{ opacity: 1, y: 0, x: 0 }}
        transition={{ duration: 0.4 }}
        className={`self-start ${
          isOpen
            ? "col-span-12 md:col-span-9 lg:col-span-10 xl:col-span-10"
            : "col-span-12"
        }`}
      >
        <ProductList />
      </motion.main>
    </div>

  )
}
