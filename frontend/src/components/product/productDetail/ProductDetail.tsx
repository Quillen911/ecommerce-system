import { Product, ProductVariant } from "@/types/main";
import ProductGallery from "./ProductGallery";
import ProductPrice from "./ProductPrice";
import ProductTitle from "./ProductTitle";
import ProductVariants from "./ProductVariants";
import ProductAddtoBag from "./ProductAddtoBag";

interface ProductDetailProps {
    product: Product;
    variant: ProductVariant
}

const ProductDetail = ({ product, variant }: ProductDetailProps) => {
    return (
      <div className="product-detail grid grid-cols-12 gap-8">
        <div className="col-span-6">
          <ProductGallery images={variant.images} />
        </div>
        <div className="col-span-6 flex flex-col gap-4">
          <ProductTitle title={product.title} />
          <ProductPrice price={variant.price} />
          <ProductVariants product={product} variants={product.variants} />
          <ProductAddtoBag variantId={product.variants[0]?.id} />
        </div>

      </div>
    );
  };
  
  export default ProductDetail;