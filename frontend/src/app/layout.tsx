import type { Metadata } from "next";
import "../styles/globals.css";
import Layout from "@/components/layout/Layout";

export const metadata: Metadata = {
  title: "Omnia",
  description: "Omnia",
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return <Layout>{children}</Layout>;
}
