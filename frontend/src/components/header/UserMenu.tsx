'use client'
import { useState, useEffect } from 'react'
import { useLogout } from '@/hooks/useAuthQuery'
import Link from 'next/link'
import { useRouter } from 'next/navigation'
import { UserIcon, ChevronDownIcon } from '@heroicons/react/24/outline'
import { UserMenuProps } from '@/types/header'

export default function UserMenu({ user, isLoading, className = '' }: UserMenuProps) {
  const [isOpen, setIsOpen] = useState(false)
  const router = useRouter()
  const logoutMutation = useLogout()
  const [mounted, setMounted] = useState(false)

  useEffect(() => {
    setMounted(true)
  }, [])

  if (!mounted) {
    return <div>Loading...</div>
  }
  
  const handleLogout = () => {
    logoutMutation.mutate();
  };

  if (isLoading) {
    return <div className="w-20 h-8 text-white"></div>
  } 
  if (!user) {
    return (
      <div className={`flex items-center space-x-2 ${className}`}>
        <Link href="/login" className="text-white hover:text-white cursor-pointer">
          Giriş Yap
        </Link>
      </div>
    )
  }

  return (
    <div className={`relative ${className}`}>
      <button
        onClick={() => setIsOpen(!isOpen)}
        className="flex items-center space-x-2 text-white hover:text-gray-500 cursor-pointer"
      >
        <UserIcon className="h-6 w-6" />
        <span>{user.username}</span>
        <ChevronDownIcon className="h-4 w-4" />
      </button>

      {isOpen && (
        <div className="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 text-black">
          <div className="px-4 py-2 border-b border-gray-200 text-black">
            <p className="text-sm font-medium text-gray-900">{user.username}</p>
            <p className="text-xs text-gray-500">{user.email}</p>
          </div>
          <Link href="/account/profile" onClick={() => setIsOpen(false)} className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 text-black">
            Hesabım
          </Link>
          <button
            onClick={handleLogout}
            className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 text-black"
          >
            Çıkış Yap
          </button>
        </div>
      )}
    </div>
  )
}