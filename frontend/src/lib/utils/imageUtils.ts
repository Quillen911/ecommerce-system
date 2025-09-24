export interface ImageConfig {
    width?: number
    height?: number
    quality?: number
    format?: 'webp' | 'jpeg' | 'png'
    blur?: boolean
}

const DEFAULT_PRODUCT_IMAGE = '/images/no-image.svg'
const DEFAULT_STORE_IMAGE = '/images/no-store.svg'

export const getProductImage = (product: any, index: number = 0): string => {
    if (!product?.images || !Array.isArray(product.images) || product.images.length === 0) {
        return DEFAULT_PRODUCT_IMAGE
    }

    if (index >= product.images.length) {
        return DEFAULT_PRODUCT_IMAGE
    }

    const imageData = product.images[index]
    
    return imageData?.image || DEFAULT_PRODUCT_IMAGE
}

export const getStoreImage = (store: any): string => {
    return store?.image || DEFAULT_STORE_IMAGE
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
