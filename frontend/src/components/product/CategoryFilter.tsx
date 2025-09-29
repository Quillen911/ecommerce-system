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

export default function CategoryFilter({ isOpen }: { isOpen: boolean, handleOpen: () => void, handleClose: () => void }) {
  const { data: mainData, isLoading, error } = useMainData()
  const router = useRouter()
  const searchParams = useSearchParams()

  const updateFilter = (key: string, value: string, multi = false) => {
    const params = new URLSearchParams(searchParams.toString())
    if(multi) {
      const current = (searchParams.get(key) || "").split(",").filter(Boolean)
      let updated: string[]
      if (current.includes(value)) {
        updated = current.filter((c) => c !== value)
      } else {
        updated = [...current, value]
      }

      if (updated.length > 0) {
        params.set(key, updated.join(","))
      } else {
        params.delete(key)
      }
    } else {
      if (value) {
        params.set(key, value)
      } else {
        params.delete(key)
      }
    }
    router.push(`?${params.toString()}`)
  }

  if (isLoading) return (
    <div className="min-h-screen">
      <p className="text-center text-2xl font-bold justify-start items-start">Yükleniyor...</p>
    </div>
  )
  if (error) return null
  
  const categories = [
    ...new Map(
      mainData?.categories
        .filter((c) => c.parent_id !== null)
        .map((c) => [c.slug, c])
    ).values()
  ]

  return (
    <div
      className={`space-y-6 overflow-y-auto overflow-x-hidden max-h-[calc(100vh-100px)] ${
        isOpen ? "block" : "hidden"
      }`}
    >
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
        <div className="border-b border-black my-4"></div>
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
          {["6","7","8","9","10","11","12","13","14","15","16"].map((age) => {
            const slug = `${age}-yas`
            const selected = (searchParams.get("age") || "")
              .split(",")
              .includes(slug)

            return (
              <label key={age} className="flex items-center">
                <input
                  type="checkbox"
                  className="w-5 h-5 mr-2 rounded-lg"
                  onChange={() => updateFilter("age", slug, true)}
                  checked={selected}
                />
                <span className="text-sm">{age} Yaş</span>
              </label>
            )
          })}
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
              { slug: "yesil", value: "Yeşil", color: "bg-green-500" },
              { slug: "kirmizi", value: "Kırmızı", color: "bg-red-500" },
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
