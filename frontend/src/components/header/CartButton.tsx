'use client'
import { ShoppingCartIcon } from '@heroicons/react/24/outline'
import { CartButtonProps } from '@/types/header'

export default function CartButton({ 
  itemCount = 0, 
  onClick, 
  className = '' 
}: CartButtonProps) {
  return (
    <button
      onClick={onClick}
      className={`relative p-2 text-white hover:text-gray-500 transition-colors cursor-pointer ${className}`}
    >
      <ShoppingCartIcon className="h-6 w-6" />
      {itemCount > 0 && (
        <span className="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
          {itemCount > 99 ? '99+' : itemCount}
        </span>
      )}
    </button>
  )
}