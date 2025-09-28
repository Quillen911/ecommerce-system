'use client'

import { useParams } from "next/navigation";
import { useCategoryProducts } from "@/hooks/useSearchQuery";
import { Product } from "@/types/main";

export default function CategoryPage() {
  const { category } = useParams();
  const { data: filteredProducts, isLoading } = useCategoryProducts(category as string);
  
  const categoryProducts = filteredProducts?.products.flatMap((product: Product) =>
    product.variants.map((variant) => {
        const primaryImage =
          variant.images.find((img) => img.is_primary)?.image ||
          variant.images[0]?.image ||
          "/images/no-image.png"
        return {
            id: variant.id,
            title: product.title,
            category: product.category.title,
            list_price: variant.price,
            image: primaryImage,
            images: variant.images,
        }
    })
  ) ?? [];
console.log(categoryProducts);

  return <div>
    <h1 className="text-2xl font-bold mb-4">{category}</h1>
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      {categoryProducts.map((product: any) => (
        <div key={product.id}>
          <h2 className="text-lg font-bold mb-2">{product.title}</h2>
          <p className="text-sm text-gray-500 mb-2">{product.category}</p>
          <p className="text-sm text-gray-500 mb-2">{product.list_price}</p>
          <img src={product.image} alt={product.title} className="w-full h-full object-cover" />
        </div>
      ))}
    </div>
  </div>;
}
