interface ProductAddtoBagProps {
    variantId: number
}

export default function ProductAddtoBag({ variantId }: ProductAddtoBagProps) {
    return (
        <div className="product-addtobag">
            <button>Add to Bag</button>
        </div>
    )
}