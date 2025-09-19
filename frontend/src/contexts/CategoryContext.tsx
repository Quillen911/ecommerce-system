'use client'
import { createContext, useContext, useState, ReactNode, useCallback } from 'react'

interface CategoryContextType {
  selectedCategory: string | null
  setSelectedCategory: (category: string | null) => void
  resetCategory: () => void
}

const CategoryContext = createContext<CategoryContextType | undefined>(undefined)

export function CategoryProvider({ children }: { children: ReactNode }) {
  const [selectedCategory, setSelectedCategory] = useState<string | null>(null)

  const resetCategory = useCallback(() => {
    setSelectedCategory(null)
  }, [])

  const handleSetSelectedCategory = useCallback((category: string | null) => {
    setSelectedCategory(category)
  }, [])

  return (
    <CategoryContext.Provider value={{ selectedCategory, setSelectedCategory: handleSetSelectedCategory, resetCategory }}>
      {children}
    </CategoryContext.Provider>
  )
}

export function useCategory() {
  const context = useContext(CategoryContext)
  if (context === undefined) {
    throw new Error('useCategory must be used within a CategoryProvider')
  }
  return context
}
