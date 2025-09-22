'use client'

import { usePathname } from "next/navigation";
import SellerShell from "./seller-shell";

export default function ConditionalSideBar({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();
  const showSideBar = !pathname.startsWith("/seller/login");

  if (!showSideBar) return <>{children}</>;

  return <SellerShell>{children}</SellerShell>;
}
