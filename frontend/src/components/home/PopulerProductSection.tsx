'use client'
import { useMainData } from '@/hooks/useMainQuery'
import { Product } from '@/types/main'
import ProductImageGallery from '../ui/ProductImageGallery'

import { Swiper, SwiperSlide } from 'swiper/react'
import { Navigation } from 'swiper/modules'
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/outline"

export default function PopulerProductSection() {
  const {data: mainData, isLoading, error} = useMainData()

  const populerProductVariants = mainData?.products.flatMap((product: Product) =>
    product.variants
      .filter((variant) => variant.is_popular)
      .map((variant) => {
        const primaryImage =
          variant.images.find((img) => img.is_primary)?.image ||
          variant.images[0]?.image ||
          "/images/no-image.png"
        return {
          id: variant.id,
          title: product.title,
          category: 
            (product.category?.parent ? product.category.parent.title + " " : "") 
            + product.category?.title,
          price: variant.price,
          image: primaryImage,
          images: variant.images,
        }
      })
  ) ?? []


  if (isLoading) return <p>Yükleniyor...</p>
  if (error) return <p>Hata oluştu</p>

  return (
    <div className="relative px-10 py-14 bg-white">
      <div className="flex items-center justify-between mb-6">
        <h2 className="text-2xl font-bold">POPÜLER ÜRÜNLER</h2>

        <div className="flex items-center gap-2 absolute top-15 right-10 z-20">
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
        {populerProductVariants.map((variant) => (
          <SwiperSlide key={variant.id}>
            <div className="cursor-pointer">
              <div className="bg-white flex items-center justify-center py-10">
                <ProductImageGallery 
                  images={variant.images}
                  alt={variant.title}
                  className="object-contain w-full h-80"
                />
              </div>
              <h3 className="mt-3 text-base font-semibold text-gray-900 line-clamp-1">
                {variant.title}
              </h3>
              <p className="text-sm text-gray-500">{variant.category}</p>
              <p className="text-lg font-bold mt-1">{variant.price} ₺</p>
            </div>
          </SwiperSlide>
        ))}
      </Swiper>
    </div>
  )
}
