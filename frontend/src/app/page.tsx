'use client'
import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import { useMe } from '@/hooks/useAuthQuery'
import CampaignBanner from '@/components/home/CampaignBanner'
import HeroSection from '@/components/home/HeroSection'
import CategorySection from '@/components/home/CategorySection'
import ProductSection from '@/components/home/ProductSection'
export default function Home() {
  const router = useRouter()
  const [mounted, setMounted] = useState(false)
  const { data: user, isLoading, error } = useMe()

  useEffect(() => {
    setMounted(true)
  }, [])


  if (!mounted || isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <p>Yükleniyor...</p>
      </div>
    )
  }

  return (
    <div>
      <CampaignBanner />
      <HeroSection />
      <CategorySection />
      <ProductSection />
    </div>
    
  );
}
