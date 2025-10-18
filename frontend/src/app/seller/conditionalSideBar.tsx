'use client'

import { usePathname } from "next/navigation";
import SellerShell from "./seller-shell";

export default function ConditionalSideBar({
  children,
}: {
  children: React.ReactNode;
}) {
  const pathname = usePathname();

  const hideSidebarRoutes = ["/seller/login", "/seller/register", "/seller/reset"];
  const showSideBar = !hideSidebarRoutes.some((route) => pathname.startsWith(route));

  if (!showSideBar) return <>{children}</>;

  return <SellerShell>{children}</SellerShell>;
}
