'use client'
import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import { useMe } from '@/hooks/useAuthQuery'

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
    <div className="min-h-screen flex flex-col items-center justify-center">
      <h2 className="text-2xl font-bold">Hoş Geldiniz, {user?.username}</h2>
    </div>
    
  );
}
