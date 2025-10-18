import FooterLinks from "./FooterLinks";
import FooterSocial from "./FooterSocial";

export default function Footer() {
  return (
    <footer className="bg-[var(--main-bg)] text-white py-10">
      <div className="w-[95%] mx-auto border-t border-gray-700 mb-8"></div>

      <div className="container mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10 text-center sm:text-left">
        <FooterLinks />

        <FooterSocial />

        <div>
          <h3 className="font-semibold mb-3 text-lg">İletişim</h3>
          <p className="text-sm text-gray-400 break-words">info@omnia.com</p>
          <p className="text-sm text-gray-400 mt-1">+90 555 123 4567</p>
        </div>
      </div>

      <div className="mt-10 pt-4 text-center text-sm text-gray-500 border-t border-gray-800 w-[90%] mx-auto">
        © {new Date().getFullYear()} Omnia. Tüm hakları saklıdır.
      </div>
    </footer>
  );
}
