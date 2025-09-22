import ConditionalSideBar from "../conditionalSideBar";

export const metadata = {
  title: "Seller Login",
};

export default function LoginRootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return <ConditionalSideBar>{children}</ConditionalSideBar>
}
