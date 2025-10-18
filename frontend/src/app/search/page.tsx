"use client"

import SearchProductList from "@/components/product/SearchProductList"
import CategoryFilter from "@/components/product/CategoryFilter"
import { motion, AnimatePresence } from "framer-motion"
import { useState } from "react"
import { FaFilter } from "react-icons/fa"
import SortingFilter from "@/components/product/SortingFilter"

export default function SearchPage() {
  const [isOpen, setIsOpen] = useState(true)

  const handleOpen = () => setIsOpen((prev) => !prev)
  const handleClose = () => setIsOpen(false)

  return (
    <div className="min-h-screen grid grid-cols-12 gap-4 sm:gap-6 bg-[var(--bg)] p-4 sm:p-6 md:p-8">
      {/* Filtre ve sıralama butonları */}
      <div className="col-span-12 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-5">
        <div className="flex items-center gap-3 w-full sm:w-auto">
          <SortingFilter />
        </div>

        <button
          onClick={handleOpen}
          type="button"
          className="flex items-center gap-2 text-base sm:text-lg font-semibold cursor-pointer"
        >
          <FaFilter className="w-6 h-6 sm:w-8 sm:h-8 cursor-pointer" />
          <span>{isOpen ? "Filtrelemeyi Gizle" : "Filtrelemeyi Göster"}</span>
        </button>
      </div>

      {/* Masaüstü filtre (sidebar) */}
      <AnimatePresence>
        {isOpen && (
          <motion.aside
            key="desktop-filter"
            initial={{ opacity: 0, x: -150 }}
            animate={{ opacity: 1, x: 0 }}
            exit={{ opacity: 0, x: -150 }}
            transition={{ duration: 0.3 }}
            className="hidden md:block col-span-3 lg:col-span-2 self-start mt-4 md:mt-0"
          >
            <div className="sticky top-24">
              <CategoryFilter
                isOpen={isOpen}
                handleOpen={handleOpen}
                handleClose={handleClose}
              />
            </div>
          </motion.aside>
        )}
      </AnimatePresence>

      {/* Mobil filtre (tam ekran modal) */}
      <AnimatePresence>
        {isOpen && (
          <motion.div
            key="mobile-filter"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.2 }}
            className="fixed inset-0 z-50 bg-white md:hidden overflow-y-auto p-6"
          >
            <button
              onClick={handleClose}
              className="mb-4 text-lg font-bold text-right w-full"
            >
              ✕ Kapat
            </button>
            <CategoryFilter
              isOpen={isOpen}
              handleOpen={handleOpen}
              handleClose={handleClose}
            />
          </motion.div>
        )}
      </AnimatePresence>

      {/* Ürün listesi */}
      <motion.main
        layout
        initial={{ opacity: 0, y: 20, x: 20 }}
        animate={{ opacity: 1, y: 0, x: 0 }}
        transition={{ duration: 0.4 }}
        className={`self-start ${
          isOpen
            ? "col-span-12 md:col-span-9 lg:col-span-10"
            : "col-span-12"
        }`}
      >
        <SearchProductList />
      </motion.main>
    </div>
  )
}
