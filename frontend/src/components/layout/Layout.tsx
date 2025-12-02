"use client";

import { QueryProvider } from "@/providers/QueryProvider";
import { CategoryProvider } from "@/contexts/CategoryContext";
import ConditionalHeader from "@/components/header/ConditionalHeader";
import CondFooter from "@/components/footer/CondFooter";
import { Toaster } from "sonner";
import CampaignBanner from "../home/CampaignBanner";
import { useEffect, useState } from "react";

export default function Layout({ children }: { children: React.ReactNode }) {
  const [mounted, setMounted] = useState(false)
  useEffect(() => setMounted(true), [])

  if (!mounted) {
    return null
  }
  return (
<>
  <Toaster />

  <QueryProvider>
    <CategoryProvider>
      <ConditionalHeader />

      <main>
        {children}
      </main>

      <CondFooter />
    </CategoryProvider>
  </QueryProvider>
</>
  );
}
