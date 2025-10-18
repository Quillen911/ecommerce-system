// src/app/layout.tsx
import type { Metadata } from "next";
import "../styles/globals.css";
import Layout from "@/components/layout/Layout";

export const metadata: Metadata = {
  title: {
    default: "Omnia",
    template: "%s | Omnia",
  },
  description: "Omnia alışveriş platformu",
  viewport: "width=device-width, initial-scale=1.0",
  icons: {
    icon: "/favicon.ico",
  },
  manifest: "/manifest.json",
  //metadataBase: new URL("https://omnia.com"),
  robots: { index: true, follow: true },
  openGraph: {
    title: "Omnia",
    description: "Omnia alışveriş platformu",
    type: "website",
    locale: "tr_TR",
  },
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="tr">
      <body
        className="
          bg-[var(--main-bg)]
          text-[var(--text)]
          antialiased
          overflow-x-hidden
          min-h-screen
          flex flex-col
        "
      >
        {/* Ana uygulama düzeni */}
        <Layout>{children}</Layout>
      </body>
    </html>
  );
}
