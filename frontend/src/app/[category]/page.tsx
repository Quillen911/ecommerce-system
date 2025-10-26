"use client";

import { use, useState, useEffect } from "react";
import { notFound } from "next/navigation";
import { FaFilter } from "react-icons/fa";
import { AnimatePresence, motion } from "framer-motion";

import { useCategoryProducts } from "@/hooks/useSearchQuery";
import CategoryFilter from "@/components/product/CategoryFilter";
import ProductList from "@/components/product/ProductList";
import SortingFilter from "@/components/product/SortingFilter";

const DESKTOP_FILTER_WIDTH = 280;

type PageProps = {
  params: Promise<{ category: string }>;
  searchParams: Record<string, string | string[] | undefined>;
};

export default function CategoryPageRoute({ params, searchParams }: PageProps) {
  const resolvedParams = use(params);             // ← Promise’i çözüyoruz
  const { data, isLoading } = useCategoryProducts(resolvedParams.category);

  if (!isLoading && (!data || data.products.length === 0)) {
    notFound();
  }

  return <CategoryPage categoryProducts={data?.products ?? []} isLoading={isLoading} />;
}

function CategoryPage({ categoryProducts, isLoading }: { categoryProducts: any; isLoading: boolean }) {
  const [isOpen, setIsOpen] = useState(true);
  const [hasMounted, setHasMounted] = useState(false);

  useEffect(() => setHasMounted(true), []);

  const toggleFilters = () => setIsOpen((prev) => !prev);
  const closeFilters = () => setIsOpen(false);

  return (
    <div className="min-h-screen bg-[var(--bg)] p-4 sm:p-8">
      <div className="flex flex-wrap items-center justify-between gap-5">
        <SortingFilter />
        <button onClick={toggleFilters} type="button" className="flex items-center gap-2 text-base font-bold sm:text-lg">
          <FaFilter className="h-6 w-6 sm:h-8 sm:w-8" />
          <span>{isOpen ? "Filtrelemeyi Gizle" : "Filtrelemeyi Göster"}</span>
        </button>
      </div>

      <motion.section
        layout
        className="mt-6 flex flex-col gap-6 md:flex-row"
        transition={{ layout: { duration: 0.25, ease: "easeInOut" } }}
      >
        <motion.aside
          layout
          initial={false}
          animate={{ width: isOpen ? DESKTOP_FILTER_WIDTH : 0, opacity: isOpen ? 1 : 0 }}
          transition={{ duration: 0.25, ease: "easeInOut" }}
          className="hidden md:block"
        >
          {isOpen ? (
            <div className="pr-2" style={{ position: "sticky", top: "100px" }}>
              <CategoryFilter isOpen={isOpen} handleOpen={toggleFilters} handleClose={closeFilters} />
            </div>
          ) : null}
        </motion.aside>

        <motion.main
          layout
          initial={hasMounted ? false : { opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.3, ease: "easeOut" }}
          className="flex-1"
        >
          <ProductList products={categoryProducts} isLoading={isLoading} />
        </motion.main>
      </motion.section>

      <AnimatePresence>
        {isOpen && (
          <motion.div
            key="mobile-filter"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-50 overflow-y-auto bg-white p-6 md:hidden"
          >
            <button onClick={closeFilters} className="mb-4 w-full text-right text-lg font-bold">
              ✕ Kapat
            </button>
            <CategoryFilter isOpen={isOpen} handleOpen={toggleFilters} handleClose={closeFilters} />
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
