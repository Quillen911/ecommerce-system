'use client'
import { useMainData } from '@/hooks/useMainQuery'
import { useCategory } from '@/contexts/CategoryContext'

export default function CategorySection() {
    const { selectedCategory, setSelectedCategory } = useCategory()
    const { data: mainData, isLoading, error } = useMainData()
    
    if (error) return null
    if (isLoading) return null
    
    const categories = mainData?.categories || []

    return (
        <div 
        className='relative flex gap-15 justify-center p-10 bg-gray-100'
        >
            <h2 className="absolute top-1 left-1/2 transform -translate-x-1/2 text-xl font-bold text-gray-700 animate-fadeIn mb-10">
                Kategoriler
            </h2>
            {categories.map((item, index) => (
                <button 
                key={item.id} 
                onClick={() => setSelectedCategory(item.category_slug)}
                className={`w-45 h-45 shadow-lg rounded-lg flex items-center justify-center flex-shrink-0 cursor-pointer transition-all duration-300 transform hover:scale-105 ${
                    selectedCategory === item.category_slug 
                        ? 'bg-blue-500 text-white shadow-xl scale-105' 
                        : 'bg-white text-gray-900 hover:bg-gray-50'
                }`}
                style={{
                    animation: `fadeInUp 0.6s ease-out ${index * 100}ms forwards`
                }}>
                    <span className="font-medium text-lg">
                        {item.category_title}
                    </span>
                </button>
            ))}
        </div>
    )
}
