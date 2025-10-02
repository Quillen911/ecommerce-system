interface ProductAddtoBagProps {
    variantId: number
}

export default function ProductAddtoBag({ variantId }: ProductAddtoBagProps) {
    return (
        <div className="product-addtobag">
            <button className="bg-black text-white px-4 py-2 rounded-md cursor-pointer">Sepete Ekle</button>
        </div>
    )
}