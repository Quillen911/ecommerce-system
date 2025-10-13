'use client'
import { useMainData } from '@/hooks/useMainQuery'
import ProductImageGallery from '../ui/ProductImageGallery'

import { Swiper, SwiperSlide } from 'swiper/react'
import { Navigation } from 'swiper/modules'
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/outline"
import { useRouter } from 'next/navigation'

export interface PopulerProductSectionProps {
    className?: string
}

export default function PopulerProductSection({ className }: PopulerProductSectionProps) {
  const {data: mainData, isLoading, error} = useMainData()
  const router = useRouter()

  const handleProductDetail = (slug: string) => {
    router.push(`/product/${slug}`)
  }

  const populerProductVariants = mainData?.products

  if (isLoading) return <p>Yükleniyor...</p>
  if (error) return <p>Hata oluştu</p>

  return (
    <div className="relative px-10 py-14 bg-var(--main-bg)">
      <div className="flex items-center justify-between mb-6">
        <div className="absolute left-42 transform -translate-x-1/3">
          <h2 className="text-2xl font-bold text-white">POPÜLER ÜRÜNLER</h2>
        </div>
        
        <div className="flex items-center gap-2 absolute top-10 right-20 z-20">
          {/* Sol buton */}
          <button className="custom-next w-10 h-10 rounded-full bg-gray-200 shadow flex items-center justify-center hover:bg-gray-300 transition cursor-pointer">
            <ChevronLeftIcon className="w-4 h-4 text-black" />
          </button>

          {/* Sağ buton */}
          <button className="custom-prev w-10 h-10 rounded-full bg-gray-200 shadow flex items-center justify-center hover:bg-gray-300 transition cursor-pointer">
            <ChevronRightIcon className="w-4 h-4 text-black" />
          </button>
        </div>

      </div>

      <Swiper
        modules={[Navigation]}
        navigation={{
          prevEl: '.custom-next',
          nextEl: '.custom-prev',
        }}
        spaceBetween={20}
        slidesPerView={3}
      >
        {populerProductVariants?.map((product) => (
          product.variants?.map((variant) => (
          <SwiperSlide key={variant.id}>
            <div className="cursor-pointer">
              <div className="bg-var(--main-bg) flex items-center justify-center py-15">
                <ProductImageGallery 
                  images={variant.images}
                  alt={product.title}
                  className="object-contain w-full h-80"
                  onClick={() => handleProductDetail(variant.slug)}
                />
              </div>
              <h3 className="mt-3 text-base font-semibold text-white line-clamp-1 px-10">
                {product.title}
              </h3>
              <p className="text-sm text-white px-10">{product.category.title}</p>
              <p className="text-lg font-bold mt-1 text-white px-10"> ₺{variant.price_cents/100}</p>
            </div>
          </SwiperSlide>
        ))))}
      </Swiper>
    </div>
  )
}
