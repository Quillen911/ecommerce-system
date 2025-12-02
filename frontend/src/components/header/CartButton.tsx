'use client'

import { ShoppingCartIcon } from '@heroicons/react/24/outline'
import { useMe } from '@/hooks/useAuthQuery'
import { useBagIndex } from '@/hooks/useBagQuery'

interface CartButtonProps {
  onClick: () => void
  className?: string
}

export default function CartButton({ onClick, className = '' }: CartButtonProps) {
  const { data: me } = useMe()
  const { data, isFetching } = useBagIndex(me?.id)

  const itemCount = data?.products?.length ?? 0

  return (
    <button
      onClick={onClick}
      className={`relative p-2 text-black hover:text-gray-500 transition-colors cursor-pointer ${className}`}
    >
      <ShoppingCartIcon className="h-6 w-6 sm:h-7 sm:w-7" />

      {itemCount > 0 && (
        <span
          className="absolute -top-1 -right-1 bg-black text-white text-[10px] sm:text-xs font-semibold rounded-full 
                     h-4 w-4 sm:h-5 sm:w-5 flex items-center justify-center"
        >
          {isFetching ? '...' : itemCount > 99 ? '99+' : itemCount}
        </span>
      )}
    </button>
  )
}
