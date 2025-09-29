"use client"
import { useState } from "react"
import * as Slider from "@radix-ui/react-slider"
import { useRouter } from "next/navigation"

export default function PriceFilter({ searchParams }: { searchParams: URLSearchParams }) {
  const router = useRouter()
  const [value, setValue] = useState<[number, number]>([
    Number(searchParams.get("min_price")) || 0,
    Number(searchParams.get("max_price")) || 1000,
  ])

  const handleCommit = (newValue: [number, number]) => {
    const params = new URLSearchParams(searchParams.toString())
    params.set("min_price", newValue[0].toString())
    params.set("max_price", newValue[1].toString())
    router.push(`?${params.toString()}`)
  }

  return (
    <div className="space-y-2 px-4">
      <div className="text-sm font-medium">
        {value[0]} ₺ - {value[1]} ₺
      </div>
      <Slider.Root
        className="relative flex items-center select-none touch-none w-full h-5"
        min={0}
        max={2000}
        step={50}
        value={value}
        onValueChange={(val: number[]) => setValue(val as [number, number])}
        onValueCommit={(val: number[]) => handleCommit(val as [number, number])}
      >
        <Slider.Track className="bg-gray-200 relative grow rounded-full h-[4px]">
          <Slider.Range className="absolute bg-blue-500 rounded-full h-full" />
        </Slider.Track>
        <Slider.Thumb className="block w-4 h-4 bg-white border-2 border-blue-500 rounded-full shadow hover:bg-blue-100 focus:outline-none" />
        <Slider.Thumb className="block w-4 h-4 bg-white border-2 border-blue-500 rounded-full shadow hover:bg-blue-100 focus:outline-none" />
      </Slider.Root>
    </div>
  )
}
