import { Product } from "@/types/seller/product"
import { useParams, useRouter } from "next/navigation"

interface VariantSummary {
    id: number
    slug: string
    thumbnail: string
  }
  
  interface ProductVariantsProps {
    product: Product
    variants: VariantSummary[]
  }
  
  export default function ProductVariants({ product, variants }: ProductVariantsProps) {
    const router = useRouter()
    const params = useParams()  
    const handleVariantClick = (slug: string) => {
        router.push(`/product/${slug}`)
    }
    return (
      <div className="product-variants">
        <h2 className="text-md text-black mb-3 font-semibold font-sans">{product.variants[0].color_name}</h2>
  
        <div className="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3">
          {variants.map((variant) => {
  
            return (
              <button
                key={variant.id}
                onClick={() => handleVariantClick(variant.slug)}
                className={`rounded-md p-1 flex items-center justify-center cursor-pointer transition border-2 border-gray-200
                  ${params.slug === variant.slug ? "border-blue-500 ring-2 ring-black" : "hover:border-gray-400"}`}
              >
                <img
                  src={variant.thumbnail}
                  alt={variant.slug}
                  className="object-contain w-16 h-16"
                />
              </button>
            )
          })}
        </div>
      </div>
    )
  }
  
