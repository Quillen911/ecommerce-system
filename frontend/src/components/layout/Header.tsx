'use client'
import { useMe } from '@/hooks/useAuthQuery'
import Logo from './HeaderLogo'
import UserMenu from './UserMenu'
import CartButton from './CartButton'
import SearchBar from './SearchBar'

export default function Header() {
  const { data: user, isLoading } = useMe()

  const handleCartClick = () => {
    // TODO: Cart functionality
  }

  return (
    <header className="bg-white border-b border-gray-200 sticky top-0 z-40">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          <div className="flex items-center">
            <Logo />
          </div>

          <div className="flex items-center space-x-4 ">
            <SearchBar />
            <CartButton onClick={handleCartClick} />
            <UserMenu user={user} isLoading={isLoading}/>
          </div>
        </div>
      </div>
    </header>
  )
}