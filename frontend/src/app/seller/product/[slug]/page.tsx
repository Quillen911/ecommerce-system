'use client'
import { useParams } from 'next/navigation'
import ProductImage  from '@/components/ui/ProductImage'
import { useShowProductBySlug } from '@/hooks/seller/useProductQuery'

export default function ProductDetailPage() {
  const { slug } = useParams()
  const { data: product, isLoading } = useShowProductBySlug(slug as string)

  if (!product) return <p>Ürün bulunamadı</p>
  if (isLoading) return <p>Yükleniyor...</p>

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">{product.title}</h1>
      <ProductImage 
        product={product} 
        index={0}
        alt={product.title} 
        className="w-48 h-48 rounded mb-4" 
      /> 
      <p className="mb-4 text-muted">{product.description}</p>
      <p className="font-medium">Liste Fiyatı: {product.list_price} ₺</p>
      <p className="mb-6">Toplam Stok: {product.stock_quantity}</p>

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
              <td className="p-2">{variant.attributes.map((a: any) => `${a.name}: ${a.value}`).join(', ')}</td>
              <td className="p-2 text-center">{variant.stock_quantity}</td>
              <td className="p-2 text-center">{variant.price} ₺</td>
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
