import { Product, ProductVariant } from "@/types/seller/product";
import { useState } from "react";

interface ProductSizeSelectorProps {
  product: Product;
  variants: ProductVariant[];
  onSizeSelect: (variantSizeId: number) => void;
}

export default function ProductSizeSelector({
  product,
  variants,
  onSizeSelect,
}: ProductSizeSelectorProps) {
  const [selectedSizeId, setSelectedSizeId] = useState<number | null>(null);

  const sizeOptions = variants.flatMap((variant) =>
    variant.sizes.map((size) => ({
      variantId: variant.id,
      variantSizeId: size.id,
      sizeOptionId: size.size_option.id,
      label: size.size_option.value,
      slug: size.size_option.slug,
      available: (size.inventory?.available ?? 0) > 0,
    }))
  );

  const uniqueSizes = Array.from(
    new Map(
      sizeOptions
        .sort((a, b) => Number(b.available) - Number(a.available))
        .map((item) => [item.slug, item])
    ).values()
  );

  const handleSizeClick = (variantSizeId: number) => {
    setSelectedSizeId(variantSizeId);
    onSizeSelect(variantSizeId);
  };

  return (
    <div className="product-size-selector">
      <h1 className="text-md text-black mb-3 font-semibold font-sans">
        Yaş Seçenekleri
      </h1>
      <div className="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-3 gap-2">
        {uniqueSizes.map((size) => (
          <button
            key={size.variantSizeId}
            className="border border-gray-400 font-semibold font-sans py-3 px-10 rounded hover:border-black hover:bg-gray-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
            onClick={() => handleSizeClick(size.variantSizeId)}
            disabled={!size.available}
            style={{
              backgroundColor:
                selectedSizeId === size.variantSizeId ? "black" : "white",
              color:
                selectedSizeId === size.variantSizeId ? "white" : "black",
            }}
          >
            {size.label}
          </button>
        ))}
      </div>
    </div>
  );
}
