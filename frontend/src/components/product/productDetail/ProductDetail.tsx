import { Product, ProductVariant } from "@/types/main";
import ProductGallery from "./ProductGallery";
import ProductPrice from "./ProductPrice";
import ProductTitle from "./ProductTitle";
import ProductVariants from "./ProductVariants";
import ProductAddtoBag from "./ProductAddtoBag";
import ProductSizeSelector from "./ProductSizeSelector";

interface ProductDetailProps {
  product: Product;
  variant: ProductVariant;
  allVariants: {
    id: number;
    slug: string;
    thumbnail: string;
  }[];
}

const ProductDetail = ({ product, variant, allVariants }: ProductDetailProps) => {
  return (
    <div className="product-detail grid grid-cols-1 md:grid-cols-12 gap-8">
      <div className="md:col-span-5">
        <ProductGallery images={variant.images} />
      </div>

      <div className="md:col-span-1"></div>

      <div className="md:col-span-6 flex flex-col gap-4">
        <ProductTitle
          title={product.title}
          category={
            product.category.parent?.title + " " + product.category.title
          }
          variant={variant}
        />

        <ProductPrice price={variant.price} />

        <ProductVariants product={product} variants={allVariants} />

        <ProductAddtoBag variantId={variant.id} />

          <div className="md:col-span-6 flex flex-row">
            <ProductSizeSelector
              product={product}
              variants={allVariants}
            />
        </div>
      </div>
    </div>
  );
};

export default ProductDetail;
