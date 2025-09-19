"use client"

import { useState } from "react"
import { useSearchParams, useRouter } from "next/navigation"
import { useSearchQuery } from "@/hooks/useSearchQuery"

export default function SearchPage() {
  const searchParams = useSearchParams()
  const router = useRouter()

  const query = searchParams.get("q") || ""
  const initialMinPrice = searchParams.get("min_price") || ""
  const initialMaxPrice = searchParams.get("max_price") || ""
  const initialSort = searchParams.get("sorting") || ""

  const [min_price, setMinPrice] = useState(initialMinPrice)
  const [max_price, setMaxPrice] = useState(initialMaxPrice)
  const [sorting, setSort] = useState(initialSort)

  const { data, isLoading } = useSearchQuery(
    query,
    { min_price: min_price, max_price: max_price },
    sorting,
    1,
    12
  )

  const handleFilterApply = () => {
    const params = new URLSearchParams()
    if (query) params.set("q", query)
    if (min_price) params.set("min_price", min_price)
    if (max_price) params.set("max_price", max_price)
    if (sorting) params.set("sort", sorting)

    router.push(`/search?${params.toString()}`)
  }

  const products = data?.data?.products || []

  return (
    <div className="flex p-10 gap-8">
      {/* Sol filtre paneli */}
      <aside className="w-64 bg-white rounded shadow p-4 h-fit sticky top-4">
        <h3 className="text-lg font-bold mb-4">Filtreler</h3>

        {/* Fiyat aralığı */}
        <div className="mb-4">
          <label className="block text-sm mb-1">Min Fiyat</label>
          <input
            type="number"
            value={min_price}
            onChange={(e) => setMinPrice(e.target.value)}
            className="w-full border rounded px-2 py-1"
          />
        </div>
        <div className="mb-4">
          <label className="block text-sm mb-1">Max Fiyat</label>
          <input
            type="number"
            value={max_price}
            onChange={(e) => setMaxPrice(e.target.value)}
            className="w-full border rounded px-2 py-1"
          />
        </div>

        {/* Sıralama */}
        <div className="mb-4">
          <label className="block text-sm mb-1">Sırala</label>
          <select
            value={sorting}
            onChange={(e) => setSort(e.target.value)}
            className="w-full border rounded px-2 py-1"
          >
            <option value="">Varsayılan</option>
            <option value="price_asc">Fiyat: Düşükten Yükseğe</option>
            <option value="price_desc">Fiyat: Yüksekten Düşüğe</option>
            <option value="newest">En Yeniler</option>
          </select>
        </div>

        <button
          onClick={handleFilterApply}
          className="w-full bg-[var(--sand)] text-white py-2 rounded hover:bg-[var(--stone)] transition"
        >
          Uygula
        </button>
      </aside>

      {/* Sağ taraf: ürünler */}
      <main className="flex-1">
        <h2 className="text-xl font-bold mb-4">
          “{query}” için arama sonuçları
        </h2>

        {isLoading ? (
          <p>Yükleniyor...</p>
        ) : products.length === 0 ? (
          <p>Ürün bulunamadı</p>
        ) : (
          <div className="grid grid-cols-2 md:grid-cols-4 gap-15 items-center px-10">
            {products.map((p: any) => (
              <div key={p.id} className="border rounded p-3 shadow hover:shadow-lg transition">
                <img
                  src={p.images[0]}
                  alt={p.title}
                  className="w-full h-40 object-cover mb-2 rounded"
                />
                <h3 className="font-semibold">{p.title}</h3>
                <p className="text-green-600 font-bold">{p.list_price} ₺</p>
              </div>
            ))}
          </div>
        )}
      </main>
    </div>
  )
}
