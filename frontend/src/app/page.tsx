'use client'
import { useState, useEffect } from 'react'
import { useMe } from '@/hooks/useAuthQuery'
import CampaignBanner from '@/components/home/CampaignBanner'
import HeroSection from '@/components/home/HeroSection'
import CategorySection from '@/components/home/CategorySection'
import PopulerProductSection from '@/components/home/PopulerProductSection'
import { useQueryClient } from '@tanstack/react-query'
import { useCategory } from '@/contexts/CategoryContext'
import ConditionalHeader from '@/components/header/ConditionalHeader'
import GalleryLayout from '@/components/home/ImageHoverCard'
import SpaceComponent from '@/components/home/SpaceComponent'
import ProVideo from '@/components/ui/ProVideo'

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
    <div className="bg-white text-[var(--text)]">
      <HeroSection />
      <CategorySection />
      <PopulerProductSection />
      <ProVideo
        title="Ürün tanıtım videosu"
        poster="/images/video-poster.jpg"
        captionsSrc="/images/categories/1.png"
        sources={[
          { src: '/video/hero-1080.mp4', type: 'video/mp4' },
          { src: '/video/hero-720.webm', type: 'video/webm' }
        ]}
        autoPlay={true}
        loop={true}
        muted={true}
      />
      <GalleryLayout />
      <SpaceComponent />
    </div>
  )
}
