'use client'
import Image from 'next/image'
import { useState, useRef } from 'react'

interface ProductImageGalleryProps {
  images: { id?: number; image?: string; is_primary?: boolean }[] | null | undefined
  onClick?: () => void
  alt?: string
  className?: string
}

export default function ProductImageGallery({
  images,
  onClick,
  alt = 'Product image',
  className = ''
}: ProductImageGalleryProps) {
  const [currentIndex, setCurrentIndex] = useState(0)
  const containerRef = useRef<HTMLDivElement | null>(null)

  const fallbackImage = '/images/no-image.png'

  const safeImages = Array.isArray(images) && images.length > 0
    ? [...images].sort((a, b) => (b.is_primary ? 1 : 0) - (a.is_primary ? 1 : 0))
    : [{ id: 0, image: fallbackImage }]

  const currentImage =
    safeImages[currentIndex]?.image && safeImages[currentIndex]?.image.trim() !== ''
      ? safeImages[currentIndex].image!
      : fallbackImage

  const handleMouseMove = (e: React.MouseEvent<HTMLDivElement>) => {
    if (!containerRef.current || safeImages.length <= 1) return
    const rect = containerRef.current.getBoundingClientRect()
    const x = e.clientX - rect.left // mouse’un kutudaki X pozisyonu
    const ratio = x / rect.width // 0.0 - 1.0 arası
    const index = Math.floor(ratio * safeImages.length)
    setCurrentIndex(index)
  }    

  return (
    <div
      ref={containerRef}
      className={`relative ${className}`}
      onMouseMove={handleMouseMove}
      onMouseLeave={() => setCurrentIndex(0)}
      onClick={onClick}
    >
      <Image
        src={currentImage}
        alt={alt}
        width={500}
        height={500}
        className="object-contain w-full h-full rounded-lg"
      />
    </div>
  )
}
