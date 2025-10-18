'use client'
import { useState, useEffect } from 'react'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/react/24/outline'

export interface HeroSectionProps {
  className?: string
}

export default function HeroSection({ className }: HeroSectionProps) {
  const [currentSlide, setCurrentSlide] = useState(0)

  const slides = [
    { id: 1, image: '/api/placeholder/800/400', title: 'Kampanya 1', description: 'Özel indirimler' },
    { id: 2, image: '/api/placeholder/800/400', title: 'Kampanya 2', description: 'Yeni ürünler' },
    { id: 3, image: '/api/placeholder/800/400', title: 'Kampanya 3', description: 'Sezon sonu' }
  ]

  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length)
    }, 5000)
    return () => clearInterval(interval)
  }, [slides.length])

  return (
    <div className="relative h-175 overflow-hidden bg-[var(--main-bg)] w-full">
      <div
        className="flex transition-transform duration-500 ease-in-out"
        style={{ transform: `translateX(-${currentSlide * 100}%)` }}
      >
        {slides.map((slide) => (
          <div key={slide.id} className="w-full flex-shrink-0 relative">
            <div className="w-full h-175 bg-gradient-to-r from-[var(--main-bg)] to-[var(--muted)] flex items-center justify-center px-4 sm:px-8">
              <div className="text-center break-words max-w-full">
                <h2 className="text-4xl font-bold mb-4 text-[var(--accent-dark)]">{slide.title}</h2>
                <p className="text-xl text-[var(--muted)]">{slide.description}</p>
                <div className="mt-6">
                  <button className="px-5 py-3 rounded-md bg-[var(--accent)] text-white hover:brightness-90 transition">
                    Keşfet
                  </button>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>

      <button
        onClick={() =>
          setCurrentSlide((prev) => (prev === 0 ? slides.length - 1 : prev - 1))
        }
        className="absolute left-2 sm:left-4 top-1/2 transform -translate-y-1/2 text-[var(--accent-dark)]"
      >
        <ChevronLeftIcon className="w-10 h-10" />
      </button>

      <button
        onClick={() => setCurrentSlide((prev) => (prev + 1) % slides.length)}
        className="absolute right-2 sm:right-4 top-1/2 transform -translate-y-1/2 text-[var(--accent-dark)]"
      >
        <ChevronRightIcon className="w-10 h-10" />
      </button>

      <div className="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
        {slides.map((_, index) => (
          <button
            key={index}
            onClick={() => setCurrentSlide(index)}
            className={`w-3 h-3 rounded-full transition-colors ${
              index === currentSlide
                ? 'bg-[var(--accent-dark)]'
                : 'bg-[color:var(--accent-dark)]/30'
            }`}
          />
        ))}
      </div>
    </div>
  )
}
