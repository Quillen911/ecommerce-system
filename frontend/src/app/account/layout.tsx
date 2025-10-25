"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { motion, AnimatePresence } from "framer-motion";
import { useEffect, useMemo, useState } from "react";

const DRAWER_WIDTH = 280;

export default function AccountLayout({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  const menuItems = useMemo(
    () => [
      { href: "/account/profile", label: "Profilim" },
      { href: "/account/addresses", label: "Adreslerim" },
      { href: "/account/orders", label: "Siparişlerim" },
    ],
    []
  );

  const isActive = (href: string) => pathname === href;

  useEffect(() => {
    document.body.style.overflow = isMobileMenuOpen ? "hidden" : "";
    return () => {
      document.body.style.overflow = "";
    };
  }, [isMobileMenuOpen]);

  const closeMenu = () => setIsMobileMenuOpen(false);
  const toggleMenu = () => setIsMobileMenuOpen((prev) => !prev);

  return (
    <div className="relative flex min-h-screen bg-gray-50 md:pl-[260px]">
      <DesktopSidebar menuItems={menuItems} isActive={isActive} />

      <AnimatePresence>
        {isMobileMenuOpen && (
          <>
            <motion.div
              key="drawer-backdrop"
              initial={{ opacity: 0 }}
              animate={{ opacity: 0.35 }}
              exit={{ opacity: 0 }}
              transition={{ duration: 0.2 }}
              className="fixed inset-0 z-30 bg-black md:hidden"
              onClick={closeMenu}
            />
            <motion.aside
              key="drawer-panel"
              initial={{ x: -DRAWER_WIDTH }}
              animate={{ x: 0 }}
              exit={{ x: -DRAWER_WIDTH }}
              transition={{ duration: 0.24, ease: "easeOut" }}
              className="fixed inset-y-0 left-0 z-40 flex w-[min(85vw,280px)] flex-col overflow-y-auto border-r border-gray-200 bg-white pt-6 pb-10 shadow-lg md:hidden"
            >
              <div className="flex items-center justify-between px-6">
                <h2 className="text-xl font-bold text-gray-900">Hesabım</h2>
                <button
                  onClick={closeMenu}
                  className="flex h-10 w-10 items-center justify-center rounded-full bg-black text-lg text-white"
                >
                  ✕
                </button>
              </div>

              <SidebarLinks menuItems={menuItems} isActive={isActive} onLinkClick={closeMenu} />
            </motion.aside>
          </>
        )}
      </AnimatePresence>

      <motion.main
        key={pathname}
        initial={{ opacity: 0, y: 18 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.35, ease: "easeOut" }}
        style={{
          transform: isMobileMenuOpen
            ? `translateX(${Math.min(typeof window !== "undefined" ? window.innerWidth * 0.85 : DRAWER_WIDTH, DRAWER_WIDTH)}px)`
            : undefined,
          transition: "transform 0.25s ease",
        }}
        className="flex-1 px-4 pb-10 pt-20 sm:px-6 sm:pt-24 lg:px-10 md:pt-12 md:pl-10"
      >
        <div className="mx-auto flex w-full max-w-4xl flex-col gap-6">
          <div className="md:hidden">
            <MobileToggleButton isOpen={isMobileMenuOpen} onToggle={toggleMenu} />
          </div>
          {children}
        </div>
      </motion.main>
    </div>
  );
}

type MenuItem = { href: string; label: string };
type SidebarProps = {
  menuItems: MenuItem[];
  isActive: (href: string) => boolean;
};

function DesktopSidebar({ menuItems, isActive }: SidebarProps) {
  return (
    <motion.aside
      initial={{ x: -80, opacity: 0 }}
      animate={{ x: 0, opacity: 1 }}
      transition={{ duration: 0.45, ease: "easeOut" }}
      className="fixed top-0 left-0 hidden h-full w-[260px] shrink-0 flex-col border-r border-gray-200 bg-white px-6 py-8 shadow-sm md:flex"
    >
      <div>
        <h2 className="text-2xl font-bold text-gray-900">Hesabım</h2>
        <motion.div
          initial={{ width: 0 }}
          animate={{ width: 48 }}
          transition={{ duration: 0.5, delay: 0.2 }}
          className="mt-2 h-1 rounded-full bg-black"
        />
      </div>

      <SidebarLinks menuItems={menuItems} isActive={isActive} />
    </motion.aside>
  );
}

type SidebarLinksProps = SidebarProps & {
  onLinkClick?: () => void;
};

function SidebarLinks({ menuItems, isActive, onLinkClick }: SidebarLinksProps) {
  return (
    <nav className="mt-8 space-y-2 px-2">
      {menuItems.map((item, index) => (
        <motion.div
          key={item.href}
          initial={{ opacity: 0, x: -24 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.3, delay: 0.15 + index * 0.06, ease: "easeOut" }}
        >
          <Link
            href={item.href}
            onClick={onLinkClick}
            className={`flex items-center rounded-lg px-4 py-3 text-sm sm:text-base transition-colors duration-200 ${
              isActive(item.href)
                ? "bg-gray-100 text-gray-900 shadow-sm"
                : "text-gray-700 hover:bg-gray-50 hover:text-gray-900"
            }`}
          >
            <span className={`mr-3 h-2 w-2 rounded-full ${isActive(item.href) ? "bg-black" : "bg-gray-400"}`} />
            {item.label}
          </Link>
        </motion.div>
      ))}
    </nav>
  );
}

function MobileToggleButton({ isOpen, onToggle }: { isOpen: boolean; onToggle: () => void }) {
  return (
    <button
      onClick={onToggle}
      aria-label={isOpen ? "Menüyü kapat" : "Menüyü aç"}
      className="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-black text-white shadow-md md:hidden"
    >
      <span className="text-xl leading-none">{isOpen ? "✕" : "☰"}</span>
    </button>
  );
}
