import { Product } from "@/types/seller/product";
import ProductCard from "../ProductCard";
import { motion } from "framer-motion";

interface ProductSimilarProps {
  similarProducts?: Product[];
}

export default function ProductSimilar({ similarProducts }: ProductSimilarProps) {
  if (!similarProducts || similarProducts.length === 0) {
    return null;
  }

  return (
    <section className="md:col-span-12 mt-10">
      <h2 className="text-2xl font-semibold text-gray-900 mb-6">Bunları da beğenebilirsiniz</h2>

      <div className="grid gap-6 grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-5">
        {similarProducts.map((product) => {
          return (
            <motion.div
              key={product.id}
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5 }}
            >
              <ProductCard key={product.id} product={product} variant={product.variants[0]} />
            </motion.div>
          )
        })}
      </div>
    </section>
  );
}
