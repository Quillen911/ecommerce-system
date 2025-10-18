"use client"

interface ProductPriceProps {
  price_cents: number
}

export default function ProductPrice({ price_cents }: ProductPriceProps) {
  const formattedPrice = (price_cents / 100).toLocaleString("tr-TR", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  })

  return (
    <div className="product-price">
      <p className="text-2xl font-semibold text-gray-900">
        ₺{formattedPrice}
      </p>
    </div>
  )
}
