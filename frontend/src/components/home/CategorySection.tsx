'use client'
import { useMainData } from '@/hooks/useMainQuery'
import Link from 'next/link'
import Image from 'next/image'

export default function CategorySection() {
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
        <div className='relative flex gap-5 justify-center items-center h-300 py-45 bg-var(--main-bg)'>
            <h2 className="absolute top-1 left-42 transform -translate-x-1/3 text-2xl font-sans font-bold text-white mt-25">
                KATEGORİLER
            </h2>

            {categories?.map((category) => {

                let imageSrc = "/images/categories/default.png"

                if (category.title === "Jean") {
                imageSrc = "/images/categories/Jean.png"
                } else if (category.title === "Eşofman Takım") {
                imageSrc = "/images/categories/EsofmanTakim.png"
                } else if (category.title === "Keten") {
                imageSrc = "/images/categories/KetenPantolon.png"
                }

                return (
                    <Link 
                        key={category?.slug} 
                        href={`/${category?.slug}`}
                        className="bg-var(--main-bg) cursor-pointer transition-all duration-400 transform hover:scale-101 px-10"
                        >
                            <div className='w-full h-full'>
                                <Image src={imageSrc} alt={category?.title} width={150} height={200} className='object-cover w-full h-full'/>
                            </div>
                                    
                            <p className="font-medium text-lg text-white mt-10">
                            {category?.title}
                            </p>

                    </Link>
            )})}

        </div>
    )
}
