'use client'

import Image from 'next/image'
import { useMemo, useState } from 'react'
import {
  getProductImage,
  getImageDimensions,
  getResponsiveSizes,
  ImageConfig,
} from '@/lib/utils/imageUtils'

interface ProductImageProps {
  product: any | string
  index?: number
  aspectRatio?: 'square' | 'portrait' | 'landscape' | 'wide'
  breakpoint?: 'mobile' | 'tablet' | 'desktop' | 'wide'
  className?: string
  alt?: string
  priority?: boolean
  config?: ImageConfig
  showLoading?: boolean
  onError?: () => void
  onClick?: () => void
}

const normalizeUrl = (url: string | null | undefined): string => {
  if (!url) return ''
  if (url.startsWith('http') || url.startsWith('data:')) return url
  const base =
    process.env.NEXT_PUBLIC_ASSET_URL ??
    process.env.NEXT_PUBLIC_API_URL ??
    ''
  if (!base) return url
  return `${base.replace(/\/$/, '')}/${url.replace(/^\//, '')}`
}

export default function ProductImage({
  product,
  index = 0,
  aspectRatio = 'square',
  breakpoint = 'mobile',
  className = '',
  alt,
  priority = false,
  config,
  showLoading = true,
  onError,
  onClick,
}: ProductImageProps) {
  const [isLoading, setIsLoading] = useState(true)
  const [hasError, setHasError] = useState(false)

  const defaultDims = getImageDimensions(aspectRatio)
  const width = config?.width ?? defaultDims.width
  const height = config?.height ?? defaultDims.height
  const sizes = getResponsiveSizes(breakpoint)

  const imageUrl = useMemo(() => {
    const raw =
      typeof product === 'string'
        ? product
        : getProductImage(product, index)
    return normalizeUrl(raw)
  }, [product, index])

  const imageAlt =
    alt ??
    (typeof product === 'object' && product?.title
      ? String(product.title)
      : 'Product image')

  const handleLoad = () => setIsLoading(false)

  const handleError = () => {
    setIsLoading(false)
    setHasError(true)
    onError?.()
  }

  if (!imageUrl || hasError) {
    return (
      <div
        className={`flex items-center justify-center bg-gray-200 ${className}`}
        style={{ aspectRatio: `${width} / ${height}` }}
      >
        <div className="text-center text-gray-500">
          <svg
            className="mx-auto mb-2 h-12 w-12"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              fillRule="evenodd"
              d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
              clipRule="evenodd"
            />
          </svg>
          <p className="text-sm">Resim yüklenemedi</p>
        </div>
      </div>
    )
  }

  return (
    <div
      className={`relative overflow-hidden ${className}`}
      onClick={onClick}
      style={{ aspectRatio: `${width} / ${height}` }}
    >
      {isLoading && showLoading && (
        <div className="absolute inset-0 z-10 flex items-center justify-center bg-gray-200 animate-pulse">
          <div className="h-8 w-8 animate-spin rounded-full border-2 border-gray-300 border-t-blue-500" />
        </div>
      )}

      <Image
        src={imageUrl}
        alt={imageAlt}
        width={width}
        height={height}
        className={`h-full w-full object-contain transition-all duration-300 ${
          isLoading ? 'opacity-0' : 'opacity-100'
        } ${onClick ? 'cursor-pointer hover:scale-105' : ''}`}
        sizes={sizes}
        priority={priority}
        onLoad={handleLoad}
        onError={handleError}
        quality={config?.quality ?? 85}
        unoptimized
      />
    </div>
  )
}

export const ProductCardImage = ({
  product,
  ...props
}: Omit<ProductImageProps, 'aspectRatio' | 'breakpoint'>) => (
  <ProductImage
    product={product}
    aspectRatio="portrait"
    breakpoint="mobile"
    className="rounded-lg"
    {...props}
  />
)

export const ProductHeroImage = ({
  product,
  ...props
}: Omit<ProductImageProps, 'aspectRatio' | 'breakpoint'>) => (
  <ProductImage
    product={product}
    aspectRatio="wide"
    breakpoint="desktop"
    priority
    className="rounded-xl"
    {...props}
  />
)

export const ProductThumbnailImage = ({
  product,
  ...props
}: Omit<ProductImageProps, 'aspectRatio' | 'breakpoint'>) => (
  <ProductImage
    product={product}
    aspectRatio="square"
    breakpoint="mobile"
    config={{ width: 80, height: 80, quality: 70 }}
    className="rounded"
    {...props}
  />
)
