import { ProductVariant } from "@/types/seller/product"
import { Category } from "@/types/seller/category"

interface ProductTitleProps {
    title: string
    category: Category
    variant: ProductVariant
}

export default function ProductTitle({ title, category, variant }: ProductTitleProps) {
  const genderTitle = category.gender?.title
  return (
    <div className="product-title">
      <h1 className="text-[22px] font-semibold font-sans">
        {variant.color_name} {title}
      </h1>
      <h2 className="text-sm text-gray-500 font-sans">
        {genderTitle} {category.title}
      </h2>
    </div>
  )
}