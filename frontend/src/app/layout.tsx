import type { Metadata } from "next";
import "../styles/globals.css";
import { QueryProvider } from "@/providers/QueryProvider";
import { CategoryProvider } from "@/contexts/CategoryContext";
import ConditionalHeader from "@/components/layout/ConditionalHeader";

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
