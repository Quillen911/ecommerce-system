'use client'
import { useMainData } from '@/hooks/useMainQuery'
import ProductImageGallery from '../ui/ProductImageGallery'
import { Swiper, SwiperSlide } from 'swiper/react'
import { Navigation } from 'swiper/modules'
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/outline"
import { useRouter } from 'next/navigation'
import 'swiper/css'
import 'swiper/css/navigation'

export interface PopulerProductSectionProps {
  className?: string
}

export default function PopulerProductSection({ className }: PopulerProductSectionProps) {
  const { data: mainData, isLoading, error } = useMainData()
  const router = useRouter()

  const handleProductDetail = (slug: string) => {
    router.push(`/product/${slug}`)
  }

  const populerProductVariants = mainData?.products

  if (isLoading) return <p>Yükleniyor...</p>
  if (error) return <p>Hata oluştu</p>

  return (
    <div className="relative px-4 sm:px-10 py-14 bg-[var(--main-bg)]">
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 relative">
        <h2 className="text-2xl font-bold text-white ml-2 sm:ml-16 text-center sm:text-left mb-6 sm:mb-0">
          POPÜLER ÜRÜNLER
        </h2>

        {/* oklar sadece masaüstünde görünür */}
        <div className="hidden sm:flex items-center gap-2 absolute top-10 right-20 z-20">
          <button className="custom-next w-10 h-10 rounded-full bg-gray-200 shadow flex items-center justify-center hover:bg-gray-300 transition cursor-pointer">
            <ChevronLeftIcon className="w-4 h-4 text-black" />
          </button>
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
        slidesPerView={1.2}
        breakpoints={{
          640: { slidesPerView: 2 },
          1024: { slidesPerView: 3 },
        }}
      >
        {populerProductVariants?.map((product) =>
          product.variants?.map((variant) => (
            <SwiperSlide key={variant.id}>
              <div className="cursor-pointer" onClick={() => handleProductDetail(variant.slug)}>
                <div className="bg-[var(--main-bg)] flex items-center justify-center py-10 sm:py-15">
                  <ProductImageGallery
                    images={variant.images}
                    alt={product.title}
                    className="object-contain w-full h-64 sm:h-80"
                  />
                </div>
                <h3 className="mt-3 text-base font-semibold text-white line-clamp-1 px-4 sm:px-10">
                  {product.title}
                </h3>
                <p className="text-sm text-white px-4 sm:px-10">{product.category.title}</p>
                <p className="text-lg font-bold mt-1 text-white px-4 sm:px-10">
                  ₺{variant.price_cents / 100}
                </p>
              </div>
            </SwiperSlide>
          ))
        )}
      </Swiper>
    </div>
  )
}
