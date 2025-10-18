'use client'
import Link from "next/link"
import { usePathname } from 'next/navigation'
import { motion } from 'framer-motion'

export default function AccountLayout({ children }: { children: React.ReactNode }) {
  const pathname = usePathname()
  const menuItems = [
    { href: '/account/profile', label: 'Profilim' },
    { href: '/account/addresses', label: 'Adreslerim' },
    { href: '/account/orders', label: 'Siparişlerim' }
  ]
  const isActive = (href: string) => pathname === href

  return (
    <div className="flex min-h-screen flex-col md:flex-row bg-gray-50">
      <motion.div
        initial={{ x: -100, opacity: 0 }}
        animate={{ x: 0, opacity: 1 }}
        transition={{ duration: 0.5, ease: 'easeOut' }}
        className="bg-white border-b md:border-b-0 md:border-r border-gray-200 shadow-sm w-full md:w-[260px] sticky top-0 z-10"
      >
        <div className="p-4 sm:p-6">
          <motion.div
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="mb-6 sm:mb-8"
          >
            <h2 className="text-lg sm:text-2xl font-bold text-gray-900 mb-2">Hesabım</h2>
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
                transition={{ duration: 0.4, delay: 0.3 + index * 0.1, ease: 'easeOut' }}
              >
                <Link
                  href={item.href}
                  className={`relative flex items-center py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg transition-all duration-200 group ${
                    isActive(item.href)
                      ? 'text-black bg-gray-100 font-semibold sm:font-bold'
                      : 'text-gray-700 hover:bg-gray-50 hover:text-black hover:scale-[1.02]'
                  }`}
                >
                  <motion.span
                    className={`w-2 h-2 rounded-full mr-2 sm:mr-3 transition-colors duration-200 ${
                      isActive(item.href) ? 'bg-black' : 'bg-gray-400 group-hover:bg-black'
                    }`}
                    whileHover={{ scale: 1.2 }}
                  />
                  <span className="text-sm sm:text-base">{item.label}</span>
                </Link>
              </motion.div>
            ))}
          </nav>
        </div>
      </motion.div>

      <motion.div
        key={pathname}
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4, delay: 0.1 }}
        className="flex-1 w-full p-5 sm:p-8"
      >
        <div className="max-w-5xl mx-auto">{children}</div>
      </motion.div>
    </div>
  )
}
