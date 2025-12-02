'use client'
import { usePathname } from 'next/navigation'
import Header from './Header'


export default function ConditionalHeader() {
  const pathname = usePathname()
  const showHeader = !pathname.includes('/login') && !pathname.includes('/register') && !pathname.includes('/seller')

  if (!showHeader) return null

  return <Header/>
}