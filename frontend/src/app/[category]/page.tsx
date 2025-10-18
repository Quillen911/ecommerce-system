"use client"

import CategoryFilter from "@/components/product/CategoryFilter"
import ProductList from "@/components/product/ProductList"
import SortingFilter from "@/components/product/SortingFilter"
import { useState } from "react"
import { FaFilter } from "react-icons/fa"
import { motion, AnimatePresence } from "framer-motion"

export default function CategoryPage() {
  const [isOpen, setIsOpen] = useState(true)

  const handleOpen = () => setIsOpen((prev) => !prev)
  const handleClose = () => setIsOpen(false)

  return (
    <div className="min-h-screen grid grid-cols-12 gap-6 bg-[var(--bg)] p-4 sm:p-8">
      {/* Filtre butonu */}
      <div className="col-span-12 flex justify-between sm:justify-end gap-5">
        <div className="flex items-center gap-2">
          <SortingFilter />
        </div>
        <button
          onClick={handleOpen}
          type="button"
          className="flex items-center gap-2 text-base sm:text-lg font-bold"
        >
          <FaFilter className="w-6 h-6 sm:w-8 sm:h-8" />
          <span>{isOpen ? "Filtrelemeyi Gizle" : "Filtrelemeyi Göster"}</span>
        </button>
      </div>

      {/* Sidebar (Desktop) */}
      <AnimatePresence>
        {isOpen && (
          <motion.aside
            key="desktop-filter"
            initial={{ opacity: 0, x: -150 }}
            animate={{ opacity: 1, x: 0 }}
            exit={{ opacity: 0, x: -150 }}
            transition={{ duration: 0.3 }}
            className="hidden md:block col-span-3 lg:col-span-2 xl:col-span-2 self-start"
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

      {/* Mobilde tam ekran filtre */}
      <AnimatePresence>
        {isOpen && (
          <motion.div
            key="mobile-filter"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
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
