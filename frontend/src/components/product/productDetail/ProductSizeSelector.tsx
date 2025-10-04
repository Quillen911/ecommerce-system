import { Product, ProductVariant } from "@/types/seller/product"
import { useParams } from "next/navigation"
import { useState } from "react"

interface ProductSizeSelectorProps {
    product: Product
    variants: ProductVariant[]
}

export default function ProductSizeSelector({ product, variants }: ProductSizeSelectorProps) {
    const params = useParams()
    const [selectedSize, setSelectedSize] = useState<string | null>(null)
    const sizes = product.variants.flatMap((variant) => 
        variant.sizes.map((size) => {
            return {
                id: size.size_option.id,
                slug: size.size_option.slug,
                value: size.size_option.value,
                available: size.inventory?.available > 0
            }
        })
    )
    const uniqueSizes = sizes.filter((size, index, self) =>
        index === self.findIndex((s) => s.slug === size.slug)
    )

    const handleSizeClick = (size: string) => {
        setSelectedSize(size)
    }

    return (
        <div className="product-size-selector">
            <h1 className="text-md text-black mb-3 font-semibold font-sans">Yaş Seçenekleri</h1>
            <div className="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-3 gap-2">
                {uniqueSizes.map((size) => (
                    <button 
                        key={size.slug} 
                        className="border border-gray-400 font-semibold font-sans py-3 px-10 rounded hover:border-black hover:bg-gray-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                        onClick={() => handleSizeClick(size.value)}
                        disabled={!size.available}
                        style={{
                            backgroundColor: selectedSize === size.value ? "black" : "white",
                            color: selectedSize === size.value ? "white" : "black",
                        }}
                    >
                        {size.value}
                    </button>
                ))}
            </div>
        </div>
    )
}