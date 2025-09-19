import type { Metadata } from "next";
import "../styles/globals.css";
import { QueryProvider } from "@/providers/QueryProvider";
import { CategoryProvider } from "@/contexts/CategoryContext";
import ConditionalHeader from "@/components/layout/ConditionalHeader";
import { Toaster } from "sonner";

export const metadata: Metadata = {
  title: "Omnia",
  description: "Omnia",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="tr">
      <body className="antialiased">
        <Toaster 
          position="top-right"
          richColors
          closeButton
        />
        <QueryProvider>
          <CategoryProvider>
            <ConditionalHeader />
            {children}
          </CategoryProvider>
        </QueryProvider>
      </body>
    </html>
  );
}
