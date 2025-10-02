import { Product } from "@/types/main"
import { useParams } from "next/navigation"
import { useState } from "react"

interface VariantSummary {
    id: number
    slug: string
    thumbnail: string
}
interface ProductSizeSelectorProps {
    product: Product
    variants: VariantSummary[]
}

export default function ProductSizeSelector({ product, variants }: ProductSizeSelectorProps) {
    const params = useParams()
    const [selectedAge, setSelectedAge] = useState<string | null>(null)
    const ages = product.variants[0].attributes
        .filter(a => a.code === "age")
        .map(a => a.value) || []

    const handleAgeClick = (age: string) => {
        console.log(age)
        setSelectedAge(age)
    }

    return (
        <div className="product-size-selector">
            <h1 className="text-md text-black mb-3 font-semibold font-sans">Yaş Seçenekleri</h1>
            <div className="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-3 gap-2">
                {ages.map((age) => (
                    <button 
                        key={age} 
                        className="border border-gray-400 font-semibold font-sans py-3 px-10 rounded hover:border-black hover:bg-gray-200 cursor-pointer"
                        onClick={() => handleAgeClick(age)}
                        style={{
                            backgroundColor: selectedAge === age ? "black" : "white",
                            color: selectedAge === age ? "white" : "black",
                        }}
                        >
                        {age}
                    </button>
                ))}
            </div>
        </div>
    )
}