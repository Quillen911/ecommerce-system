import { Product, ProductVariant } from "@/types/main"

interface ProductVariantsProps {
    product: Product
    variants: ProductVariant[]
}

export default function ProductVariants({ product, variants }: ProductVariantsProps) {
    return (
        <div className="product-variants">
            <h1>{product.title}</h1>
            <h2>{variants.length} variants</h2>
        </div>
    )
}