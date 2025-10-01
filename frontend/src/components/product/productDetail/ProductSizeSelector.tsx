import { Product, ProductVariant } from "@/types/main"

interface ProductSizeSelectorProps {
    product: Product
    variants: ProductVariant[]
}

export default function ProductSizeSelector({ product, variants }: ProductSizeSelectorProps) {
    return (
        <div>
            <h1>Product Size Selector</h1>
        </div>
    )
}