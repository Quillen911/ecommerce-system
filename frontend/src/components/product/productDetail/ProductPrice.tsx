interface ProductPriceProps {
    price_cents: number
}

export default function ProductPrice({ price_cents }: ProductPriceProps) {
    return (
        <div className="product-price">
            <p className="text-xl font-bold font-sans">₺{price_cents / 100}</p>
        </div>
    )
}