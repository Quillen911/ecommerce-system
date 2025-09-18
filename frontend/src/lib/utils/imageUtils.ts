export interface ImageConfig {
    width?: number
    height?: number
    quality?: number
    format?: 'webp' | 'jpeg' | 'png'
    blur?: boolean
}

export const getProductImageUrl = (
    imagePath: string | null | undefined, 
    config?: ImageConfig
): string => {
    if (!imagePath) {
        return '/images/no-image.svg'
    }
    
    // Eğer tam URL ise direkt döndür
    if (imagePath.startsWith('http')) {
        return imagePath
    }
    
    // Storage path'ini oluştur
    const baseUrl = process.env.NEXT_PUBLIC_API_URL?.replace('/api', '') || 'http://localhost:8000'
    let url = `${baseUrl}/storage/productsImages/${imagePath}`
    
    // Query parameters ekle (Laravel image optimization için)
    if (config) {
        const params = new URLSearchParams()
        if (config.width) params.append('w', config.width.toString())
        if (config.height) params.append('h', config.height.toString())
        if (config.quality) params.append('q', config.quality.toString())
        if (config.format) params.append('f', config.format)
        if (config.blur) params.append('blur', '1')
        
        if (params.toString()) {
            url += `?${params.toString()}`
        }
    }
    
    return url
}

export const getProductImage = (product: any, index: number = 0, config?: ImageConfig): string => {
    if (!product.images || !Array.isArray(product.images) || product.images.length === 0) {
        return '/images/no-image.svg'
    }
    
    const imagePath = product.images[index]
    return getProductImageUrl(imagePath, config)
}

export const getStoreImage = (store: any, config?: ImageConfig): string => {
    return getProductImageUrl(store.image, config) || '/images/no-store.svg'
}

export const getImageDimensions = (aspectRatio: 'square' | 'portrait' | 'landscape' | 'wide') => {
    switch (aspectRatio) {
        case 'square':
            return { width: 300, height: 300 }
        case 'portrait':
            return { width: 200, height: 280 }
        case 'landscape':
            return { width: 320, height: 240 }
        case 'wide':
            return { width: 400, height: 200 }
        default:
            return { width: 300, height: 300 }
    }
}

export const getResponsiveSizes = (breakpoint: 'mobile' | 'tablet' | 'desktop' | 'wide') => {
    switch (breakpoint) {
        case 'mobile':
            return '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 25vw'
        case 'tablet':
            return '(max-width: 1024px) 50vw, 25vw'
        case 'desktop':
            return '(max-width: 1200px) 33vw, 25vw'
        case 'wide':
            return '25vw'
        default:
            return '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 25vw'
    }
}
