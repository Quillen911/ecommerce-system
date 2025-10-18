'use client'

import Link from "next/link"
import { usePathname } from 'next/navigation'
import { motion } from 'framer-motion'
import { useLogout } from "@/hooks/seller/useSellerAuthQuery"
import { useRouter } from "next/navigation"
import { useState, useEffect } from "react"

export default function SellerShell({ children }: { children: React.ReactNode }) {
  const pathname = usePathname()
  const logoutMutation = useLogout()
  const router = useRouter()

  const handleLogout = async () => {
    await logoutMutation.mutate()
    router.push('/seller/login')
  }

  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false)
  const menuItems = [
    { href: '/seller/product', label: 'Ürünlerim' },
    { href: '/seller/campaign', label: 'Kampanyalarım' },
    { href: '/seller/order', label: 'Siparişlerim' },
  ]
  const isActive = (href: string) => pathname === href

  // Menü açıldığında body scroll’u kilitle
  useEffect(() => {
    document.body.style.overflow = isMobileMenuOpen ? "hidden" : ""
    return () => { document.body.style.overflow = "" }
  }, [isMobileMenuOpen])

  // Tıklama sonrası menü kapansın
  const handleLinkClick = (href: string) => {
    router.push(href)
    setIsMobileMenuOpen(false)
  }

  return (
    <div className="flex min-h-screen bg-gray-50">
      {/* Sol Sidebar */}
      <motion.div
        initial={{ x: -100, opacity: 0 }}
        animate={{ x: 0, opacity: 1 }}
        transition={{ duration: 0.5, ease: "easeOut" }}
        className={`fixed md:relative top-0 left-0 h-full bg-white border-r border-gray-200 shadow-sm z-50 md:z-auto transition-transform duration-300 ease-in-out
          ${isMobileMenuOpen ? "translate-x-0" : "-translate-x-full md:translate-x-0"}
        `}
        style={{ width: "280px" }}
      >
        <div className="p-6 overflow-y-auto h-full">
          <div className="flex justify-between items-center mb-6 md:hidden">
            <h2 className="text-xl font-bold text-gray-900">Menü</h2>
            <button
              onClick={() => setIsMobileMenuOpen(false)}
              className="text-gray-600 text-2xl font-bold"
            >
              ✕
            </button>
          </div>

          <motion.div
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="mb-8 hidden md:block"
          >
            <h2 className="text-2xl font-bold text-gray-900 mb-2">Ürünlerim</h2>
            <motion.div
              initial={{ width: 0 }}
              animate={{ width: 48 }}
              transition={{ duration: 0.8, delay: 0.4 }}
              className="h-1 bg-black rounded-full"
            />
          </motion.div>

          <nav className="space-y-1 relative">
            {menuItems.map((item, index) => (
              <motion.div
                key={item.href}
                initial={{ opacity: 0, x: -30 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{
                  duration: 0.4,
                  delay: 0.3 + index * 0.1,
                  ease: "easeOut"
                }}
              >
                <button
                  onClick={() => handleLinkClick(item.href)}
                  className={`
                    w-full text-left relative flex items-center py-3 px-4 rounded-lg transition-all duration-200 group
                    ${isActive(item.href)
                      ? "text-black bg-gray-100 font-bold text-lg"
                      : "text-gray-700 hover:bg-gray-50 hover:text-black hover:scale-105"}
                  `}
                >
                  <motion.span
                    className={`w-2 h-2 rounded-full mr-3 transition-colors duration-200 ${
                      isActive(item.href)
                        ? "bg-black"
                        : "bg-gray-400 group-hover:bg-black"
                    }`}
                    whileHover={{ scale: 1.2 }}
                  />
                  {item.label}
                </button>
              </motion.div>
            ))}
          </nav>

          <div className="p-6 border-t border-gray-200 mt-8">
            <button
              onClick={handleLogout}
              className="bg-[var(--text)] text-white w-full py-2 rounded-lg hover:bg-[var(--danger)] cursor-pointer"
            >
              Çıkış Yap
            </button>
          </div>
        </div>
      </motion.div>

      {/* Mobil Menü Butonu */}
      <button
        onClick={() => setIsMobileMenuOpen((prev) => !prev)}
        className="md:hidden fixed top-4 left-4 bg-black text-white p-2 rounded-lg z-50"
      >
        {isMobileMenuOpen ? "✕" : "☰"}
      </button>

      {/* Sağ İçerik */}
      <motion.div
        key={pathname}
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4, delay: 0.1 }}
        className="flex-1 p-6 md:p-8"
      >
        <div className="max-w-4xl">{children}</div>
      </motion.div>
    </div>
  )
}
