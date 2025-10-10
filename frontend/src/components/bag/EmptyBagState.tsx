import Link from "next/link"

export function EmptyBagState() {
  return (
    <div className="min-h-screen p-6 bg-[var(--bg)] flex flex-col items-center justify-center">
      <div className="surface border border-dashed border-color rounded-2xl shadow-md p-10 max-w-md text-center animate-fadeIn">
        <h2 className="text-2xl font-bold mb-3">Sepetiniz Boş</h2>
        <p className="text-gray-500 mb-6 text-sm">
          Henüz ürün eklemediniz. Şimdi keşfetmeye başlayın.
        </p>
        <Link
          href="/"
          className="inline-block bg-[var(--stone)] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[var(--accent-dark)] transition-colors duration-200"
        >
          Alışverişe Başla
        </Link>
      </div>
    </div>
  )
}
