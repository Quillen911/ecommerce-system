'use client'
import { usePathname } from 'next/navigation'
import Footer from './Footer'

export default function ConditionalFooter() {
    const pathname = usePathname()
    const showFooter = !pathname.includes('/login') && !pathname.includes('/register') && !pathname.includes('/seller') && !pathname.includes('/seller/*')

    if (!showFooter) return null

    return <Footer />
}