"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { useMe } from "@/hooks/useAuthQuery";

import Logo from "./HeaderLogo";
import UserMenu from "./UserMenu";
import CartButton from "./CartButton";
import SearchBar from "./SearchBar";
import CategoryDropdown from "./CategoryDropdown";

export default function Header() {
  const { data: user, isLoading } = useMe();
  const router = useRouter();
  const [mobileOpen, setMobileOpen] = useState(false);

  const handleCartClick = () => router.push("/bag");

  useEffect(() => {
    if (mobileOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
    return () => {
      document.body.style.overflow = "";
    };
  }, [mobileOpen]);

  return (
    <header className="bg-[var(--main-bg)] text-[var(--text)] sticky top-0 z-50 border-b border-neutral-800">
      <div className="max-w-[1800px] mx-auto px-4 md:px-10">
        <div className="flex items-center justify-between h-16 relative">
          <div className="flex items-center">
            <Logo />
          </div>

          <div className="hidden md:flex flex-1 justify-center pl-[15%]">
            <CategoryDropdown />
          </div>

          <div className="flex items-center space-x-2 md:space-x-3">
            <div className="hidden md:block">
              <SearchBar />
            </div>

            <CartButton onClick={handleCartClick} />
            <UserMenu user={user} isLoading={isLoading} />

            <button
              aria-label="Menü"
              className="md:hidden flex flex-col justify-center items-center space-y-[5px] relative w-8 h-8"
              onClick={() => setMobileOpen((prev) => !prev)}
            >
              <span
                className={`w-6 h-[2px] bg-white rounded transition-all duration-300 ${
                  mobileOpen ? "rotate-45 translate-y-[7px]" : ""
                }`}
              />
              <span
                className={`w-6 h-[2px] bg-white rounded transition-all duration-300 ${
                  mobileOpen ? "opacity-0" : ""
                }`}
              />
              <span
                className={`w-6 h-[2px] bg-white rounded transition-all duration-300 ${
                  mobileOpen ? "-rotate-45 -translate-y-[7px]" : ""
                }`}
              />
            </button>
          </div>
        </div>

        {mobileOpen && (
          <div className="md:hidden fixed inset-x-0 top-16 bg-neutral-900 text-white py-6 px-6 space-y-6 border-t border-neutral-700 animate-fadeIn overflow-y-auto max-h-[calc(100vh-4rem)]">
            <CategoryDropdown isMobile />
            <div className="pt-3 border-t border-neutral-800">
              <SearchBar />
            </div>
          </div>
        )}
      </div>
    </header>
  );
}
