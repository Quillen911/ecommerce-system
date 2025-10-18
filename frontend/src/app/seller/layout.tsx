import ConditionalSideBar from "./conditionalSideBar"

export const metadata = {
  title: "Seller Panel",
  description: "Satıcı yönetim paneli — ürün, sipariş ve kampanya işlemlerinizi buradan yönetin.",
}

export default function SellerRootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return <ConditionalSideBar>{children}</ConditionalSideBar>
}
