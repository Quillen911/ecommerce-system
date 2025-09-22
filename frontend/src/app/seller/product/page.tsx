'use client'
import { useIndexProduct, useStoreProduct, useUpdateProduct, useDestroyProduct } from '@/hooks/seller/useProductQuery'
import { useMySeller } from '@/hooks/seller/useSellerAuthQuery'
import { useRouter } from 'next/navigation'
export default function ProductPage() {
    const { data: me } = useMySeller()
    const { data: products, isLoading: isLoadingProducts } = useIndexProduct(me?.id)
    const { mutate: updateProduct } = useUpdateProduct(me?.id!)
    const { mutate: destroyProduct } = useDestroyProduct(me?.id!)

    const product = products?.data
    const router = useRouter()

    const handleProductDetail = (slug: string) => {
        router.push(`/seller/product/${slug}`)
    }
    if (isLoadingProducts) return <p className="text-center text-muted">Yükleniyor...</p>


    return (
        <div className="p-6">
          <h1 className="text-2xl font-bold mb-6">Ürün Yönetimi</h1>
          
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 cursor-pointer">
            {product?.map((p) => (
              <div
                key={p.id}
                onClick={() => handleProductDetail(p.slug)}
                className="surface border border-color rounded-xl shadow-sm p-4 flex flex-col gap-3 transition hover:shadow-md hover:scale-[1.01]"
              >
                {/* Başlık */}
                <h2 className="font-semibold text-lg text-accent-dark line-clamp-2">{p.title}</h2>
                <p className="text-sm muted line-clamp-3">{p.description}</p>
    
                {/* Fiyat & Stok */}
                <div className="flex justify-between items-center text-sm border-t border-color pt-2">
                  <span className="font-medium">{p.list_price} ₺</span>
                  <span className={p.stock_quantity > 0 ? "text-green-600" : "text-danger"}>
                    {p.stock_quantity > 0 ? `${p.stock_quantity} stok` : "Tükendi"}
                  </span>
                </div>
    
                {/* Varyantlar */}
                {p.variants.length > 0 && (
                  <div className="text-xs border-t border-color pt-2 space-y-1">
                    <p><span className="font-medium">Varyantlar:</span> {p.variants.map(v => v.attributes.map(a => a.value).join(' ')).join(', ')}</p>
                    <p><span className="font-medium">Stoklar:</span> {p.variants.map(v => v.stock_quantity).join(', ')}</p>
                    <p><span className="font-medium">Fiyatlar:</span> {p.variants.map(v => v.price).join(', ')} ₺</p>
                  </div>
                )}
    
                {/* Aksiyonlar */}
                <div className="flex gap-2 mt-auto pt-3">
                  <button
                    className="flex-1 px-3 py-2 text-sm rounded-lg bg-accent text-white hover:bg-blue-700 transition"
                  >
                    Güncelle
                  </button>
                  <button
                    className="flex-1 px-3 py-2 text-sm rounded-lg bg-danger text-white hover:bg-red-800 transition"
                  >
                    Sil
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>
      )
}
