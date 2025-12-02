'use client'

import Image from 'next/image'
import Link from 'next/link'

export default function GalleryLayout() {
  return (
    <div className="w-full bg-[var(--campaign-light-bg)] p-3 sm:p-4">
      <div className="grid grid-cols-12 auto-rows-[170px] sm:auto-rows-[190px] lg:auto-rows-[210px] gap-3 sm:gap-4">
        {/* Sol üst geniş */}
        <Link
          href="/urun/1"
          className="relative col-span-7 row-span-2 rounded-lg overflow-hidden group min-h-[180px]"
        >
          <Image
            src="/images/categories/1.png"
            alt="Görsel 1"
            fill
            className="object-cover transition-transform duration-700 ease-out group-hover:scale-105"
          />
        </Link>

        {/* Sağ dikey pano */}
        <Link
          href="/urun/2"
          className="relative col-start-8 col-span-5 row-span-3 rounded-lg overflow-hidden group min-h-[180px]"
        >
          <Image
            src="/images/categories/3.png"
            alt="Görsel 2"
            fill
            className="object-cover transition-transform duration-700 ease-out group-hover:scale-105"
          />
        </Link>

        {/* Alt sol küçük */}
        <Link
          href="/urun/3"
          className="relative col-span-4 row-span-1 rounded-lg overflow-hidden group min-h-[140px]"
        >
          <Image
            src="/images/categories/2.png"
            alt="Görsel 3"
            fill
            className="object-cover transition-transform duration-700 ease-out group-hover:scale-105"
          />
        </Link>

        {/* Alt orta küçük */}
        <Link
          href="/urun/4"
          className="relative col-span-3 row-span-1 rounded-lg overflow-hidden group min-h-[140px]"
        >
          <Image
            src="/images/categories/2.png"
            alt="Görsel 4"
            fill
            className="object-cover transition-transform duration-700 ease-out group-hover:scale-105"
          />
        </Link>
      </div>
    </div>
  )
}
