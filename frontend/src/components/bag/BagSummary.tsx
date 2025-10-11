interface BagSummaryProps {
  total?: number
  discount?: number
  cargoPrice?: number
  finalPrice?: number
  onCheckout?: () => void
}

export function BagSummary({
  total = 0,
  discount = 0,
  cargoPrice = 0,
  finalPrice = 0,
  onCheckout,
}: BagSummaryProps) {
  return (
    <div className="surface p-6 rounded-lg shadow-md border border-color h-fit animate-fadeInUp">
      <h2 className="text-xl font-semibold mb-4">Sepet Özeti</h2>
      <div className="space-y-2 text-sm">
        <div className="flex justify-between">
          <span>Ürün Toplamı</span>
          <span>{(total / 100).toFixed(2)} ₺</span>
        </div>
        <div className="flex justify-between">
          <span>İndirim</span>
          <span className="text-green-600">- {(discount / 100).toFixed(2)} ₺</span>
        </div>
        <div className="flex justify-between">
          <span>Kargo</span>
          <span>{(cargoPrice / 100).toFixed(2)} ₺</span>
        </div>
        <hr className="my-3 border-color" />
        <div className="flex justify-between font-bold text-lg">
          <span>Ödenecek Tutar</span>
          <span>{(finalPrice / 100).toFixed(2)} ₺</span>
        </div>
      </div>
      <button
        className="w-full mt-6 py-3 bg-[var(--accent)] text-white rounded-lg font-semibold hover:bg-[var(--accent-dark)] transition disabled:opacity-60"
        onClick={onCheckout}
        disabled={!onCheckout}
      >
        Alışverişi Tamamla
      </button>
    </div>
  )
}
