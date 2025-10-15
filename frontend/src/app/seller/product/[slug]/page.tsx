'use client'
import { useParams } from 'next/navigation'
import ProductImage, { ProductCardImage } from '@/components/ui/ProductImage'
import { useShowProductBySlug } from '@/hooks/seller/useProductQuery'
import { useRouter } from 'next/navigation'

export default function ProductDetailPage() {
  const { slug } = useParams()
  const { data: product, isLoading } = useShowProductBySlug(slug as string)
  const router = useRouter()
  if (!product) return <p>Ürün bulunamadı</p>
  if (isLoading) return <p>Yükleniyor...</p>

  return (
    <div className="p-6">
      <button className="bg-[var(--text)] text-white p-3 rounded-3xl absolute top-10 right-10 hover:bg-gray-500 hover:text-black transition-all duration-300" onClick={() => router.back()}>
        Geri
      </button>
      <h1 className="text-2xl font-bold mb-4">{product.title}</h1>
 
      <p className="mb-4 text-muted">{product.description}</p>

      <h2 className="text-xl font-semibold mb-3">Varyantlar</h2>
      <table className="w-full border border-color rounded-lg text-sm">
        <thead className="bg-gray-100">
          <tr>
            <th className="p-2 text-left">SKU</th>
            <th className="p-2">Özellikler</th>
            <th className="p-2">Stok</th>
            <th className="p-2">Fiyat</th>
            <th className="p-2">Görsel</th>
          </tr>
        </thead>
        <tbody>
          {product.variants.map((variant: any) => (
            
            <tr key={variant.id} className="border-t border-color">
              <td className="p-2">{variant.sku}</td>
              <td className="p-2 text-center"> ₺{variant.price_cents/100}</td>
              <td className="p-2">
                <ProductImage 
                  product={variant} 
                  className="w-12 h-12 rounded object-cover" 
                />
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}
