'use client'
import { useState, useEffect } from 'react'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/react/24/outline'
import Image from 'next/image'

export interface HeroSectionProps {
  className?: string
}

export default function HeroSection({ className }: HeroSectionProps) {
  const [currentSlide, setCurrentSlide] = useState(0)

  const slides = [
    { id: 1, image: '/images/categories/hero.png' },
    { id: 2, image: '/images/categories/hero2.png' },
    { id: 3, image: '/images/categories/hero3.png' }
  ]

  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length)
    }, 5000)
    return () => clearInterval(interval)
  }, [slides.length])

  return (
    <div className={`relative w-full bg-white ${className ?? ''}`}>
      <div
        className="flex transition-transform duration-500 ease-in-out"
        style={{ transform: `translateX(-${currentSlide * 100}%)` }}
      >
        {slides.map((slide, idx) => (
          <div
            key={slide.id}
            className="relative w-full flex-shrink-0 h-[60vh] min-h-[320px] sm:min-h-[380px] md:h-[90vh] md:min-h-[560px] lg:h-[95vh] lg:min-h-[680px] max-h-[1080px]"
            >
            <Image
              src={slide.image}
              alt="Hero Image"
              fill
              priority={idx === 0}
              sizes="100vw"
              className="object-cover"
            />
          </div>
        ))}
      </div>

      <button
        onClick={() =>
          setCurrentSlide((prev) => (prev === 0 ? slides.length - 1 : prev - 1))
        }
        className="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 text-[var(--campaign-bg)] bg-white/60 backdrop-blur-sm rounded-full p-2 active:scale-95"
      >
        <ChevronLeftIcon className="w-8 h-8 sm:w-10 sm:h-10" />
      </button>

      <button
        onClick={() => setCurrentSlide((prev) => (prev + 1) % slides.length)}
        className="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 text-[var(--campaign-bg)] bg-white/60 backdrop-blur-sm rounded-full p-2 active:scale-95"
      >
        <ChevronRightIcon className="w-8 h-8 sm:w-10 sm:h-10" />
      </button>

      <div className="absolute bottom-3 sm:bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
        {slides.map((_, index) => (
          <button
            key={index}
            onClick={() => setCurrentSlide(index)}
            className={`w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full transition-colors ${
              index === currentSlide
                ? 'bg-[var(--campaign-bg)]'
                : 'bg-[color:var(--campaign-bg)]/30'
            }`}
          />
        ))}
      </div>
    </div>
  )
}
