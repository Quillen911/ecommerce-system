'use client'
import { useQueryClient } from '@tanstack/react-query'
import { useRouter } from 'next/navigation'
import { HeaderProps } from '@/types/header'
import { useCategory } from '@/contexts/CategoryContext'

export default function Logo({ className = '' }: HeaderProps) {
  const queryClient = useQueryClient()
  const router = useRouter()
  const { resetCategory } = useCategory()

  const handleLogoClick = () => {
    queryClient.removeQueries({ queryKey: ['category-products'], exact: false })
    queryClient.invalidateQueries({ queryKey: ['main-data'] })
    resetCategory()
    router.push('/')
  }

  return (
    <button 
      onClick={handleLogoClick} 
      className={`flex items-center ${className}`}
    >
      <span className="text-2xl font-bold text-black">Omnia</span>
    </button>
  )
}
