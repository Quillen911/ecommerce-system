import { useMemo, useState } from "react";
import { Product, ProductVariant } from "@/types/seller/product";
import ProductGallery from "./ProductGallery";
import ProductPrice from "./ProductPrice";
import ProductTitle from "./ProductTitle";
import ProductVariants from "./ProductVariants";
import ProductAddtoBag from "./ProductAddtoBag";
import ProductSizeSelector from "./ProductSizeSelector";
import ProductSimilar from "./ProductSimilar";

interface ProductDetailProps {
  product: Product;
  variant: ProductVariant;
  allVariants: {
    id: number;
    slug: string;
    thumbnail: string;
  }[];
  similarProducts?: Product[];
}

const FALLBACK_IMAGE = "/images/placeholder-product.png";

const ProductDetail = ({ product, variant, allVariants, similarProducts }: ProductDetailProps) => {
  const [selectedSizeId, setSelectedSizeId] = useState<number | null>(null);

  const galleryImages = useMemo(
    () =>
      (variant.images ?? []).map(({ id, image, is_primary, sort_order }) => ({
        id,
        image: image ?? FALLBACK_IMAGE,
        is_primary: Boolean(is_primary),
        sort_order: sort_order ?? 0,
      })),
    [variant.images],
  );

  return (
    <div className="product-detail grid grid-cols-1 gap-8 md:grid-cols-12">
      <div className="md:col-span-5">
        <ProductGallery images={galleryImages} />
      </div>

      <div className="md:col-span-1" />

      <div className="md:col-span-6 flex flex-col gap-5">
        <ProductTitle title={product.title} category={product.category} variant={variant} />

        <ProductPrice price_cents={variant.price_cents} />

        <ProductVariants product={product} variants={allVariants} />

        <div className="flex flex-col gap-3">
          <ProductSizeSelector
            product={product}
            variants={product.variants}
            onSizeSelect={(variantSizeId) => setSelectedSizeId(variantSizeId)}
          />

          <ProductAddtoBag variantSizeId={selectedSizeId} />
        </div>
      </div>
      <ProductSimilar similarProducts={similarProducts} />
    </div>
  );
};

export default ProductDetail;
