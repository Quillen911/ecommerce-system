"use client";

import { QueryProvider } from "@/providers/QueryProvider";
import { CategoryProvider } from "@/contexts/CategoryContext";
import ConditionalHeader from "@/components/header/ConditionalHeader";
import CondFooter from "@/components/footer/CondFooter";
import { Toaster } from "sonner";
import CampaignBanner from "../home/CampaignBanner";

export default function Layout({ children }: { children: React.ReactNode }) {
  return (
    <>
      <Toaster position="top-right" richColors closeButton />

      <QueryProvider>
        <CategoryProvider>
          <CampaignBanner />
          <ConditionalHeader />

          <main className="flex-1 min-h-screen bg-[var(--main-bg)]">
            {children}
          </main>

          <CondFooter />
        </CategoryProvider>
      </QueryProvider>
    </>
  );
}
