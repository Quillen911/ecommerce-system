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
  if (isLoading) return null

  const categories = [
    ...new Map(
      mainData?.categories
        .filter((c) => c.parent_id === null && c.children?.length !== 0)
        .map((c) => [c.slug, c])
    ).values()
  ]

  return (
    <div className="relative flex flex-wrap justify-center items-center gap-6 sm:gap-8 md:gap-10 py-12 sm:py-20 bg-[var(--main-bg)] w-full overflow-hidden">
      <h2 className="w-full text-center text-2xl sm:text-3xl font-sans font-bold text-white mb-10 sm:mb-16">
        KATEGORİLER
      </h2>

      {categories?.map((category) => {
        let imageSrc = '/images/categories/default.png'

        if (category.title === 'Jean') {
          imageSrc = '/images/categories/Jean.png'
        } else if (category.title === 'Eşofman Takım') {
          imageSrc = '/images/categories/EsofmanTakim.png'
        } else if (category.title === 'Keten') {
          imageSrc = '/images/categories/KetenPantolon.png'
        }

        return (
          <Link
            key={category?.slug}
            href={`/${category?.slug}`}
            className="cursor-pointer transition-all duration-300 transform hover:scale-[1.02] flex flex-col items-center"
          >
            <div className="w-[130px] sm:w-[150px] md:w-[180px] h-[160px] sm:h-[200px] md:h-[240px]">
              <Image
                src={imageSrc}
                alt={category?.title}
                width={150}
                height={200}
                className="object-cover w-full h-full rounded-md"
              />
            </div>

            <p className="font-medium text-lg text-white mt-4 sm:mt-6">
              {category?.title}
            </p>
          </Link>
        )
      })}
    </div>
  )
}
