// src/app/layout.tsx
import type { Metadata, Viewport } from "next";
import "../styles/globals.css";
import Layout from "@/components/layout/Layout";

export const metadata: Metadata = {
  title: {
    default: "Omnia",
    template: "%s | Omnia",
  },
  description: "Omnia alışveriş platformu",
  icons: {
    icon: "/favicon.ico",
  },
  manifest: "/manifest.json",
  robots: { index: true, follow: true },
  openGraph: {
    title: "Omnia",
    description: "Omnia alışveriş platformu",
    type: "website",
    locale: "tr_TR",
  },
};

export const viewport: Viewport = {
  width: "device-width",
  initialScale: 1.0,
  maximumScale: 1.0,
  viewportFit: "cover",
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="tr" data-scroll-behavior="smooth">
      <body
        className="bg-[var(--main-bg)] text-[var(--text)] antialiased overflow-x-hidden min-h-screen flex flex-col"
      >
        <Layout>{children}</Layout>
      </body>
    </html>
  );
}
