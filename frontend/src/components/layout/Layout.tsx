"use client";

import { QueryProvider } from "@/providers/QueryProvider";
import { CategoryProvider } from "@/contexts/CategoryContext";
import ConditionalHeader from "@/components/header/ConditionalHeader";
import Footer from "@/components/footer/Footer";
import { Toaster } from "sonner";

export default function Layout({ children }: { children: React.ReactNode }) {
  return (
    <>
      <Toaster position="top-right" richColors closeButton />

      <QueryProvider>
        <CategoryProvider>
          <ConditionalHeader />

          <main className="flex-1 min-h-screen bg-[var(--main-bg)]">
            {children}
          </main>

          <Footer />
        </CategoryProvider>
      </QueryProvider>
    </>
  );
}
