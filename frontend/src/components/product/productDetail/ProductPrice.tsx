interface ProductPriceProps {
    price: number
}

export default function ProductPrice({ price }: ProductPriceProps) {
    return (
        <div className="product-price">
            <p>{price} ₺</p>
        </div>
    )
}