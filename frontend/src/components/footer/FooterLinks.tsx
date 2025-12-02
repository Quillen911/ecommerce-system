export default function FooterLinks() {
  return (
    <div className="text-center sm:text-left">
      <h3 className="font-semibold mb-3 text-lg text-black">Keşfet</h3>
      <ul className="space-y-2">
        <li>
          <a href="/" className="hover:text-gray-400 block">
            Ana Sayfa
          </a>
        </li>
        <li>
          <a href="/about" className="hover:text-gray-400 block">
            Hakkımızda
          </a>
        </li>
        <li>
          <a href="/products" className="hover:text-gray-400 block">
            Ürünler
          </a>
        </li>
        <li>
          <a href="/contact" className="hover:text-gray-400 block">
            İletişim
          </a>
        </li>
      </ul>
    </div>
  );
}
