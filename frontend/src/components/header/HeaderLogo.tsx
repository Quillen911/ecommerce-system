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
    queryClient.removeQueries({ queryKey: ['main-data'], exact: false })
    queryClient.invalidateQueries({ queryKey: ['main-data'] })
    resetCategory()
    router.push('/')
  }

  return (
    <button
      onClick={handleLogoClick}
      className={`flex items-center ${className}`}
    >
      <span
        className="text-xl sm:text-2xl md:text-2xl font-bold text-white cursor-pointer truncate max-w-[140px] sm:max-w-none"
      >
        {process.env.NEXT_PUBLIC_APP_NAME}
      </span>
    </button>
  )
}
