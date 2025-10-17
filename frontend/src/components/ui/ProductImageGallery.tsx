"use client"

import Image from "next/image"
import { useRef, useState } from "react"

interface GalleryImage {
  id?: number
  image?: string | null
  is_primary?: boolean
}

interface ProductImageGalleryProps {
  images: GalleryImage[] | null | undefined
  onClick?: () => void
  alt?: string
  className?: string
}

export default function ProductImageGallery({
  images,
  onClick,
  alt = "Product image",
  className = "",
}: ProductImageGalleryProps) {
  const [currentIndex, setCurrentIndex] = useState(0)
  const containerRef = useRef<HTMLDivElement | null>(null)

  const fallbackImage = "/images/no-image.png"

  const safeImages: GalleryImage[] =
    Array.isArray(images) && images.length > 0
      ? [...images].sort((a, b) => (b.is_primary ? 1 : 0) - (a.is_primary ? 1 : 0))
      : [{ id: 0, image: fallbackImage }]

  const rawImage = safeImages[currentIndex]?.image ?? null
  const currentImage =
    typeof rawImage === "string" && rawImage.trim() !== "" ? rawImage : fallbackImage

  const handleMouseMove = (event: React.MouseEvent<HTMLDivElement>) => {
    if (!containerRef.current || safeImages.length <= 1) return

    const rect = containerRef.current.getBoundingClientRect()
    const x = event.clientX - rect.left
    const ratio = x / rect.width
    const index = Math.min(Math.floor(ratio * safeImages.length), safeImages.length - 1)
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
        className="h-full w-full rounded-lg object-contain"
      />
    </div>
  )
}