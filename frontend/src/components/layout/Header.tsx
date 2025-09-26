'use client'
import { useMe } from '@/hooks/useAuthQuery'
import Logo from './HeaderLogo'
import UserMenu from './UserMenu'
import CartButton from './CartButton'
import SearchBar from './SearchBar'
import { useRouter } from 'next/navigation'

export default function Header() {
  const { data: user, isLoading } = useMe()
  const router = useRouter()
  const handleCartClick = () => {
    router.push('/bag')
  }

  return (
    <header className="bg-[var(--main-bg)] text-[var(--text)] sticky top-0 z-40">
      <div className="w-full px-10">
        <div className="flex items-center justify-between h-16">
          <div className="flex items-center">
            <Logo />
          </div>

          <div className="flex items-center space-x-4">
            <SearchBar />
            <CartButton onClick={handleCartClick} />
            <UserMenu user={user} isLoading={isLoading}/>
          </div>
        </div>
      </div>
    </header>

  )
}