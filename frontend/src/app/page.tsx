'use client'
import { useState, useEffect } from 'react'
import { useMe } from '@/hooks/useAuthQuery'
import CampaignBanner from '@/components/home/CampaignBanner'
import HeroSection from '@/components/home/HeroSection'
import CategorySection from '@/components/home/CategorySection'
import PopulerProductSection from '@/components/home/PopulerProductSection'
import { useQueryClient } from '@tanstack/react-query'
import { useCategory } from '@/contexts/CategoryContext'

export default function Home() {
  const [mounted, setMounted] = useState(false)
  const { isLoading } = useMe()
  const { resetCategory } = useCategory()
  const queryClient = useQueryClient()

  useEffect(() => {
    queryClient.removeQueries({ queryKey: ['category-products'], exact: false })
    queryClient.invalidateQueries({ queryKey: ['main-data'] })
    resetCategory()
    setMounted(true)
  }, [queryClient, resetCategory])

  if (!mounted || isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-[var(--main-bg)] text-[var(--text)]">
        <p>Yükleniyor...</p>
      </div>
    )
  }

  return (
    <div className="bg-[var(--main-bg)] text-[var(--text)]">
      <CampaignBanner />
      <HeroSection />
      <CategorySection />
      <PopulerProductSection />
    </div>
  )
}
