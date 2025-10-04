import { ProductVariant } from "@/types/seller/product"

interface ProductTitleProps {
    title: string
    category: string
    variant: ProductVariant
}

export default function ProductTitle({ title, category, variant }: ProductTitleProps) {
    return (
        <div className="product-title">
            <h1 className="text-[22px] font-semibold font-sans">
                {variant.color_name} {title}
            </h1>
            <h2 className="text-sm text-gray-500 font-sans">{category}</h2>
        </div>
    )
}