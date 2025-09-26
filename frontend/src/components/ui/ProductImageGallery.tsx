'use client'
import Image from 'next/image'
import { useState } from 'react'

interface ProductImageGalleryProps {
  images: { id?: number; image?: string; is_primary?: boolean }[] | null | undefined
  alt?: string
  aspectRatio?: 'square' | 'portrait' | 'landscape' | 'wide'
  className?: string
}

export default function ProductImageGallery({
  images,
  alt = 'Product image',
  aspectRatio = 'square',
  className = ''
}: ProductImageGalleryProps) {
  const [currentIndex, setCurrentIndex] = useState(0)

  const fallbackImage = '/images/no-image.png'

  const safeImages = Array.isArray(images) && images.length > 0
    ? [...images].sort((a, b) => (b.is_primary ? 1 : 0) - (a.is_primary ? 1 : 0))
    : [{ id: 0, image: fallbackImage }]

  const handlePrev = () => {
    setCurrentIndex((prev) => (prev === 0 ? safeImages.length - 1 : prev - 1))
  }

  const handleNext = () => {
    setCurrentIndex((prev) => (prev === safeImages.length - 1 ? 0 : prev + 1))
  }

  const currentImage =
    safeImages[currentIndex]?.image && safeImages[currentIndex]?.image.trim() !== ''
      ? safeImages[currentIndex].image!
      : fallbackImage

  return (
    <div className={`relative ${className}`}>
      {/* Ana resim */}
      <Image
        src={currentImage}
        alt={alt}
        width={500}
        height={500}
        className="object-contain w-full h-full rounded-lg"
      />

      {/* Sol Ok */}
      {safeImages.length > 1 && (
        <button
          onClick={handlePrev}
          className="absolute left-2 top-1/2 -translate-y-1/2 text-black text-2xl p-2 hover:scale-160 transition-transform duration-100 ease-in-out cursor-pointer"
        >
          ‹
        </button>
      )}

      {/* Sağ Ok */}
      {safeImages.length > 1 && (
        <button
          onClick={handleNext}
          className="absolute right-2 top-1/2 -translate-y-1/2 text-black text-2xl p-2 hover:scale-160 transition-transform duration-100 ease-in-out cursor-pointer"
        >
          ›
        </button>
      )}
    </div>
  )
}
