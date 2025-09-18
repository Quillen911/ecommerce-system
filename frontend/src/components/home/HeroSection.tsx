'use client'
import { useState, useEffect } from 'react'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/react/24/outline'
export default function HeroSection() {
    const [currentSlide, setCurrentSlide] = useState(0)
    
    // Örnek resimler (gerçek resimler eklenebilir)
    const slides = [
        {
            id: 1,
            image: '/api/placeholder/800/400',
            title: 'Kampanya 1',
            description: 'Özel indirimler'
        },
        {
            id: 2,
            image: '/api/placeholder/800/400',
            title: 'Kampanya 2',
            description: 'Yeni ürünler'
        },
        {
            id: 3,
            image: '/api/placeholder/800/400',
            title: 'Kampanya 3',
            description: 'Sezon sonu'
        }
    ]
    
    // Otomatik geçiş
    useEffect(() => {
        const interval = setInterval(() => {
            setCurrentSlide((prev) => (prev + 1) % slides.length)
        }, 5000) // 5 saniyede bir değişir
        
        return () => clearInterval(interval)
    }, [slides.length])
    
    return (
        <div className="relative h-96 overflow-hidden">
            <div className="flex transition-transform duration-500 ease-in-out"
                 style={{ transform: `translateX(-${currentSlide * 100}%)` }}>
                {slides.map((slide) => (
                    <div key={slide.id} className="w-full flex-shrink-0 relative">
                        <div className="w-full h-96 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <div className="text-center text-white">
                                <h2 className="text-4xl font-bold mb-4">{slide.title}</h2>
                                <p className="text-xl">{slide.description}</p>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
            
            {/* Sol/Sağ Oklar */}
            <button 
                onClick={() => setCurrentSlide((prev) => prev === 0 ? slides.length - 1 : prev - 1)}
                className="absolute left-1 top-1/2 transform -translate-y-1/2 bg-none text-white rounded-full hover:bg-opacity-75"
            >
                <ChevronLeftIcon className="w-10 h-10" />
            </button>
            <button 
                onClick={() => setCurrentSlide((prev) => (prev + 1) % slides.length)}
                className="absolute right-1 top-1/2 transform -translate-y-1/2 bg-none text-white rounded-full hover:bg-opacity-75"
            >
                <ChevronRightIcon className="w-10 h-10" />
            </button>
            
            {/* Dots Indicator */}
            <div className="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                {slides.map((_, index) => (
                    <button
                        key={index}
                        onClick={() => setCurrentSlide(index)}
                        className={`w-3 h-3 rounded-full transition-colors ${
                            index === currentSlide ? 'bg-white' : 'bg-white bg-opacity-50'
                        }`}
                    />
                ))}
            </div>
        </div>
    )
}