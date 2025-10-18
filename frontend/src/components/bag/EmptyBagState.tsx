import Link from "next/link"

export function EmptyBagState() {
  return (
    <div className="min-h-screen p-6 bg-[var(--bg)] flex flex-col items-center justify-center">
      <div className="surface border border-dashed border-color rounded-2xl shadow-md p-6 sm:p-10 w-full max-w-sm sm:max-w-md text-center animate-fadeIn">
        <h2 className="text-xl sm:text-2xl font-bold mb-3">Sepetiniz Boş</h2>
        <p className="text-gray-500 mb-6 text-xs sm:text-sm px-2">
          Henüz ürün eklemediniz. Şimdi keşfetmeye başlayın.
        </p>
        <Link
          href="/"
          className="inline-block bg-[var(--stone)] text-white px-6 sm:px-8 py-2 sm:py-3 rounded-lg font-semibold hover:bg-[var(--accent-dark)] transition-colors duration-200 text-sm sm:text-base"
        >
          Alışverişe Başla
        </Link>
      </div>
    </div>
  )
}
