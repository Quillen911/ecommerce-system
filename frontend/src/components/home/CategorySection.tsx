'use client'
import { useMainData } from '@/hooks/useMainQuery'
import { useRouter } from 'next/navigation'

export default function CategorySection() {
    const router = useRouter()
    const { data: mainData, isLoading, error } = useMainData()
    const handleCategoryClick = (category_slug: string) => {
        router.push(`/${category_slug}`)
    }
    if (error) {
        return null
    }
    
    if (isLoading) return null
    
    const categories = mainData?.categories || []

    return (
        <div 
        className='flex gap-15 justify-center p-10 bg-gray-100'
        >
            {categories.map((item) => (
                <button 
                key={item.id} 
                onClick={() => handleCategoryClick(item.category_slug)}
                className='w-45 h-45 bg-white shadow-lg rounded-lg flex items-center justify-center flex-shrink-0'>
                    <span className="text-gray-900 font-medium text-lg">
                        {item.category_title}
                    </span>
                </button>
            ))}
        </div>
    )
}
