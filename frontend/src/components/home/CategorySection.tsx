'use client'
import { useMainData } from '@/hooks/useMainQuery'
import { useCategory } from '@/contexts/CategoryContext'

export default function CategorySection() {
    const { selectedCategory, setSelectedCategory } = useCategory()
    const { data: mainData, isLoading, error } = useMainData()
    
    if (error) return null
    if (isLoading) return null
    
    const gameCategories = Array.from(
        new Set(
            (mainData?.products?.data || [])
                .flatMap(p =>
                    p.computed_attributes
                        ?.filter(attr => attr.code === "game")
                        .map(attr => attr.slug) || []
                )
        )
    )
console.log(gameCategories)
    return (
        <div className='relative flex flex-wrap gap-4 justify-center p-10 bg-gray-100'>
            <h2 className="absolute top-1 left-1/2 transform -translate-x-1/2 text-xl font-bold text-gray-700 animate-fadeIn mb-10">
                Kategoriler
            </h2>

            {gameCategories.map((game, index) => (
                <button 
                    key={game} 
                    onClick={() => setSelectedCategory(game.toLowerCase())}
                    className={`w-[150px] h-[150px] shadow-lg rounded-lg flex items-center justify-center flex-shrink-0 cursor-pointer transition-all duration-300 transform hover:scale-105 ${
                        selectedCategory === game.toLowerCase()
                            ? 'bg-blue-500 text-white shadow-xl scale-105' 
                            : 'bg-white text-gray-900 hover:bg-gray-50'
                    }`}
                    style={{
                        animation: `fadeInUp 0.6s ease-out ${index * 100}ms forwards`
                    }}>
                    <span className="font-medium text-lg">
                    {game.replace(/-/g, " ").toUpperCase()}
                    </span>
                </button>
            ))}
        </div>
    )
}
