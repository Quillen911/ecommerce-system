"use client"

import { useMainData } from '@/hooks/useMainQuery'
import Link from "next/link"
import PriceFilter from "./PriceFilter"
import { useRouter, useSearchParams } from "next/navigation"
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion"

export default function CategoryFilter({ isOpen, handleOpen, handleClose }: { isOpen: boolean, handleOpen: () => void, handleClose: () => void }) {
  const { data: mainData, isLoading, error } = useMainData()
  const router = useRouter()
  const searchParams = useSearchParams()

  const updateFilter = (key: string, value: string) => {
    const params = new URLSearchParams(searchParams.toString())
    if (value) {
      params.set(key, value)
    } else {
      params.delete(key)
    }
    router.push(`?${params.toString()}`)
  }

  if (error) return null
  if (isLoading) return (
    <div className="min-h-screen">
      <p className="text-center text-2xl font-bold justify-start items-start">Yükleniyor...</p>
    </div>
  )

  const categories = [
    ...new Map(
      mainData?.categories
        .filter((c) => c.parent_id !== null)
        .map((c) => [c.slug, c])
    ).values()
  ]
  console.log(categories)

  return (
    <div className={`space-y-6 overflow-y-auto ${isOpen ? 'block' : 'hidden'}`}>
      <div>
        <div className="flex gap-4">
          <ul className="space-y-1 text-lg px-6">
            {categories.map((cat) => (
              <li key={cat.slug}>
                <Link
                  href={`/${cat.slug}`}
                  className="hover:text-gray-500 font-bold"
                >
                  {cat.title}
                </Link>
              </li>
            ))}
          </ul>
        </div>
      </div>

      {/* Accordion Filtreler */}
      <Accordion type="multiple" className="w-full space-y-2">
        {/* Cinsiyet */}
        <AccordionItem value="gender">
          <AccordionTrigger className="text-lg font-semibold">Cinsiyet</AccordionTrigger>
          <AccordionContent>
            <label className="flex items-center mb-2 space-x-2">
              <input
                type="radio"
                name="gender"
                onChange={() => updateFilter("gender", "Erkek Çocuk")}
                checked={searchParams.get("gender") === "Erkek Çocuk"}
                className="w-5 h-5 text-blue-600"
              />
              <span className="text-base">Erkek Çocuk</span>
            </label>
            <label className="flex items-center space-x-2">
              <input
                type="radio"
                name="gender"
                onChange={() => updateFilter("gender", "Kız Çocuk")}
                checked={searchParams.get("gender") === "Kız Çocuk"}
                className="w-5 h-5 text-pink-600"
              />
              <span className="text-base">Kız Çocuk</span>
            </label>
          </AccordionContent>
        </AccordionItem>


        {/* Çocuk Yaş */}
        <AccordionItem value="childAge">
          <AccordionTrigger className="text-lg font-semibold">Çocuk Yaş</AccordionTrigger>
          <AccordionContent className="space-y-2">
            {["6","7","8","9","10","11","12","13","14","15","16"].map((age) => (
              <label key={age} className="flex items-center">
                <input
                  type="checkbox"
                  className="w-5 h-5 mr-2 rounded-lg"
                  onChange={() => updateFilter("age", `${age}-yas`)}
                  checked={searchParams.get("age") === `${age}-yas`}
                />
                <span className="text-sm">{age} Yaş</span>
              </label>
            ))}
          </AccordionContent>
        </AccordionItem>


        {/* Fiyat */}
        <AccordionItem value="price">
          <AccordionTrigger className="text-lg font-semibold">Fiyata Göre İncele</AccordionTrigger>
          <AccordionContent>
            <PriceFilter searchParams={searchParams} />
          </AccordionContent>
        </AccordionItem>

        {/* Renkler */}
        <AccordionItem value="color">
  <AccordionTrigger className="text-lg font-semibold">Renk</AccordionTrigger>
  <AccordionContent className="grid grid-cols-3 gap-3 p-2">
    {[
      { slug: "siyah", value: "Siyah", color: "bg-black" },
      { slug: "mavi", value: "Mavi", color: "bg-blue-500" },
      { slug: "kahverengi", value: "Kahverengi", color: "bg-amber-800" },
      { slug: "yesil", value: "Yeşil", color: "bg-green-500" },
      { slug: "gri", value: "Gri", color: "bg-gray-500" },
      { slug: "turuncu", value: "Turuncu", color: "bg-orange-500" },
      { slug: "pembe", value: "Pembe", color: "bg-pink-500" },
      { slug: "mor", value: "Mor", color: "bg-purple-500" },
      { slug: "kirmizi", value: "Kırmızı", color: "bg-red-500" },
      { slug: "beyaz", value: "Beyaz", color: "bg-white border" },
      { slug: "sari", value: "Sarı", color: "bg-yellow-400" },
    ].map((clr) => {
      const selected = (searchParams.get("color") || "").split(",").includes(clr.slug)

      const toggleColor = () => {
        const current = (searchParams.get("color") || "").split(",").filter(Boolean)
        let updated: string[]
        if (selected) {
          updated = current.filter((c) => c !== clr.slug) // kaldır
        } else {
          updated = [...current, clr.slug] // ekle
        }
        updateFilter("color", updated.join(","))
      }

      return (
        <div
          key={clr.slug}
          onClick={toggleColor}
          className={`flex flex-col items-center cursor-pointer`}
        >
          <span
            className={`w-8 h-8 rounded-full border-2 flex items-center justify-center ${clr.color} ${
              selected ? "ring-2 ring-offset-2 ring-black" : ""
            }`}
          >
            {selected && <span className="w-3 h-3 bg-white rounded-full" />}
          </span>
          <span className="text-xs mt-1">{clr.value}</span>
        </div>
      )
    })}
  </AccordionContent>
</AccordionItem>



        {/* İndirimler */}
        <AccordionItem value="discount">
          <AccordionTrigger className="text-lg font-semibold">İndirimler ve Fırsatlar</AccordionTrigger>
          <AccordionContent>
            <label className="block"><input type="checkbox" /> Sadece indirimli ürünler</label>
          </AccordionContent>
        </AccordionItem>
      </Accordion>
    </div>
  )
}
