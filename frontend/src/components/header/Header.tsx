"use client";

import { useMe } from "@/hooks/useAuthQuery";
import { useRouter } from "next/navigation";
import { useState } from "react";

import Logo from "./HeaderLogo";
import UserMenu from "./UserMenu";
import CartButton from "./CartButton";
import SearchBar from "./SearchBar";
import CategoryDropdown from "./CategoryDropdown";

export default function Header() {
  const { data: user, isLoading } = useMe();
  const router = useRouter();
  const [mobileOpen, setMobileOpen] = useState(false);

  const handleCartClick = () => {
    router.push("/bag");
  };

  return (
    <header className="bg-[var(--main-bg)] text-[var(--text)] sticky top-0 z-50 border-b border-neutral-800">
      <div className="max-w-[1800px] mx-auto px-6 md:px-10">
        <div className="flex items-center justify-between h-16 relative">
          <div className="flex items-center">
            <Logo />
          </div>

          <div className="hidden md:flex flex-1 justify-center pl-[15%]">
            <CategoryDropdown />
          </div>

          <div className="flex items-center space-x-1 md:space-x-3">
            <div className="hidden md:block">
              <SearchBar />
            </div>
            <CartButton onClick={handleCartClick} />
            <UserMenu user={user} isLoading={isLoading} />

            <button
              className="md:hidden flex flex-col justify-center items-center space-y-1"
              onClick={() => setMobileOpen((prev) => !prev)}
            >
              <span className="w-6 h-[2px] bg-white" />
              <span className="w-6 h-[2px] bg-white" />
              <span className="w-6 h-[2px] bg-white" />
            </button>
          </div>
        </div>

        {mobileOpen && (
          <div className="md:hidden bg-neutral-900 text-white py-4 px-6 space-y-4 border-t border-neutral-700 animate-fadeIn">
            <CategoryDropdown isMobile />
            <div>
              <SearchBar />
            </div>
          </div>
        )}
      </div>
    </header>
  );
}
