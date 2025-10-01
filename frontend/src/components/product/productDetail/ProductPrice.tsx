interface ProductPriceProps {
    price: number
}

export default function ProductPrice({ price }: ProductPriceProps) {
    return (
        <div className="product-price">
            <p className="text-xl font-bold font-sans">₺{price}</p>
        </div>
    )
}