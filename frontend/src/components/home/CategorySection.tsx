'use client'
import { useMainData } from '@/hooks/useMainQuery'
import Link from 'next/link'
import Image from 'next/image'

export interface CategoryProps {
  className?: string
}

export default function CategorySection({ className }: CategoryProps) {
  const { data: mainData, isLoading, error } = useMainData()
  if (error) return null
  if (isLoading)
    return (
      <div className="flex items-center justify-center min-h-screen bg-[var(--bg)]">
        <p className="text-lg sm:text-xl font-semibold text-gray-900 mb-2 animate-pulse">
          Yükleniyor…
        </p>
      </div>
    )

  const categories = [
    ...new Map(
      mainData?.categories
        .filter((c) => c.parent_id === null && c.children?.length !== 0)
        .map((c) => [c.slug, c])
    ).values()
  ]

  return (
    <div className="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 justify-items-center gap-6 sm:gap-8 md:gap-10 px-4 sm:px-8 py-2 sm:py-5 bg-white w-full overflow-hidden">
      {categories?.map((category) => {
        let imageSrc = '/images/categories/default.png'
        if (category.title === 'Jean') imageSrc = '/images/categories/1.png'
        else if (category.title === 'Eşofman Takım') imageSrc = '/images/categories/2.png'
        else if (category.title === 'Keten') imageSrc = '/images/categories/3.png'

        return (
          <Link
            key={category?.slug}
            href={`/${category?.slug}`}
            className="group cursor-pointer transition-transform duration-300 hover:scale-[1.02] hover:shadow-lg flex flex-col items-center"
          >
            <div className="relative w-[280px] sm:w-[320px] md:w-[360px] aspect-[3/4] overflow-hidden rounded-xl">
              <Image
                src={imageSrc}
                alt={category?.title}
                fill
                sizes="(min-width:1024px) 360px, (min-width:640px) 320px, 280px"
                className="object-cover transition-transform duration-300 group-hover:scale-105 group-active:scale-110"
              />
              <div className="absolute bottom-3 left-1/2 -translate-x-1/2 bg-black/40 text-white px-3 py-1 rounded-md backdrop-blur-sm">
                <p className="text-lg font-semibold text-center">{category?.title}</p>
              </div>
            </div>
          </Link>
        )
      })}
    </div>
  )
}