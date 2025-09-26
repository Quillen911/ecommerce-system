'use client'
import Image from 'next/image'
import { useState } from 'react'
import { getProductImage, getImageDimensions, getResponsiveSizes, ImageConfig } from '@/lib/utils/imageUtils'

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

export default function ProductImage({ 
    product, 
    index = 0, 
    aspectRatio = 'square',
    breakpoint = 'mobile',
    className = "",
    alt,
    priority = false,
    config,
    showLoading = true,
    onError,
    onClick
}: ProductImageProps) {
    const [isLoading, setIsLoading] = useState(true)
    const [hasError, setHasError] = useState(false)
    
    const dimensions = getImageDimensions(aspectRatio)
    const sizes = getResponsiveSizes(breakpoint)
    const imageUrl = typeof product === 'string' 
        ? product 
        : getProductImage(product, index)
        
    const imageAlt = alt || product.title || 'Product image'
    
    const handleLoad = () => {
        setIsLoading(false)
    }
    
    const handleError = () => {
        setIsLoading(false)
        setHasError(true)
        onError?.()
    }
    
    if (hasError) {
        return (
            <div 
                className={`bg-gray-200 flex items-center justify-center ${className}`}
                style={{ width: dimensions.width, height: dimensions.height }}
            >
                <div className="text-center text-gray-500">
                    <svg className="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clipRule="evenodd" />
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
            style={{ width: dimensions.width, height: dimensions.height }}
        >
            {isLoading && showLoading && (
                <div 
                    className="absolute inset-0 bg-gray-200 animate-pulse flex items-center justify-center z-10"
                >
                    <div className="w-8 h-8 border-2 border-gray-300 border-t-blue-500 rounded-full animate-spin"></div>
                </div>
            )}
            
            <Image 
                src={imageUrl}
                alt={imageAlt}
                width={dimensions.width}
                height={dimensions.height}
                className={`object-contain transition-all duration-300${
                    isLoading ? 'opacity-0' : 'opacity-100'
                } ${onClick ? 'cursor-pointer hover:scale-105' : ''}`}
                style={{ 
                    width: '100%', 
                    height: '100%',
                    objectFit: 'contain'
                }}
                sizes={sizes}
                priority={priority}
                onLoad={handleLoad}
                onError={handleError}
                quality={config?.quality || 85}
                unoptimized={true}
            />
        </div>
    )
}

export function ProductCardImage({ product, ...props }: Omit<ProductImageProps, 'aspectRatio' | 'breakpoint'>) {
    return (
        <ProductImage 
            product={product}
            aspectRatio="portrait"
            breakpoint="mobile"
            className="rounded-lg"
            {...props}
        />
    )
}

export function ProductHeroImage({ product, ...props }: Omit<ProductImageProps, 'aspectRatio' | 'breakpoint'>) {
    return (
        <ProductImage 
            product={product}
            aspectRatio="wide"
            breakpoint="desktop"
            priority={true}
            className="rounded-xl"
            {...props}
        />
    )
}

export function ProductThumbnailImage({ product, ...props }: Omit<ProductImageProps, 'aspectRatio' | 'breakpoint'>) {
    return (
        <ProductImage 
            product={product}
            aspectRatio="square"
            breakpoint="mobile"
            config={{ width: 80, height: 80, quality: 70 }}
            className="rounded"
            {...props}
        />
    )
}
