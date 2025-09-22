'use client'
import Link from "next/link"
import { usePathname } from 'next/navigation'
import { motion } from 'framer-motion'
import { useLogout } from "@/hooks/seller/useSellerAuthQuery"
import { useRouter } from "next/navigation"

export default function SellerShell ( {children}: {children: React.ReactNode} ) {

  const pathname = usePathname()
  const logoutMutation = useLogout()
  const router = useRouter()
  const handleLogout = async () => {
    await logoutMutation.mutate()
    router.push('/seller/login')
  }
  const menuItems = [
    { href: '/seller/product', label: 'Ürünlerim' },
    { href: '/seller/campaign', label: 'Kampanyalarım' },
    { href: '/seller/order', label: 'Siparişlerim' },
  ]

  const isActive = (href: string) => pathname === href

  return (
    <div className="flex min-h-screen bg-gray-50">
      {/* Sol Sidebar */}
      <motion.div 
        initial={{ x: -100, opacity: 0 }}
        animate={{ x: 0, opacity: 1 }}
        transition={{ duration: 0.5, ease: "easeOut" }}
        style={{ width: '280px' }}
        className="bg-white border-r border-gray-200 shadow-sm relative"
      >
        <div className="p-6">
          <motion.div 
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="mb-8"
          >
            <h2 className="text-2xl font-bold text-gray-900 mb-2">Ürünlerim</h2>
            <motion.div 
              initial={{ width: 0 }}
              animate={{ width: 48 }}
              transition={{ duration: 0.8, delay: 0.4 }}
              className="h-1 bg-black rounded-full"
            ></motion.div>
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
                <Link 
                  href={item.href}
                  className={`
                    relative flex items-center py-3 px-4 rounded-lg transition-all duration-200 group
                    ${isActive(item.href) 
                      ? 'text-black bg-gray-100 font-bold text-lg'
                      : 'text-gray-700 hover:bg-gray-50 hover:text-black hover:scale-105'
                    }
                  `}
                >
                  <motion.span 
                    className={`w-2 h-2 rounded-full mr-3 transition-colors duration-200 ${
                      isActive(item.href) ? 'bg-black' : 'bg-gray-400 group-hover:bg-black'
                    }`}
                    whileHover={{ scale: 1.2 }}
                  ></motion.span>
                  {item.label}
                  
                </Link>
              </motion.div>
            ))}
          </nav>
        </div>
        <div className="p-6 border-t border-gray-200 absolute bottom-10 left-15 right-0" >
            <button onClick={handleLogout} className="bg-[var(--text)] text-white px-4 py-2 rounded-lg hover:bg-[var(--danger)] cursor-pointer">
                Çıkış Yap
            </button>
        </div>
      </motion.div>
      
      {/* Sağ İçerik */}
      <motion.div 
        key={pathname}
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4, delay: 0.1 }}
        className="flex-1 p-8"
      >
        <div className="max-w-4xl">
          {children}
        </div>
      </motion.div>
    </div>
  )
}