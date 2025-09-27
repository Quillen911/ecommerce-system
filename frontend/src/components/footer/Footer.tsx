import FooterLinks from "./FooterLinks";
import FooterSocial from "./FooterSocial";

export default function Footer() {
  return (
    <footer className="bg-[var(--main-bg)] text-white py-10">
      <div className="border-t border-gray-700 mt-8 pt-10 w-19/20 mx-auto text-center text-sm text-gray-500">

      </div>
      <div className="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
        <FooterLinks />
        <FooterSocial />
        <div>
          <h3 className="font-semibold mb-3">İletişim</h3>
          <p className="text-sm text-gray-400">info@omnia.com</p>
          <p className="text-sm text-gray-400">+90 555 123 4567</p>
        </div>
      </div>
      <div className="mt-8 pt-4 text-center text-sm text-gray-500">
        © {new Date().getFullYear()} Omnia. Tüm hakları saklıdır.
      </div>
    </footer>
  );
}
