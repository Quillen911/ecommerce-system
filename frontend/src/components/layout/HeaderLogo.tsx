import Link from 'next/link'
import { HeaderProps } from '@/types/header'

export default function Logo({ className = '' }: HeaderProps) {
  return (
    <Link href="/" className={`flex items-center ${className}`}>
      <span className="text-2xl font-bold text-black">Omnia</span>
    </Link>
  )
}