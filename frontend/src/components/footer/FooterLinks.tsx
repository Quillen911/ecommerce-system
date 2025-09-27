export default function FooterLinks() {
    return (
      <div>
        <h3 className="font-semibold mb-3">Keşfet</h3>
        <ul className="space-y-2">
          <li><a href="/" className="hover:text-gray-400">Ana Sayfa</a></li>
          <li><a href="/about" className="hover:text-gray-400">Hakkımızda</a></li>
          <li><a href="/products" className="hover:text-gray-400">Ürünler</a></li>
          <li><a href="/contact" className="hover:text-gray-400">İletişim</a></li>
        </ul>
      </div>
    );
  }
  