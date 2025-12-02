'use client'
import { useMainData } from '@/hooks/useMainQuery'
import ProductImageGallery from '../ui/ProductImageGallery'
import { Swiper, SwiperSlide } from 'swiper/react'
import { Navigation } from 'swiper/modules'
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/outline"
import { useRouter } from 'next/navigation'
import 'swiper/css'
import 'swiper/css/navigation'
import LoadingState from '@/components/ui/LoadingState'

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

  if (isLoading) return <LoadingState label="Yükleniyor…" />
  if (error) return <p>Hata oluştu</p>

  return (
    <div className="relative px-4 sm:px-10 py-14 bg-white">
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 relative">
        <h2 className="text-2xl font-bold text-gray-900 ml-2 sm:ml-16 text-center sm:text-left mb-6 sm:mb-0">
          POPÜLER ÜRÜNLER
        </h2>

        {/* oklar sadece masaüstünde görünür */}
        <div className="hidden sm:flex items-center gap-2 absolute right-20 z-20">
          <button className="custom-next w-10 h-10 rounded-full bg-[var(--campaign-bg)] shadow flex items-center justify-center hover:bg-[var(--campaign-header-bg)] transition cursor-pointer">
            <ChevronLeftIcon className="w-4 h-4 text-white" />
          </button>
          <button className="custom-prev w-10 h-10 rounded-full bg-[var(--campaign-bg)] shadow flex items-center justify-center hover:bg-[var(--campaign-header-bg)] transition cursor-pointer">
            <ChevronRightIcon className="w-4 h-4 text-white" />
          </button>
        </div>
      </div>

      <Swiper
        modules={[Navigation]}
        navigation={{
          prevEl: '.custom-next',
          nextEl: '.custom-prev',
        }}
        spaceBetween={15}
        slidesPerView={2} 
        breakpoints={{
          640: { slidesPerView: 3 },   // tablet küçük
          768: { slidesPerView: 4 },   // tablet yatay
          1024: { slidesPerView: 5 },  // desktop → 5 ürün
        }}
      >
        {populerProductVariants?.map((product) =>
          product.variants?.map((variant) => (
            <SwiperSlide key={variant.id}>
              <div className="cursor-pointer" onClick={() => handleProductDetail(variant.slug)}>
                
                <div className="bg-white flex items-center justify-center py-6">
                  <ProductImageGallery
                    images={variant.images}
                    alt={product.title}
                    className="object-contain w-full h-40 sm:h-48"
                  />
                </div>

                <h3 className="mt-2 text-sm font-semibold text-gray-900 line-clamp-1">
                  {product.title}
                </h3>

                <p className="text-xs text-gray-700">{product.category.title}</p>

                <p className="text-base font-bold mt-1 text-gray-900">
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
